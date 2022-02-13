# Выполнение тестового задание «Биржа рабов»

Текст оригинального задания: [README_original.md](README_original.md)  

## Задание №1. Реализовать операцию аренды раба

[![Test](https://github.com/luzrain/slave-market/actions/workflows/tests.yaml/badge.svg)](https://github.com/luzrain/slave-market/actions/workflows/tests.yaml)
[![Psalm](https://github.com/luzrain/slave-market/actions/workflows/psalm.yaml/badge.svg)](https://github.com/luzrain/slave-market/actions/workflows/psalm.yaml)
[![Codestyle](https://github.com/luzrain/slave-market/actions/workflows/codestyle.yaml/badge.svg)](https://github.com/luzrain/slave-market/actions/workflows/codestyle.yaml)

### Команды для запуска проекта
| Команда | Описание |
|--|--|
| `make help` | Справка по командам |
| `make build` | Билд образа |
| `make start` | Старт приложения |
| `make stop` | Стоп приложения |
| `make clean` | Стоп приложения и очистка файлов |
| `make sh` | Войти в консоль контейнера |
| `make tests` | Запуск тестов |
| `make psalm` | Запуск статического анализатора кода |
| `make cs-fixer` | Запустить PHP CS Fixer и исправить codestyle |
| `make cs-fixer-test` | Запустить PHP CS Fixer и показать diff (не исправлять) |

## Задание №2. Спроектировать схему БД и написать запросы

Схема БД: [sql/schema.sql](sql/schema.sql)  
Запросы: [sql/README.md](sql/README.md)  
