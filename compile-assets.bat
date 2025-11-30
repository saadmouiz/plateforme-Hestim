@echo off
echo Installation des dependances npm...
call npm install

echo.
echo Compilation des assets Vite...
call npm run build

echo.
echo Compilation terminee! Vous pouvez maintenant utiliser @vite dans vos vues.
pause

