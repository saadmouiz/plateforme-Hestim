<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmploiDuTemps;
use App\Models\ReservationPlace;
use App\Models\Cour;

class EmploiDuTempsController extends Controller
{
    public function index()
    {
        return view('etudiant.emploi-du-temps');
    }

    public function cours()
    {
        return view('etudiant.cours');
    }

    public function reservations(Request $request)
    {
        $coursId = $request->get('cours_id');
        $emploiId = $request->get('emploi_id');
        
        // Récupérer les cours de l'étudiant
        $groupes = auth()->user()->groupes;
        $cours = Cour::whereHas('groupes', function($q) use ($groupes) {
            $q->whereIn('groupes.id', $groupes->pluck('id'));
        })->with(['emploisDuTemps.salle', 'enseignant'])->get();
        
        $selectedCours = null;
        $selectedEmploi = null;
        $reservations = collect();
        $capacite = 0;
        
        if ($coursId && $emploiId) {
            $selectedCours = $cours->find($coursId);
            if ($selectedCours) {
                $selectedEmploi = $selectedCours->emploisDuTemps->find($emploiId);
                if ($selectedEmploi) {
                    $capacite = $selectedEmploi->salle->capacite ?? 0;
                    // Récupérer les réservations pour cet emploi du temps
                    $reservations = ReservationPlace::where('emploi_du_temps_id', $emploiId)
                        ->where('statut', 'reservee')
                        ->get();
                }
            }
        }
        
        return view('etudiant.reservations', compact('cours', 'selectedCours', 'selectedEmploi', 'reservations', 'capacite'));
    }

    public function storeReservation(Request $request)
    {
        try {
            $validated = $request->validate([
                'cours_id' => 'required|exists:cours,id',
                'emploi_du_temps_id' => 'required|exists:emploi_du_temps,id',
                'numero_place' => 'required|integer|min:1',
            ], [
                'cours_id.required' => 'Le cours est obligatoire.',
                'emploi_du_temps_id.required' => 'L\'emploi du temps est obligatoire.',
                'numero_place.required' => 'Le numéro de place est obligatoire.',
                'numero_place.min' => 'Le numéro de place doit être supérieur à 0.',
            ]);

            // Vérifier que l'étudiant appartient au groupe du cours
            $emploi = EmploiDuTemps::with('cours.groupes')->findOrFail($validated['emploi_du_temps_id']);
            $groupes = auth()->user()->groupes;
            $hasAccess = $emploi->cours->groupes->whereIn('id', $groupes->pluck('id'))->isNotEmpty();
            
            if (!$hasAccess) {
                return redirect()->back()
                    ->with('error', 'Vous n\'avez pas accès à ce cours.');
            }

            // Vérifier la capacité de la salle
            $salle = $emploi->salle;
            if ($validated['numero_place'] > $salle->capacite) {
                return redirect()->back()
                    ->with('error', 'Le numéro de place dépasse la capacité de la salle.');
            }

            // Vérifier si l'étudiant a déjà réservé une place pour ce cours
            $existingReservation = ReservationPlace::where('etudiant_id', auth()->id())
                ->where('emploi_du_temps_id', $validated['emploi_du_temps_id'])
                ->where('statut', 'reservee')
                ->first();
            
            if ($existingReservation) {
                return redirect()->back()
                    ->with('error', 'Vous avez déjà réservé une place pour ce cours.');
            }

            // Vérifier si la place est déjà réservée
            $placeReserved = ReservationPlace::where('emploi_du_temps_id', $validated['emploi_du_temps_id'])
                ->where('numero_place', $validated['numero_place'])
                ->where('statut', 'reservee')
                ->exists();
            
            if ($placeReserved) {
                return redirect()->back()
                    ->with('error', 'Cette place est déjà réservée.');
            }

            ReservationPlace::create([
                'etudiant_id' => auth()->id(),
                'emploi_du_temps_id' => $validated['emploi_du_temps_id'],
                'cours_id' => $validated['cours_id'],
                'numero_place' => $validated['numero_place'],
                'statut' => 'reservee',
            ]);

            return redirect()->back()
                ->with('success', 'Place réservée avec succès !');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function cancelReservation($id)
    {
        try {
            $reservation = ReservationPlace::where('etudiant_id', auth()->id())
                ->findOrFail($id);
            
            $reservation->update(['statut' => 'annulee']);
            
            return redirect()->back()
                ->with('success', 'Réservation annulée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'annulation');
        }
    }
}
