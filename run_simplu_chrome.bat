@echo off
setlocal EnableExtensions
set "PYTHON_EXITCODE=0"
REM Asigura ca lucram din folderul in care este acest fisier .bat
cd /d "%~dp0"
title SIMPLU - Archive uploader

REM Ca in varianta buna: eliberam profilul Chrome inainte de pornirea cu remote debugging.
set "FORCE_CLEANUP=1"
if "%FORCE_CLEANUP%"=="1" (
    echo [CLEANUP] Opresc Chrome si ChromeDriver cu PowerShell...
    powershell -NoProfile -NonInteractive -ExecutionPolicy Bypass -Command "Stop-Process -Name chrome,chromedriver -Force -ErrorAction SilentlyContinue; Start-Sleep -Seconds 2" >nul 2>&1
) else (
    echo [CLEANUP] Sar peste inchiderea fortata ^(mod start rapid^).
)

REM TOT ce urmeaza este scris DOAR cu caractere ASCII ca sa nu apara probleme in CMD

REM -----------------------------------------------------
REM 1) Creeaza un fisier de log cu data/ora in nume
REM -----------------------------------------------------
set "LOG_FILE=run_simplu_%date:~-4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%.log"
set "LOG_FILE=%LOG_FILE: =0%"

echo ========================================== > "%LOG_FILE%"
echo LOG START: %DATE% %TIME% >> "%LOG_FILE%"
echo ========================================== >> "%LOG_FILE%"
echo. >> "%LOG_FILE%"

echo ==========================================
echo   PORNIRE SCRIPT AUTOMATIZARE
echo   Log: %LOG_FILE%
echo ==========================================
echo ========================================== >> "%LOG_FILE%"
echo   PORNIRE SCRIPT AUTOMATIZARE >> "%LOG_FILE%"
echo ========================================== >> "%LOG_FILE%"
echo. >> "%LOG_FILE%"

echo [LOG] Director curent: %CD%
echo [LOG] Director curent: %CD% >> "%LOG_FILE%"
echo [LOG] Data/Ora: %DATE% %TIME%
echo [LOG] Data/Ora: %DATE% %TIME% >> "%LOG_FILE%"
echo. >> "%LOG_FILE%"

echo [STEP 1] Sar peste pornirea Chrome din batch.
echo [STEP 1] Python va porni Chrome debug si apoi uploadul. >> "%LOG_FILE%"
goto CHROME_DONE

REM -----------------------------------------------------
REM 2) Verifica daca portul 9222 (Chrome debug) raspunde
REM -----------------------------------------------------
echo [STEP 1] Verificare stare Chrome Debug (port 9222)...
echo [STEP 1] Verificare stare Chrome Debug (port 9222)... >> "%LOG_FILE%"

powershell -NoProfile -Command "try { $r = Invoke-WebRequest -UseBasicParsing -Uri 'http://127.0.0.1:9222/json/version' -TimeoutSec 3; if ($r.StatusCode -eq 200) { exit 0 } else { exit 1 } } catch { exit 1 }" >nul 2>&1

if errorlevel 1 goto CHROME_OFF

:CHROME_ON
    echo [INFO] Chrome Debug este DEJA PORNIT si asculta pe portul 9222.
    echo [INFO] Chrome Debug este DEJA PORNIT si asculta pe portul 9222. >> "%LOG_FILE%"
    echo [LOG] Verificare procese Chrome... >> "%LOG_FILE%"
    powershell -NoProfile -Command "Get-Process -Name chrome -ErrorAction SilentlyContinue | Select-Object Id,ProcessName,StartTime,Path | Format-Table -AutoSize" >> "%LOG_FILE%" 2>&1
    echo Se porneste direct scriptul Python...
    echo Se porneste direct scriptul Python... >> "%LOG_FILE%"
    goto CHROME_DONE

