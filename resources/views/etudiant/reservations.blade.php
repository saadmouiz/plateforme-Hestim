@extends('layouts.dashboard')

@section('title', 'Réserver une Place')
@section('page-title', 'Dashboard Etudiant')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center space-x-4">
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold">Réserver une Place</h2>
                <p class="text-blue-100 mt-1">Réservez votre place pour un cours</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Sélection du cours -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Sélectionner un cours</h3>
        <form method="GET" action="{{ route('etudiant.reservations') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cours</label>
                    <select name="cours_id" id="coursSelect" onchange="updateEmplois()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                        <option value="">Sélectionner un cours</option>
                        @foreach($cours as $c)
                            <option value="{{ $c->id }}" {{ $selectedCours && $selectedCours->id == $c->id ? 'selected' : '' }}>
                                {{ $c->nom }} ({{ $c->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Séance</label>
                    <select name="emploi_id" id="emploiSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                        <option value="">Sélectionner une séance</option>
                        @if($selectedCours)
                            @foreach($selectedCours->emploisDuTemps as $emploi)
                                <option value="{{ $emploi->id }}" {{ $selectedEmploi && $selectedEmploi->id == $emploi->id ? 'selected' : '' }}>
                                    {{ ucfirst($emploi->jour) }} - {{ substr($emploi->heure_debut, 0, 5) }} à {{ substr($emploi->heure_fin, 0, 5) }} - {{ $emploi->salle->nom ?? 'N/A' }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                Afficher la salle
            </button>
        </form>
    </div>

    @if($selectedCours && $selectedEmploi)
        <!-- Course Info Header -->
        <div class="bg-blue-600 rounded-lg p-6 text-white">
            <h2 class="text-xl font-bold mb-2">Réserver une Place pour le Cours :</h2>
            <div class="space-y-1">
                <p class="text-lg">{{ $selectedCours->nom }} ({{ $selectedCours->code }})</p>
                <p class="text-blue-100">{{ ucfirst($selectedEmploi->jour) }} - {{ substr($selectedEmploi->heure_debut, 0, 5) }} à {{ substr($selectedEmploi->heure_fin, 0, 5) }}</p>
                <p class="text-blue-100">Salle: {{ $selectedEmploi->salle->nom ?? 'N/A' }} - Capacité: {{ $capacite }} places</p>
                <p class="text-blue-100">Enseignant: {{ $selectedCours->enseignant->name ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Classroom Layout -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="max-w-4xl mx-auto">
                <!-- Board -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-4 mb-6 text-white text-center shadow-lg">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-semibold">Tableau et Professeur</span>
                    </div>
                </div>

                <!-- Seating Layout -->
                <div id="seatingLayout" class="space-y-4">
                    @php
                        $placesReservees = $reservations->pluck('numero_place')->toArray();
                        $maReservation = $reservations->where('etudiant_id', auth()->id())->first();
                        $maPlace = $maReservation ? $maReservation->numero_place : null;
                        $rows = ceil($capacite / 4); // 4 places par rangée
                    @endphp
                    
                    @for($row = 1; $row <= $rows; $row++)
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-2">Rang {{ $row }}</p>
                            <div class="grid grid-cols-4 gap-3">
                                @for($place = 1; $place <= 4; $place++)
                                    @php
                                        $placeNum = ($row - 1) * 4 + $place;
                                        $isReserved = in_array($placeNum, $placesReservees);
                                        $isMyPlace = $maPlace == $placeNum;
                                        $isAvailable = $placeNum <= $capacite && !$isReserved;
                                    @endphp
                                    
                                    @if($placeNum <= $capacite)
                                        <div class="relative">
                                            @if($isMyPlace)
                                                <button class="w-full h-20 rounded-lg bg-green-600 text-white border-2 border-green-700 shadow-md cursor-default">
                                                    <div class="flex items-center justify-center space-x-1">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                    <p class="text-xs mt-1 font-semibold">Ma place</p>
                                                </button>
                                            @elseif($isReserved)
                                                <button class="w-full h-20 rounded-lg bg-red-200 border-2 border-red-300 cursor-not-allowed opacity-75" disabled>
                                                    <div class="flex items-center justify-center space-x-1 text-red-600">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </div>
                                                    <p class="text-xs mt-1 text-red-600">Occupée</p>
                                                </button>
                                            @else
                                                <form action="{{ route('etudiant.reservations.store') }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="cours_id" value="{{ $selectedCours->id }}">
                                                    <input type="hidden" name="emploi_du_temps_id" value="{{ $selectedEmploi->id }}">
                                                    <input type="hidden" name="numero_place" value="{{ $placeNum }}">
                                                    <button type="submit" 
                                                            onclick="return confirm('Voulez-vous réserver la place {{ $placeNum }} ?')"
                                                            class="w-full h-20 rounded-lg bg-gray-100 border-2 border-gray-300 hover:border-blue-500 hover:bg-blue-50 transition-all">
                                                        <div class="flex items-center justify-center space-x-1 text-gray-600">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs mt-1 text-gray-600 font-semibold">Place {{ $placeNum }}</p>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        <div></div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Legend -->
                <div class="mt-8 flex items-center justify-center space-x-6 flex-wrap">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-green-600 rounded-lg border-2 border-green-700"></div>
                        <span class="text-sm text-gray-700 font-semibold">Ma place</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gray-100 border-2 border-gray-300 rounded-lg"></div>
                        <span class="text-sm text-gray-700">Disponible</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-red-200 border-2 border-red-300 rounded-lg"></div>
                        <span class="text-sm text-gray-700">Occupée</span>
                    </div>
                </div>

                @if($maReservation)
                    <!-- Annuler la réservation -->
                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-800 mb-3">
                            <strong>Vous avez réservé la place {{ $maPlace }} pour ce cours.</strong>
                        </p>
                        <form action="{{ route('etudiant.reservations.cancel', $maReservation->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler votre réservation ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                                Annuler ma réservation
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Sélectionnez un cours</h3>
                <p class="text-gray-600">Veuillez sélectionner un cours et une séance pour réserver une place.</p>
            </div>
        </div>
    @endif
</div>

<script>
    function updateEmplois() {
        const coursId = document.getElementById('coursSelect').value;
        const emploiSelect = document.getElementById('emploiSelect');
        
        if (coursId) {
            // Recharger la page avec le cours sélectionné
            window.location.href = '{{ route("etudiant.reservations") }}?cours_id=' + coursId;
        } else {
            emploiSelect.innerHTML = '<option value="">Sélectionner une séance</option>';
        }
    }
</script>
@endsection
