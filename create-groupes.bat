@echo off
echo ========================================
echo Creation des groupes de test
echo ========================================
echo.

php artisan db:seed --class=GroupeSeeder

echo.
echo Groupes crees avec succes!
echo.
pause

