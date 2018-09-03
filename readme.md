## Time Dispather

Подготовка окружения
---
* Создать sqlite базу данных. Выполнив в корне проекта через консоль `touch database/database.sqlite`
* Подтянуть все зависимости. Выполнил в корне проекта через консоль `composer install`
* Выставить права на запись для директорий `storage/logs`, `public/media`.
* Импортировать пользоватлей используя директиву `csv-import-account {path}`, где `{path}` - это путь от корня. 
Пример использования `php artisan csv-import-account /var/www/html/time-dispatcher/csv-import-files/ad-info-2018-07.csv`.
* Импортировать актуальную информацию о времени наработки пользователя следует использовать директиву `csv-import {path}*`, 
где `{path}` - это путь от корня. Пример использования `php artisan csv-import /var/www/html/time-dispatcher/csv-import-files/onofflog-2018-07.csv`.
* Настроить CRON, для периодического обновления данных о времени наработки работника.