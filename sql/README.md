## Задание №2. Спроектировать схему БД и написать запросы

Схема БД: [schema.sql](schema.sql)

#### Получить минимальную, максимальную и среднюю стоимость всех рабов весом более 60 кг.
```sql
SELECT
    MAX(`price`) AS `max`,
    MIN(`price`) AS `min`,
    AVG(`price`) AS `average`
FROM `slave`
WHERE `weight` > 60
```

#### Выбрать категории, в которых больше 10 рабов.
```sql
SELECT
    `category`.*,
    COUNT(`category`.`id`) AS `slaves_count`
FROM `slave_category`
LEFT JOIN `category` ON `category`.`id` = `slave_category`.`category_id`
GROUP BY `category`.`id`
HAVING `slaves_count` > 10
```

#### Выбрать категорию с наибольшей суммарной стоимостью рабов.
```sql
SELECT
    `category`.*,
    SUM(`slave`.`price`) AS `total_cost`
FROM `slave_category`
LEFT JOIN `slave` ON `slave`.`id` = `slave_category`.`slave_id`
LEFT JOIN `category` ON `category`.`id` = `slave_category`.`category_id`
GROUP BY `category`.`id`
ORDER BY `total_cost` DESC
LIMIT 1
```

#### Выбрать категории, в которых мужчин больше чем женщин.
```sql
SELECT
    `category`.*,
    SUM(IF(`slave`.`sex` = 'male', 1, 0)) AS `males_count`,
    SUM(IF(`slave`.`sex` = 'female', 1, 0)) AS `females_count`
FROM `slave_category`
LEFT JOIN `slave` ON `slave`.`id` = `slave_category`.`slave_id`
LEFT JOIN `category` ON `category`.`id` = `slave_category`.`category_id`
GROUP BY `category`.`id`
HAVING `males_count` > `females_count`
```

#### Количество рабов в категории "Для кухни" (включая все вложенные категории).
```sql
WITH RECURSIVE `cte` AS (
    SELECT
        `id`
    FROM `category`
    WHERE `name` = 'Для кухни'
    UNION ALL
    SELECT
        `c`.`id`
    FROM `category` `c`
    JOIN `cte` ON `cte`.`id` = `c`.`parent_id`
)
SELECT
    COUNT(*) AS `count`
FROM `slave_category`
WHERE `category_id` IN (SELECT `id` FROM `cte`)
```
