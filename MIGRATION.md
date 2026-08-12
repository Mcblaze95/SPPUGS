# Yii2 Advanced conversion

The original Yii2 Basic application remains at:

`D:\ADMIN\Downloads\YII2 migrate`

This converted application uses the standard Yii2 Advanced split:

- `backend/`: the Supply and Property Tracking administration application
- `frontend/`: a separate public-facing application shell
- `common/models/`: shared database and authentication models
- `common/data/system.sqlite`: migrated SQLite database
- `console/`: shared command-line application

## Run the backend

```powershell
cd "D:\ADMIN\Downloads\YII2 advanced"
php yii serve
```

Open <http://localhost:8080>.

## Run the frontend

In a second terminal:

```powershell
cd "D:\ADMIN\Downloads\YII2 advanced"
php -S localhost:8081 -t frontend/web frontend/web/index.php
```

Open <http://localhost:8081>.

The `serve-backend.bat` and `serve-frontend.bat` files provide the same commands.
