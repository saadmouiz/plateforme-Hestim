@echo off
echo ========================================
echo Configuration de la base de donnees
echo ========================================
echo.

echo Etape 1: Execution des migrations...
call php artisan migrate --force

echo.
echo Etape 2: Creation des utilisateurs de test...
call php artisan db:seed --class=AdminUserSeeder

echo.
echo ========================================
echo Configuration terminee!
echo ========================================
echo.
echo Comptes crees:
echo - Admin: admin@hestim.ma / password
echo - Enseignant: enseignant@hestim.ma / password
echo - Etudiant: etudiant@hestim.ma / password
echo.
pause

