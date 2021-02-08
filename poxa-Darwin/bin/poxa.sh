#!/bin/sh
set -e

export POXA_APP_ID=$2
export POXA_APP_KEY=$3
export POXA_SECRET=$4
export POXA_PORT=$5
export POXA_SSL=$6
export POXA_SSL_CERTFILE=$7
export POXA_SSL_KEYFILE=$8

SELF=$(readlink "$0" || true)
if [ -z "$SELF" ]; then SELF="$0"; fi
RELEASE_ROOT="$(cd "$(dirname "$SELF")/.." && pwd -P)"
export RELEASE_ROOT
export RELEASE_NAME="${RELEASE_NAME:-"poxa"}"
export RELEASE_VSN="${RELEASE_VSN:-"$(cut -d' ' -f2 "$RELEASE_ROOT/releases/start_erl.data")"}"
export RELEASE_COMMAND="$1"
export RELEASE_MODE="${RELEASE_MODE:-"embedded"}"

REL_VSN_DIR="$RELEASE_ROOT/releases/$RELEASE_VSN"
. "$REL_VSN_DIR/env.sh"

export RELEASE_COOKIE="${RELEASE_COOKIE:-"$(cat "$RELEASE_ROOT/releases/COOKIE")"}"
export RELEASE_NODE="${RELEASE_NODE:-"$RELEASE_NAME"}"
export RELEASE_TMP="${RELEASE_TMP:-"$RELEASE_ROOT/tmp"}"
export RELEASE_VM_ARGS="${RELEASE_VM_ARGS:-"$REL_VSN_DIR/vm.args"}"
export RELEASE_DISTRIBUTION="${RELEASE_DISTRIBUTION:-"sname"}"
export RELEASE_BOOT_SCRIPT="${RELEASE_BOOT_SCRIPT:-"start"}"
export RELEASE_BOOT_SCRIPT_CLEAN="${RELEASE_BOOT_SCRIPT_CLEAN:-"start_clean"}"

rand () {
  od -t xS -N 2 -A n /dev/urandom | tr -d " \n"
}

rpc () {
  exec "$REL_VSN_DIR/elixir" \
       --hidden --cookie "$RELEASE_COOKIE" \
       --$RELEASE_DISTRIBUTION "rpc-$(rand)-$RELEASE_NODE" \
       --boot "$REL_VSN_DIR/$RELEASE_BOOT_SCRIPT_CLEAN" \
       --boot-var RELEASE_LIB "$RELEASE_ROOT/lib" \
       --rpc-eval "$RELEASE_NODE" "$1"
}

start () {
  export_release_sys_config
  REL_EXEC="$1"
  shift
  exec "$REL_VSN_DIR/$REL_EXEC" \
       --cookie "$RELEASE_COOKIE" \
       --$RELEASE_DISTRIBUTION "$RELEASE_NODE" \
       --erl "-mode $RELEASE_MODE" \
       --erl-config "$RELEASE_SYS_CONFIG" \
       --boot "$REL_VSN_DIR/$RELEASE_BOOT_SCRIPT" \
       --boot-var RELEASE_LIB "$RELEASE_ROOT/lib" \
       --vm-args "$RELEASE_VM_ARGS" "$@"
}

export_release_sys_config () {
  if grep -q "RUNTIME_CONFIG=true" "$REL_VSN_DIR/sys.config"; then
    RELEASE_SYS_CONFIG="$RELEASE_TMP/$RELEASE_NAME-$RELEASE_VSN-$(date +%Y%m%d%H%M%S)-$(rand).runtime"

    (mkdir -p "$RELEASE_TMP" && cp "$REL_VSN_DIR/sys.config" "$RELEASE_SYS_CONFIG.config") || (
      echo "ERROR: Cannot start release because it could not write $RELEASE_SYS_CONFIG.config" >&2
      exit 1
    )
  else
    RELEASE_SYS_CONFIG="$REL_VSN_DIR/sys"
  fi

  export RELEASE_SYS_CONFIG
}

case $1 in
  start)
    start "elixir" --no-halt
    ;;

  start_iex)
    start "iex" --werl
    ;;

  daemon)
    start "elixir" --no-halt --pipe-to "${RELEASE_TMP}/pipe" "${RELEASE_TMP}/log"
    ;;

  daemon_iex)
    start "iex" --pipe-to "${RELEASE_TMP}/pipe" "${RELEASE_TMP}/log"
    ;;

  eval)
    if [ -z "$2" ]; then
      echo "ERROR: EVAL expects an expression as argument" >&2
      exit 1
    fi

    export_release_sys_config
    exec "$REL_VSN_DIR/elixir" \
       --cookie "$RELEASE_COOKIE" \
       --erl-config "$RELEASE_SYS_CONFIG" \
       --boot "$REL_VSN_DIR/$RELEASE_BOOT_SCRIPT_CLEAN" \
       --boot-var RELEASE_LIB "$RELEASE_ROOT/lib" \
       --vm-args "$RELEASE_VM_ARGS" --eval "$2"
    ;;

  remote)
    exec "$REL_VSN_DIR/iex" \
         --werl --hidden --cookie "$RELEASE_COOKIE" \
         --$RELEASE_DISTRIBUTION "rem-$(rand)-$RELEASE_NODE" \
         --boot "$REL_VSN_DIR/$RELEASE_BOOT_SCRIPT_CLEAN" \
         --boot-var RELEASE_LIB "$RELEASE_ROOT/lib" \
         --remsh "$RELEASE_NODE"
    ;;

  rpc)
    if [ -z "$2" ]; then
      echo "ERROR: RPC expects an expression as argument" >&2
      exit 1
    fi
    rpc "$2"
    ;;

  restart|stop)
    rpc "System.$1"
    ;;

  pid)
    rpc "IO.puts System.pid"
    ;;

  version)
    echo "$RELEASE_NAME $RELEASE_VSN"
    ;;

  *)
    echo "Usage: $(basename "$0") COMMAND [ARGS]

The known commands are:

    start          Starts the system
    start_iex      Starts the system with IEx attached
    daemon         Starts the system as a daemon
    daemon_iex     Starts the system as a daemon with IEx attached
    eval \"EXPR\"    Executes the given expression on a new, non-booted system
    rpc \"EXPR\"     Executes the given expression remotely on the running system
    remote         Connects to the running system via a remote shell
    restart        Restarts the running system via a remote command
    stop           Stops the running system via a remote command
    pid            Prints the OS PID of the running system via a remote command
    version        Prints the release name and version to be booted
" >&2

    if [ -n "$1" ]; then
      echo "ERROR: Unknown command $1" >&2
      exit 1
    fi
    ;;
esac
