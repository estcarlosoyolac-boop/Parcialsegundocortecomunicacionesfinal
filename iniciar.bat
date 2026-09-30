@echo off
cd /d "%~dp0"
echo Iniciando el despliegue (la primera vez tarda 2-3 minutos)...
docker compose up -d
if errorlevel 1 (
  echo.
  echo ERROR: revise que Docker Desktop este abierto y que el puerto 80 este libre.
  pause
  exit /b 1
)
echo Listo. Abriendo los servicios...
start "" "http://localhost/"
start "" "http://localhost/grafana/"
start "" "http://localhost/jupyter/"
pause
