## Docker

+ [Документация docker](https://www.docker.com/)
+ [docker-compose](https://docs.docker.com/compose/)

> Все команды запускаются из главной директории текущего проекта,
> поэтому не забудьте выполнить:
> ```bash
> cd /path/to/slave-market
> ```


#### Собрать образы:

```bash
make build
```

#### Запустить тесты:

```bash
make tests/execute
```

или следующим образом, если вам нужно выполнить только конкретный тест:

```bash
docker-compose run --rm --user=1000:1000 php vendor/bin/phpunit tests
```