:CHROME_OFF
    echo [INFO] Chrome Debug NU este pornit.
    echo [INFO] Chrome Debug NU este pornit. >> "%LOG_FILE%"
    echo [LOG] Lansez start_chrome_debug.bat in aceeasi consola (CALL, fara al doilea CMD).
    echo [LOG] Lansez start_chrome_debug.bat in aceeasi consola. >> "%LOG_FILE%"
    if not exist "%~dp0start_chrome_debug.bat" (
        echo [EROARE] Lipseste fisierul: "%~dp0start_chrome_debug.bat"
        echo [EROARE] Lipseste start_chrome_debug.bat >> "%LOG_FILE%"
        goto END_FAIL
    )
    call "%~dp0start_chrome_debug.bat"
    if errorlevel 1 (
        echo [EROARE] start_chrome_debug.bat a returnat cod de eroare.
        goto END_FAIL
    )

    echo [LOG] Astept ca Chrome sa deschida portul 9222 (max ~60s, verificare la 2s)...
    echo [LOG] Astept port 9222 max 60s >> "%LOG_FILE%"
    for /L %%i in (1,1,30) do (
        powershell -NoProfile -Command "try { $r = Invoke-WebRequest -UseBasicParsing -Uri 'http://127.0.0.1:9222/json/version' -TimeoutSec 3; if ($r.StatusCode -eq 200) { exit 0 } else { exit 1 } } catch { exit 1 }" >nul 2>&1
        if not errorlevel 1 (
            echo [LOG] Port 9222 ACTIV dupa %%i incercari (~%%i x 2s).
            echo [LOG] Port 9222 ACTIV dupa %%i incercari >> "%LOG_FILE%"
            goto CHROME9222_OK
        )
        echo [LOG] 9222 inca inactiv — incerc %%i/30, pauza 2s...
        timeout /t 2 >nul
    )
    echo [WARNING] Port 9222 nu s-a deschis in ~60s.
    echo [WARNING] Continui cu Python; scriptul Python va incerca sa porneasca Chrome debug singur.
    echo [WARNING] Timeout port 9222 - continui cu Python fallback >> "%LOG_FILE%"
    goto CHROME_DONE
:CHROME9222_OK

:CHROME_DONE

REM -----------------------------------------------------
REM 3) Porneste scriptul Python si logheaza output-ul
REM -----------------------------------------------------
echo. >> "%LOG_FILE%"
echo [STEP 2] Pornire script Python... >> "%LOG_FILE%"
echo [STEP 2] Pornire script Python...
echo ==========================================
echo   PORNIRE SCRIPT AUTOMATIZARE
echo ==========================================
echo.

REM Fortam UTF-8 pentru Python (diacriticele din mesaje altfel dau UnicodeEncodeError)
set PYTHONIOENCODING=utf-8
set PYTHONUTF8=1

echo [LOG] Pornesc Python DIRECT in consola (fara wrapper PowerShell).
echo [LOG] Pornesc Python DIRECT in consola (fara wrapper PowerShell). >> "%LOG_FILE%"
echo [INFO] Dupa pornire trebuie sa vezi imediat linii [DEBUG]/[INFO].
echo [INFO] Dupa pornire trebuie sa vezi imediat linii [DEBUG]/[INFO]. >> "%LOG_FILE%"

set "PYEXE="
where python >nul 2>&1
if not errorlevel 1 set "PYEXE=python"
if not defined PYEXE (
    where py >nul 2>&1
    if not errorlevel 1 set "PYEXE=py -3"
)
if not defined PYEXE (
    echo [EROARE] Nu gasesc Python in PATH ^(python sau py^).
    echo Instaleaza Python 3 sau adauga-l in PATH, apoi reincearca.
    goto END_FAIL
)

echo [LOG] Folosesc: %PYEXE%
echo [LOG] Folosesc: %PYEXE% >> "%LOG_FILE%"
%PYEXE% -u "+FINAL 3 - asta pornesti SIMPLU.py"
set "PYTHON_EXITCODE=%errorlevel%"
goto AFTER_PY

:END_FAIL
set "PYTHON_EXITCODE=1"

:AFTER_PY

echo. >> "%LOG_FILE%"
echo [LOG] Script Python s-a incheiat cu codul: %PYTHON_EXITCODE% >> "%LOG_FILE%"
echo [LOG] Script Python s-a incheiat cu codul: %PYTHON_EXITCODE%
echo.
echo Script finalizat.
echo ========================================== >> "%LOG_FILE%"
echo LOG END: %DATE% %TIME% >> "%LOG_FILE%"
echo ========================================== >> "%LOG_FILE%"
echo.
echo Log-ul a fost salvat in: %LOG_FILE%
echo.
echo Apasa o tasta pentru a inchide aceasta fereastra...
pause >nul
endlocal
exit /b %PYTHON_EXITCODE%
