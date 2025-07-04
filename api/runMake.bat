@echo off
echo ===================================================
echo Laravel 生成助手
echo ===================================================
echo [1] 创建控制器
echo [2] 创建模型
echo [3] 创建控制器和模型
echo [Q] 退出
echo ===================================================
set /p choice="请输入你的选择: "

if "%choice%"=="1" (
    set /p controller="请输入控制器名称: "
    echo 正在创建控制器 %controller%...
    echo.
    php artisan make:controller %controller%
    echo.
    goto end
)

if "%choice%"=="2" (
    set /p model="请输入模型名称: "
    set /p create_migration="是否需要创建迁移文件? (y/n): "
    if /i "%create_migration%"=="y" (
        echo 正在创建模型 %model% 并生成迁移文件...
        echo.
        php artisan make:model %model% -m
    ) else (
        echo 正在创建模型 %model%...
        echo.
        php artisan make:model %model%
    )
    echo.
    goto end
)

if "%choice%"=="3" (
    set /p controller="请输入控制器名称: "
    set /p model="请输入模型名称: "
    set /p create_migration="是否需要创建迁移文件? (y/n): "
    echo 正在创建控制器 %controller%...
    echo.
    php artisan make:controller %controller%
    if /i "%create_migration%"=="y" (
        echo 正在创建模型 %model% 并生成迁移文件...
        echo.
        php artisan make:model %model% -m
    ) else (
        echo 正在创建模型 %model%...
        echo.
        php artisan make:model %model%
    )
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
