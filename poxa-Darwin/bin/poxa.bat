@echo off
setlocal enabledelayedexpansion

pushd .
cd "%~dp0\.."
set RELEASE_ROOT=%cd%
popd

if not defined RELEASE_NAME (set RELEASE_NAME=poxa)
if not defined RELEASE_VSN (for /f "tokens=1,2" %%K in ('type "!RELEASE_ROOT!\releases\start_erl.data"') do (set ERTS_VSN=%%K) && (set RELEASE_VSN=%%L))
if not defined RELEASE_MODE (set RELEASE_MODE=embedded)
set RELEASE_COMMAND=%~1
set REL_VSN_DIR=!RELEASE_ROOT!\releases\!RELEASE_VSN!
call "!REL_VSN_DIR!\env.bat"

if not defined RELEASE_COOKIE (set /p RELEASE_COOKIE=<!RELEASE_ROOT!\releases\COOKIE)
if not defined RELEASE_NODE (set RELEASE_NODE=!RELEASE_NAME!)
if not defined RELEASE_TMP (set RELEASE_TMP=!RELEASE_ROOT!\tmp)
if not defined RELEASE_VM_ARGS (set RELEASE_VM_ARGS=!REL_VSN_DIR!\vm.args)
if not defined RELEASE_DISTRIBUTION (set RELEASE_DISTRIBUTION=sname)
if not defined RELEASE_BOOT_SCRIPT (set RELEASE_BOOT_SCRIPT=start)
if not defined RELEASE_BOOT_SCRIPT_CLEAN (set RELEASE_BOOT_SCRIPT_CLEAN=start_clean)
set RELEASE_SYS_CONFIG=!REL_VSN_DIR!\sys

if "%~1" == "start" (set "REL_EXEC=elixir" && set "REL_EXTRA=--no-halt" && set "REL_GOTO=start")
if "%~1" == "start_iex" (set "REL_EXEC=iex" && set "REL_EXTRA=--werl" && set "REL_GOTO=start")
if "%~1" == "install" (set "REL_GOTO=install")
if "%~1" == "eval" (
  if "%~2" == "" (
    echo ERROR: EVAL expects an expression as argument
    goto end
  )
  set "REL_GOTO=eval"
)

if not "!REL_GOTO!" == "" (
  findstr "RUNTIME_CONFIG=true" "!RELEASE_SYS_CONFIG!.config" >nul 2>&1 && (
    for /f "skip=1" %%X in ('wmic os get localdatetime') do if not defined TIMESTAMP set TIMESTAMP=%%X
    set RELEASE_SYS_CONFIG=!RELEASE_TMP!\!RELEASE_NAME!-!RELEASE_VSN!-!TIMESTAMP:~0,11!-!RANDOM!.runtime
    mkdir "!RELEASE_TMP!" >nul 2>&1
    copy /y "!REL_VSN_DIR!\sys.config" "!RELEASE_SYS_CONFIG!.config" >nul || (
      echo Cannot start release because it could not write to "!RELEASE_SYS_CONFIG!.config"
      goto end
    )
  )

  goto !REL_GOTO!
)

if "%~1" == "remote" (goto remote)
if "%~1" == "version" (goto version)
if "%~1" == "stop" (set "REL_RPC=System.stop()" && goto rpc)
if "%~1" == "restart" (set "REL_RPC=System.stop()" && goto rpc)
if "%~1" == "pid" (set "REL_RPC=IO.puts(System.pid())" && goto rpc)
if "%~1" == "rpc" (
  if "%~2" == "" (
    echo ERROR: RPC expects an expression as argument
    goto end
  )
  set "REL_RPC=%~2"
  goto rpc
)

echo Usage: %~nx0 COMMAND [ARGS]
echo.
echo The known commands are:
echo.
echo    start        Starts the system
echo    start_iex    Starts the system with IEx attached
echo    install      Installs this system as a Windows service
echo    eval "EXPR"  Executes the given expression on a new, non-booted system
echo    rpc "EXPR"   Executes the given expression remotely on the running system
echo    remote       Connects to the running system via a remote shell
echo    restart      Restarts the running system via a remote command
echo    stop         Stops the running system via a remote command
echo    pid          Prints the OS PID of the running system via a remote command
echo    version      Prints the release name and version to be booted
echo.
if not "%~1" == "" (echo ERROR: Unknown command %~1)
goto end

