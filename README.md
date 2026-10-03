# Parcial 2 práctico — Comunicaciones

Despliegue multi-contenedor (nginx, Joomla, PostgreSQL, Jupyter, Grafana) con Docker Compose.

Ingeniería Mecatrónica · UMNG
Docente: Ing. Andrés Julián Moreno M.Sc.
Integrantes: Carlos Oyola · Samuel López · Angela Sánchez

![Arquitectura](docs/arquitectura.svg)

## Requisitos
- Docker y Docker Compose v2
- Puerto 80 libre

## Arranque sin comandos (Windows)

1. Abrir **Docker Desktop** y esperar a que diga *Engine running*.
2. En GitHub: **Code → Download ZIP** y descomprimir.
3. Entrar a la carpeta descomprimida y hacer **doble clic en `iniciar.bat`**.
   Levanta los 5 contenedores y abre Joomla, Grafana y Jupyter en el navegador.
4. Para apagar: doble clic en `detener.bat`.

## Arranque con comandos

```bash
git clone <URL_DEL_REPOSITORIO>
cd <CARPETA_DEL_REPOSITORIO>
cp .env.example .env
docker compose up -d
```

El primer arranque tarda 2-3 minutos: Joomla se instala automáticamente contra PostgreSQL. `docker compose up -d` termina cuando los 5 servicios están *healthy*:

```bash
docker compose ps
```

## Accesos (todo por el puerto 80, a través de nginx)

| Servicio | URL | Credenciales |
|---|---|---|
| Joomla | http://localhost/ | — |
| Joomla admin | http://localhost/administrator/ | admin / AdminJoomla2026Comm |
| Grafana | http://localhost/grafana/ | abre directo el dashboard (admin / GrafanaAdmin2026 solo para editar) |
| Jupyter | http://localhost/jupyter/ | acceso directo, sin token |

- Grafana abre directamente el dashboard **Joomla - Trafico** sin iniciar sesión (datasource y dashboard aprovisionados automáticamente).
- Jupyter abre directamente `analisis_datos.ipynb` → **Run → Run All Cells**.

> Para ver datos en Grafana: navegar algunas páginas de http://localhost/ (incluida /no-existe)
> o ejecutar el notebook de Jupyter, que genera tráfico hacia Joomla.

## Estructura

```
├── docker-compose.yml
├── .env.example
├── joomla/                                # portada automática (texto + imágenes) creada al arrancar
├── nginx/default.conf                      # reverse proxy + log TSV de Joomla
├── database/init/01-monitoreo.sql          # file_fdw: el log de nginx como tabla
├── jupyter/Dockerfile
├── jupyter/notebooks/analisis_datos.ipynb  # cuaderno precargado
├── grafana/provisioning/                   # datasource + dashboard
├── docs/arquitectura.svg
├── README.md
└── INFORME.md
```

El informe técnico (topología y análisis OSI) está en [INFORME.md](INFORME.md).
