# my-warehouse
Мой склад

# Установка
Добавить в config/app.php
- providers: \Zelvad\MyWarehouse\MyWarehouseServiceProvider::class,
- Добавить в исключения URL 'my-warehouse/*' (App/Http/Middleware/VerifyCsrfToken.php)
- Добавить в .env MY_WAREHOUSE_API_TOKEN=token
- Исполнить php artisan vendor:publish
- Исполнить php artisan migrate
# Команды
- php artisan sync:productFolders : Синхронизирует группы товаров
# Webhook URLs
- CREATE: POST {{domain}}/my-warehouse/create
- UPDATE: POST {{domain}}/my-warehouse/update
- DELETE: POST {{domain}}/my-warehouse/delete
