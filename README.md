# Тестовая работа


## Сборка, запуск и инициализация

По умолчанию приложение использует порты 8000, smtp 1026, почтовый web client 1081.

Если порты заняты поменять в .env.docker значения переменных NGINX_HOST_HTTP_PORT, SMTP_PORT, SMTP_WEB_PORT

```bash
git clone https://github.com/tim31al/test-php-develop.git
cd test-php-develop
```

```bash
make build
make init
```

[Открыть в браузере](http://localhost:8000)

[Открыть в браузере почтовый клиент](http://localhost:1081)

## Тесты

```bash
make test
```

## Завершение работы, удаление контейнеров

```bash
make dc_down
```