@echo off
echo ===================================================
echo Laravel 迁移助手
echo ===================================================
echo [1] 运行迁移
echo [2] 回滚上一次迁移
echo [3] 回滚最近 N 次迁移
echo [4] 重置所有迁移
echo [5] 刷新所有迁移
echo [6] 刷新所有迁移并填充种子数据
echo [7] 刷新最近 N 次迁移
echo [Q] 退出
echo ===================================================
set /p choice="请输入你的选择: "

if "%choice%"=="1" (
    echo 正在运行迁移...
    echo.
    php artisan migrate
    echo.
    goto end
)

if "%choice%"=="2" (
    echo 正在回滚上一次迁移...
    echo.
    php artisan migrate:rollback
    echo.
    goto end
)

if "%choice%"=="3" (
    set /p steps="请输入要回滚的步数: "
    echo 正在回滚最近 %steps% 次迁移...
    echo.
    php artisan migrate:rollback --step=%steps%
    echo.
    goto end
)

if "%choice%"=="4" (
    echo 正在重置所有迁移...
    echo.
    php artisan migrate:reset
    echo.
    goto end
)

if "%choice%"=="5" (
    echo 正在刷新所有迁移...
    echo.
    php artisan migrate:refresh
    echo.
    goto end
)

if "%choice%"=="6" (
    echo 正在刷新所有迁移并填充种子数据...
    echo.
    php artisan migrate:refresh --seed
    echo.
    goto end
)

if "%choice%"=="7" (
    set /p steps="请输入要刷新的步数: "
    echo 正在刷新最近 %steps% 次迁移...
    echo.
    php artisan migrate:refresh --step=%steps%
    echo.
    goto end
)

if "%choice%"=="Q" (
    goto end
)

echo 无效选择，请重试。
pause
goto :eof

:end
echo 完成。
pause
exit
