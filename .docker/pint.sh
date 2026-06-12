#!/bin/bash

# Directorio raíz del proyecto en tu máquina
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

# Traducimos las rutas locales a las rutas del contenedor (/app)
ARGS=()
for arg in "$@"; do
    if [[ "$arg" == "$PROJECT_ROOT"* ]]; then
        ARGS+=("${arg/$PROJECT_ROOT/\/app}")
    else
        ARGS+=("$arg")
    fi
done

# Ejecutamos Pint dentro del contenedor.
# Usamos -T para evitar errores de TTY en la ejecución en segundo plano del IDE.
docker compose exec -T app vendor/bin/pint "${ARGS[@]}"
