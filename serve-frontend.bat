@echo off
cd /d "%~dp0"
php -S localhost:8081 -t frontend/web frontend/web/index.php
