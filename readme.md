# Telegram PHP logger

Простой PHP-класс для логирование в чат\канал в telegram.

## Установка


### Установка через Composer

Запустите

```
php composer.phar require benyazi/telegram-php-log
```

или добавьте

```js
"benyazi/telegram-php-log": "dev-master"
```

в секцию ```require``` вашего composer.json


## Использование


```php
$logger = new \Benyazi\TelegramLog\Logger(BOT_KEY, CHAT_ID);
```

Отправка обычного лога:

```php
$logger->info('Info log');
```


Отправка лога ошибки:

```php
$logger->error('Error log');
```


Добавление данных для отображения в сообщении:

```php
try {
} catch (\Exception $e) {
    $logger->error("Error log", ["exception_message" => $e->getMessage(), "file" => $e->getFile()]);
}
```


Установка дополнительных хэштегов в сообщение:

```php
$logger->error("Error log", [], ["alarm", "ahtung", "serezha_vinovat"]);
```


## Автор

[Sergey Klabukov](https://github.com/benyazi/), e-mail: [yo@benyazi.ru](mailto:yo@benyazi.ru)