:start
"!REL_VSN_DIR!\!REL_EXEC!.bat" !REL_EXTRA! ^
  --cookie "!RELEASE_COOKIE!" ^
  --!RELEASE_DISTRIBUTION! "!RELEASE_NODE!" ^
  --erl "-mode !RELEASE_MODE!" ^
  --erl-config "!RELEASE_SYS_CONFIG!" ^
  --boot "!REL_VSN_DIR!\!RELEASE_BOOT_SCRIPT!" ^
  --boot-var RELEASE_LIB "!RELEASE_ROOT!\lib" ^
  --vm-args "!RELEASE_VM_ARGS!"
goto end

:eval
"!REL_VSN_DIR!\elixir.bat" ^
  --eval "%~2" ^
  --cookie "!RELEASE_COOKIE!" ^
  --erl-config "!RELEASE_SYS_CONFIG!" ^
  --boot "!REL_VSN_DIR!\!RELEASE_BOOT_SCRIPT_CLEAN!" ^
  --boot-var RELEASE_LIB "!RELEASE_ROOT!\lib" ^
  --vm-args "!RELEASE_VM_ARGS!"
goto end

:remote
"!REL_VSN_DIR!\iex.bat" ^
  --werl --hidden --cookie "!RELEASE_COOKIE!" ^
  --!RELEASE_DISTRIBUTION! "rem-!RANDOM!-!RELEASE_NODE!" ^
  --boot "!REL_VSN_DIR!\!RELEASE_BOOT_SCRIPT_CLEAN!" ^
  --boot-var RELEASE_LIB "!RELEASE_ROOT!\lib" ^
  --remsh "!RELEASE_NODE!"
goto end

:rpc
"!REL_VSN_DIR!\elixir.bat" ^
  --hidden --cookie "!RELEASE_COOKIE!" ^
  --!RELEASE_DISTRIBUTION! "rpc-!RANDOM!-!RELEASE_NODE!" ^
  --boot "!REL_VSN_DIR!\!RELEASE_BOOT_SCRIPT_CLEAN!" ^
  --boot-var RELEASE_LIB "!RELEASE_ROOT!\lib" ^
  --rpc-eval "!RELEASE_NODE!" "!REL_RPC!"
goto end

:version
echo !RELEASE_NAME! !RELEASE_VSN!
goto end

:install
if exist !RELEASE_ROOT!\erts-!ERTS_VSN! (
  set ERLSRV=!RELEASE_ROOT!\erts-!ERTS_VSN!\bin\erlsrv.exe
) else (
  set ERLSRV=erlsrv.exe
)

!ERLSRV! add !RELEASE_NAME!_!RELEASE_NAME! ^
  -!RELEASE_DISTRIBUTION! "!RELEASE_NODE!" ^
  -env RELEASE_ROOT=!RELEASE_ROOT! -env RELEASE_NAME=!RELEASE_NAME! -env RELEASE_VSN=!RELEASE_VSN! -env RELEASE_COOKIE=!RELEASE_COOKIE! -env RELEASE_NODE=!RELEASE_NODE! -env RELEASE_VM_ARGS=!RELEASE_VM_ARGS! -env RELEASE_TMP=!RELEASE_TMP! -env RELEASE_SYS_CONFIG=!RELEASE_SYS_CONFIG! ^
  -args "-setcookie !RELEASE_COOKIE! -config !RELEASE_SYS_CONFIG! -mode !RELEASE_MODE! -boot !REL_VSN_DIR!\start -boot_var RELEASE_LIB !RELEASE_ROOT!\lib -args_file !REL_VSN_DIR!\vm.args"

if %ERRORLEVEL% EQU 0 (
  echo Service installed but not started. From now on, it must be started and stopped by erlsrv:
  echo.
  echo     !ERLSRV! start !RELEASE_NAME!_!RELEASE_NAME!
  echo     !ERLSRV! stop !RELEASE_NAME!_!RELEASE_NAME!
  echo     !ERLSRV! remove !RELEASE_NAME!_!RELEASE_NAME!
  echo     !ERLSRV! list
  echo     !ERLSRV! help
  echo.
)
goto end

:end
endlocal
