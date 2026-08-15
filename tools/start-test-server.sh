#!/usr/bin/env bash

set -euo pipefail

root_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
host="${1:-127.0.0.1}"
port="${2:-8011}"
document_root="${3:-$root_dir}"

if ! command -v php >/dev/null 2>&1; then
    echo "PHP was not found in PATH." >&2
    exit 1
fi

if [[ ! "$port" =~ ^[0-9]{1,5}$ ]] || ((port < 1 || port > 65535)); then
    echo "Port must be an integer between 1 and 65535." >&2
    exit 1
fi

if [[ ! -d "$document_root" ]]; then
    echo "Document root does not exist: $document_root" >&2
    exit 1
fi

echo "Starting DZCP test server at http://${host}:${port}/"
echo "Document root: ${document_root}"
exec php -S "${host}:${port}" -t "$document_root"
