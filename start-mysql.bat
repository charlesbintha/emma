@echo off
echo ========================================
echo Demarrage du serveur MySQL (WAMP)
echo ========================================
echo.

net start wampmysqld64

if %ERRORLEVEL% EQU 0 (
    echo.
    echo [OK] MySQL a demarre avec succes !
    echo.
    echo Vous pouvez maintenant :
    echo 1. Tester l'app mobile
    echo 2. Verifier la connexion avec: php artisan migrate:status
    echo.
) else (
    echo.
    echo [ERREUR] Impossible de demarrer MySQL
    echo.
    echo Solutions possibles :
    echo 1. Lancez ce fichier en tant qu'Administrateur (clic droit ^> Executer en tant qu'administrateur)
    echo 2. Ou demarrez WAMP manuellement
    echo 3. Ou demarrez depuis les services Windows (services.msc)
    echo.
)

pause
