#!/usr/bin/env bash

set -euo pipefail

root_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
sqlite_mode=false
reset_database=false
positionals=()

for argument in "$@"; do
    case "$argument" in
        --sqlite) sqlite_mode=true ;;
        --reset) reset_database=true ;;
        *) positionals+=("$argument") ;;
    esac
done

host="${positionals[0]:-127.0.0.1}"
port="${positionals[1]:-8011}"
document_root="${positionals[2]:-$root_dir}"

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

if [[ "$sqlite_mode" == true ]]; then
    export DZCP_DATABASE_DRIVER=sqlite
    export DZCP_SQLITE_PATH="${DZCP_SQLITE_PATH:-$root_dir/var/test/dzcp.sqlite}"
    if [[ "$reset_database" == true ]]; then
        php "$root_dir/tools/setup-test-database.php" --reset
    else
        php "$root_dir/tools/setup-test-database.php"
    fi
fi

echo "Starting DZCP test server at http://${host}:${port}/"
echo "Document root: ${document_root}"
[[ "$sqlite_mode" == true ]] && echo "Database: SQLite (${DZCP_SQLITE_PATH})"
exec php -S "${host}:${port}" -t "$document_root"
