@echo off
setlocal EnableExtensions
set "PYTHON_EXITCODE=0"
cd /d "%~dp0"
title SIMPLU - Archive uploader Firefox

set "FORCE_CLEANUP=1"
if "%FORCE_CLEANUP%"=="1" (
    echo [CLEANUP] Opresc Firefox si geckodriver cu PowerShell, ca in D:\TEST...
    powershell -NoProfile -NonInteractive -ExecutionPolicy Bypass -Command ^
        "Get-Process -Name firefox,geckodriver -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue; Start-Sleep -Seconds 2; $profileBase = Join-Path $env:APPDATA 'Mozilla\Firefox\Profiles'; if (Test-Path $profileBase) { Get-ChildItem $profileBase -Directory | ForEach-Object { foreach ($lock in 'parent.lock','.parentlock','lock') { $p = Join-Path $_.FullName $lock; if (Test-Path $p) { Remove-Item -LiteralPath $p -Force -ErrorAction SilentlyContinue } } } }"
    timeout /t 1 >nul
) else (
    echo [CLEANUP] Sar peste inchiderea fortata ^(mod start rapid^).
)

set "LOG_FILE=run_simplu_firefox_%date:~-4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%.log"
set "LOG_FILE=%LOG_FILE: =0%"

echo ========================================== > "%LOG_FILE%"
echo LOG START: %DATE% %TIME% >> "%LOG_FILE%"
echo ========================================== >> "%LOG_FILE%"
echo. >> "%LOG_FILE%"

echo ==========================================
echo   SIMPLU - Firefox + Marionette ^(2828^)
echo   Log: %LOG_FILE%
echo ==========================================
echo ========================================== >> "%LOG_FILE%"
echo   SIMPLU Firefox >> "%LOG_FILE%"
echo ========================================== >> "%LOG_FILE%"
echo. >> "%LOG_FILE%"

echo [LOG] Director curent: %CD%
echo [LOG] Director curent: %CD% >> "%LOG_FILE%"

echo [STEP 1] Firefox va fi pornit direct de GeckoDriver cu profilul logat.
echo [STEP 1] Firefox pornit direct de Python/GeckoDriver cu profilul logat. >> "%LOG_FILE%"
goto FX_DONE

echo [STEP 1] Verificare Marionette pe localhost port 2828...
echo [STEP 1] Verificare Marionette 2828... >> "%LOG_FILE%"

powershell -NoProfile -Command "$result = Test-NetConnection -ComputerName localhost -Port 2828 -InformationLevel Quiet -WarningAction SilentlyContinue; if ($result) { exit 0 } else { exit 1 }" >nul 2>&1

if errorlevel 1 goto FX_OFF

:FX_ON
    echo [INFO] Port 2828 este deja deschis ^(Firefox cu Marionette probabil ruleaza^).
    echo [INFO] Port 2828 activ. >> "%LOG_FILE%"
    goto FX_DONE

:FX_OFF
    echo [INFO] Port 2828 nu raspunde - pornesc Firefox cu start_firefox_archive_debug.bat
    echo [LOG] CALL start_firefox_archive_debug.bat >> "%LOG_FILE%"
    if not exist "%~dp0start_firefox_archive_debug.bat" (
        echo [EROARE] Lipseste: "%~dp0start_firefox_archive_debug.bat"
        goto END_FAIL
    )
    call "%~dp0start_firefox_archive_debug.bat"
    if errorlevel 1 (
        echo [EROARE] start_firefox_archive_debug.bat a esuat.
        goto END_FAIL
    )

    echo [LOG] Astept port 2828 ^(max ~60s, la 2s^)...
    echo [LOG] Astept 2828 max 60s >> "%LOG_FILE%"
    for /L %%i in (1,1,30) do (
        powershell -NoProfile -Command "$result = Test-NetConnection -ComputerName localhost -Port 2828 -InformationLevel Quiet -WarningAction SilentlyContinue; if ($result) { exit 0 } else { exit 1 }" >nul 2>&1
        if not errorlevel 1 (
            echo [LOG] Port 2828 ACTIV dupa %%i incercari.
            echo [LOG] Port 2828 OK dupa %%i >> "%LOG_FILE%"
            goto FX2828_OK
        )
        echo [LOG] 2828 inca inactiv - incerc %%i/30, pauza 2s...
        timeout /t 2 >nul
    )
    echo [EROARE] Port 2828 nu s-a deschis in ~60s.
    echo Asigura-te ca Firefox e instalat si ca start_firefox_archive_debug.bat porneste corect.
    echo [EROARE] Timeout 2828 >> "%LOG_FILE%"
    goto END_FAIL
:FX2828_OK

:FX_DONE

echo. >> "%LOG_FILE%"
echo [STEP 2] Pornire SIMPLU_FIREFOX_ArchiveUpload.py... >> "%LOG_FILE%"
echo [STEP 2] Pornire SIMPLU_FIREFOX_ArchiveUpload.py...

set PYTHONIOENCODING=utf-8
set PYTHONUTF8=1

set "PYEXE="
where python >nul 2>&1
if not errorlevel 1 set "PYEXE=python"
if not defined PYEXE (
    where py >nul 2>&1
    if not errorlevel 1 set "PYEXE=py -3"
)
if not defined PYEXE (
    echo [EROARE] Nu gasesc Python in PATH ^(python sau py^).
    goto END_FAIL
)

echo [LOG] Folosesc: %PYEXE%
echo [LOG] Folosesc: %PYEXE% >> "%LOG_FILE%"
%PYEXE% -u "SIMPLU_FIREFOX_ArchiveUpload.py"
set "PYTHON_EXITCODE=%errorlevel%"
goto AFTER_PY

:END_FAIL
set "PYTHON_EXITCODE=1"

:AFTER_PY

echo. >> "%LOG_FILE%"
echo [LOG] Cod iesire Python: %PYTHON_EXITCODE% >> "%LOG_FILE%"
echo [LOG] Cod iesire Python: %PYTHON_EXITCODE%
echo.
echo Script finalizat.
echo ========================================== >> "%LOG_FILE%"
echo LOG END: %DATE% %TIME% >> "%LOG_FILE%"
echo ========================================== >> "%LOG_FILE%"
echo.
echo Log salvat in: %LOG_FILE%
echo.
echo Apasa o tasta pentru a inchide...
pause >nul
endlocal
exit /b %PYTHON_EXITCODE%
