# Nouvu/Lerma
[![Latest Unstable Version](https://poser.pugx.org/nouvu/lerma/v/stable)](https://packagist.org/packages/nouvu/lerma) [![License](https://poser.pugx.org/nouvu/lerma/license)](//packagist.org/packages/nouvu/lerma)

> This implementation uses modules without PDO

### Composer

```sh
composer require nouvu/lerma:^7.2.4
```

## Initial use ##

#### #1 - method ####

```php
Lerma :: create ( DriverEnum $driver ): ConnectDataInterface
```

#### #2 - method ####

```php
new Lerma( string | array $dsn ): Lerma
```

## Connect MySQLi Example ##

#### #1 - Static Lerma instance

```php
use Nouvu\Database\{ Lerma, DriverEnum };

require 'vendor/autoload.php';

$lerma = Lerma :: create( driver: DriverEnum :: MySQLi )
	-> setData( host: '127.0.0.1', username: 'root', password: 'root' )
	-> setDatabaseName( dbname: 'dbtest' )
	-> setCharset( charset: 'utf8' )
	-> setPort( port: 3306 )
	-> getLerma();
```

#### #2 - Lerma instance ####

```php
use Nouvu\Database\Lerma;

require 'vendor/autoload.php';

// dsn can use sprintf
$lerma = new Lerma( [ 'mysql:host=%s;username=%s;password=%s;dbname=%s;charset=%s;port=%d', 
    '127.0.0.1', 'root', 'root', 'dbtest', 'utf8', 3306
] );
```

## Connect SQLite3 Example ##

#### #1 - Static Lerma instance

```php
use Nouvu\Database\{ Lerma, DriverEnum };

require 'vendor/autoload.php';

$lerma = Lerma :: create( driver: DriverEnum :: SQLite3 )
	-> setFile( __DIR__ . '/dbtest.db' )
	-> getLerma();
```

#### #2 - Lerma instance ####

```php
use Nouvu\Database\Lerma;

require 'vendor/autoload.php';

// dsn can use sprintf
$lerma = new Lerma( [ 'sqlite:db=%s', __DIR__ . '/dbtest.db' ] );
```

***

## Nouvu\Database\Lerma :: class ##

<details>
<summary>Contents spoiler</summary>

#### Список методов ####

Статический интерфейс подключения к Базе Данных
```php
public static function create( DriverEnum $driver ): ConnectDataInterface
```

Подготавливает запрос к выполнению

```php
public function prepare( string | array $sql, array $data = null ): LermaStatement
```

Выполняет запрос к базе данных 

```php
public function query( string | array $sql ): LermaStatement
```

Запускает подготовленный запрос на выполнение 

```php
public function execute( array $data ): void
```

Откат текущей транзакции

```php
public function rollBack( mixed ...$rollback ): bool
```

Стартует транзакцию

```php
public function beginTransaction( mixed ...$rollback ): bool
```

Фиксирует транзакцию

```php
public function commit( mixed ...$commit ): bool
```

Возвращает значение, созданное для столбца AUTO_INCREMENT последним запросом

```php
public function InsertID(): int
```

#### Code Examples ####

#1 - example

```php
$values = [ 'group' => 6, 'Lerma' ];

$statement = $lerma -> prepare( [ 'SELECT * FROM `%s` WHERE `group` = :group AND `name` = ?', 'table' ], $values );

echo json_encode ( $statement -> fetch( Lerma :: FETCH_ASSOC ), 480 );
```
```php
{
	"id": 8,
	"name": "Lerma",
	"group": 6,
	"text": "Инструмент",
	"created_at": "2022-02-06 23:44:30"
}
```

#2 - example

```php
$values = [
	[ 'name1', 'group1', 'text1' ],
	[ 'name2', 'group2', 'text2' ],
	[ 'name3', 'group3', 'text3' ],
];


$lerma -> prepare( [ 'INSERT INTO `%s`( `name`, `group`, `text` ) VALUES ( ?,?,? )', 'table' ], $values );

echo $lerma -> InsertID(); // 3
```

OR

```php
$values = [
	[ 'name1', 'group1', 'text1' ],
	[ 'name2', 'group2', 'text2' ],
	[ 'name3', 'group3', 'text3' ],
];


try
{
	$lerma -> beginTransaction();
	
	// uses rollBack if Exception
	$lerma -> prepare( [ 'INSERT INTO `%s`( `name`, `group`, `text` ) VALUES ( ?,?,? )', 'table' ], $values );
	
	$lerma -> commit();
}
catch ( \Nouvu\Database\Exception\LermaException )
{
	
}

// 2 ----------------

try
{
	$lerma -> beginTransaction();
	
	$lerma -> prepare( [ 'INSERT INTO `%s`( `name`, `group`, `text` ) VALUES ( ?,?,? )', 'table' ] );
	
	foreach ( $values AS $row )
	{
		$lerma -> execute( $row );
	}
	
	$lerma -> commit();
	
	echo $lerma -> InsertID(); // 3
}
catch ( \Nouvu\Database\Exception\LermaException )
{
	$lerma -> rollBack();
}
```
</details>

***

## Nouvu\Database\LermaStatement :: class ##

<details>
<summary>Contents spoiler</summary>

#### Список методов ####

Извлечение следующей строки из результирующего набора 

```php
public function fetch( int $mode = null, \Closure | string | null $argument = null ): mixed
```

Выбирает оставшиеся строки из набора результатов

```php
public function fetchAll( int $mode = null, \Closure | string | null $argument = null ): iterable
```

Возвращает количество строк, затронутых последним SQL-запросом 

```php
public function rowCount(): int
```

Возвращает количество столбцов в результирующем наборе

```php
public function columnCount(): int
```

Извлекает внешний итератор

```php
public function getIterator(): \Traversable
```
> Только для MySQLi

#### Code Examples ####

#1 - example - iterator MySQLi

```php
$statement = $lerma -> query( 'SELECT * ...' );

foreach ( $statement AS $row )
{
	// result $row
}

// OR

iterator_to_array ( $statement );
```
```php
Array
(
    [0] => Array
        (
            [0] => 1
            [1] => Nouvu-Skeleton
            [2] => 1
            [3] => скелет
            [4] => 2022-02-06 23:44:30
        )

    [1] => Array
        (
            [0] => 2
            [1] => Nouvu-Framework
            [2] => 1
            [3] => ядро
            [4] => 2022-02-06 23:44:30
        )

    [2] => Array
        (
            [0] => 3
            [1] => Nouvu-Web
            [2] => 1
            [3] => веб
            [4] => 2022-02-06 23:44:30
        )
	
	...
)
```

#2 - example - fetch(All)

```php
$statement = $lerma -> query( 'SELECT `name` ...' );

while ( $value = $statement -> fetch( Lerma :: FETCH_COLUMN ) )
{
	// result #1
}

foreach ( $statement -> fetchAll( Lerma :: FETCH_COLUMN ) AS $value )
{
	// result #2
}
```

result #1 and #2

```sh
Nouvu-Skeleton
Nouvu-Framework
Nouvu-Web
ContainerPHP
Logger
Query-Storage-Bank
Neuronet
Lerma
McBanner
Piramid
Aero
Aero2
Aero-Authentication
```

#3 - Новая возможность - FETCH_FUNC from Generator

```php
$statement = $lerma -> query( 'SELECT `name` ...' );

$statement -> fetchAll( Lerma :: FETCH_FUNC, function ( object $std ) )
{
	yield $std -> id => $std;
}
```

> Предупреждение:
```sh
'SELECT * FROM table LIMIT ?, ?'
```
> Вызовет ошибку как и в других случаях синтаксиса query. Используйте принудительно подстановку значений в запрос

</details>

## Список режимов ##

| name | fetch | fetchAll | code |
| ------ | ------ | ------ | ------ |
| Lerma :: FETCH_NUM | + | + | 1 |
| Lerma :: FETCH_ASSOC | + | + | 2 |
| Lerma :: FETCH_OBJ | + | + | 4 |
| Lerma :: FETCH_COLUMN | + | + | 265 |
| Lerma :: FETCH_FUNC | + | + | 586 |
| Lerma :: FETCH_KEY_PAIR | + | + | 307 |
| Lerma :: FETCH_KEY_PAIR \| Lerma :: FETCH_FUNC | - | + | 891 |
| Lerma :: FETCH_UNIQUE | - | + | 333 |
| Lerma :: FETCH_GROUP | - | + | 428 |
| Lerma :: FETCH_GROUP \| Lerma :: FETCH_COLUMN | - | + | 429 |
| Lerma :: MYSQL_FETCH_FIELD | + | + | 343 |
| Lerma :: MYSQL_FETCH_BIND | + | - | 663 |
| Lerma :: MYSQL_FETCH_BIND \| Lerma :: FETCH_COLUMN | + | - | 927 |

## Helper Functions ##

<details>
<summary>Contents spoiler</summary>

namespace

```php
use function Nouvu\Database\Helpers\{ ... };
```

Доступ к внутреннему файлу конфигурации

```php
config( string $offset = null ): mixed
```
```php
// Отключение именованных плейсхолдеров
config() -> set( 'namedPlaceholders', false );

// Установка режима вывода по умолчанию
config() -> set( 'mode', Lerma :: FETCH_OBJ );

// Вывод списка данных для подключения различных расширений
config( 'drivers' );
```
```php
use Nouvu\Config\Config;
use Nouvu\Database\Lerma;
use Nouvu\Database\Modules;

return [
	'dsn_default' => 'mysql',
	'drivers' => [
		'mysql' => [
			'module' => Modules\MySQLi :: class,
			'dbname' => 'dbtest',
			'host' => '127.0.0.1',
			'port' => 3306,
			'charset' => 'utf8',
			'username' => 'root',
			'password' => 'root'
		],
		'sqlite' => [
			'module' => Modules\SQLite3 :: class,
			'db' => 'lerma.db'
		],
	],
	'mode' => Lerma :: FETCH_NUM,
	'namedPlaceholders' => true,
	'ShemaExceptionConnect' => [
		'mysql' => static function ( mysqli_sql_exception $mysqli_sql_exception )
		{
			throw $mysqli_sql_exception;
		},
	],
	\Facade\Create :: class => [
		'mysql' => \Nouvu\Database\Modules\Facade\Mysql\ConnectData :: class,
		'sqlite' => \Nouvu\Database\Modules\Facade\Sqlite\ConnectData :: class,
	],
]
```

Debug строки запроса и значений

```php
debug( bool $reset = false ): Debug
```
```php
echo json_encode ( debug(), 480 );
```

</details>

## Прямой доступ к расширению ##

```php
$connect = $lerma -> connect() -> get();
```

## Fetch mode Result Examples ##

<details>
<summary>Lerma :: FETCH_NUM</summary>

```php
$stmt = $lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetch( Lerma :: FETCH_NUM ) );
```
```php
Array
(
    [0] => 1
    [1] => Nouvu-Skeleton
    [2] => 1
    [3] => скелет
    [4] => 2022-02-06 23:44:30
)
```
```php
$stmt = $lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetchAll( Lerma :: FETCH_NUM ) );
```
```php
Array
(
    [0] => Array
        (
            [0] => 1
            [1] => Nouvu-Skeleton
            [2] => 1
            [3] => скелет
            [4] => 2022-02-06 23:44:30
        )

    [1] => Array
        (
            [0] => 2
            [1] => Nouvu-Framework
            [2] => 1
            [3] => ядро
            [4] => 2022-02-06 23:44:30
        )

    [2] => Array
        (
            [0] => 3
            [1] => Nouvu-Web
            [2] => 1
            [3] => веб
            [4] => 2022-02-06 23:44:30
        )

    [3] => Array
        (
            [0] => 4
            [1] => ContainerPHP
            [2] => 2
            [3] => ленивая загрузка
            [4] => 2022-02-06 23:44:30
        )

    [4] => Array
        (
            [0] => 5
            [1] => Logger
            [2] => 3
            [3] => логирование
            [4] => 2022-02-06 23:44:30
        )

    [5] => Array
        (
            [0] => 6
            [1] => Query-Storage-Bank
            [2] => 4
            [3] => хранимые процедуры
            [4] => 2022-02-06 23:44:30
        )

    [6] => Array
        (
            [0] => 7
            [1] => Neuronet
            [2] => 5
            [3] => Нейросеть
            [4] => 2022-02-06 23:44:30
        )

    [7] => Array
        (
            [0] => 8
            [1] => Lerma
            [2] => 6
            [3] => Инструмент
            [4] => 2022-02-06 23:44:30
        )

    [8] => Array
        (
            [0] => 9
            [1] => McBanner
            [2] => 7
            [3] => Счетчик
            [4] => 2022-02-06 23:44:30
        )

    [9] => Array
        (
            [0] => 10
            [1] => Piramid
            [2] => 7
            [3] => Пирамида цветная
            [4] => 2022-02-06 23:44:30
        )

    [10] => Array
        (
            [0] => 11
            [1] => Aero
            [2] => 8
            [3] => старое ядро
            [4] => 2022-02-06 23:44:30
        )

    [11] => Array
        (
            [0] => 12
            [1] => Aero2
            [2] => 8
            [3] => старое ядро2
            [4] => 2022-02-06 23:44:30
        )

    [12] => Array
        (
            [0] => 13
            [1] => Aero-Authentication
            [2] => 9
            [3] => в мусор
            [4] => 2022-02-06 23:44:30
        )

)
```
</details>
<details>
<summary>Lerma :: FETCH_ASSOC</summary>

```php
$stmt = $lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetch( Lerma :: FETCH_ASSOC ) );
```
```php
Array
(
    [id] => 1
    [name] => Nouvu-Skeleton
    [group] => 1
    [text] => скелет
    [created_at] => 2022-02-06 23:44:30
)
```
```php
$stmt = $lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetchAll( Lerma :: FETCH_ASSOC ) );
```
```php
Array
(
    [0] => Array
        (
            [id] => 1
            [name] => Nouvu-Skeleton
            [group] => 1
            [text] => скелет
            [created_at] => 2022-02-06 23:44:30
        )

    [1] => Array
        (
            [id] => 2
            [name] => Nouvu-Framework
            [group] => 1
            [text] => ядро
            [created_at] => 2022-02-06 23:44:30
        )

    [2] => Array
        (
            [id] => 3
            [name] => Nouvu-Web
            [group] => 1
            [text] => веб
            [created_at] => 2022-02-06 23:44:30
        )

    [3] => Array
        (
            [id] => 4
            [name] => ContainerPHP
            [group] => 2
            [text] => ленивая загрузка
            [created_at] => 2022-02-06 23:44:30
        )

    [4] => Array
        (
            [id] => 5
            [name] => Logger
            [group] => 3
            [text] => логирование
            [created_at] => 2022-02-06 23:44:30
        )

    [5] => Array
        (
            [id] => 6
            [name] => Query-Storage-Bank
            [group] => 4
            [text] => хранимые процедуры
            [created_at] => 2022-02-06 23:44:30
        )

    [6] => Array
        (
            [id] => 7
            [name] => Neuronet
            [group] => 5
            [text] => Нейросеть
            [created_at] => 2022-02-06 23:44:30
        )

    [7] => Array
        (
            [id] => 8
            [name] => Lerma
            [group] => 6
            [text] => Инструмент
            [created_at] => 2022-02-06 23:44:30
        )

    [8] => Array
        (
            [id] => 9
            [name] => McBanner
            [group] => 7
            [text] => Счетчик
            [created_at] => 2022-02-06 23:44:30
        )

    [9] => Array
        (
            [id] => 10
            [name] => Piramid
            [group] => 7
            [text] => Пирамида цветная
            [created_at] => 2022-02-06 23:44:30
        )

    [10] => Array
        (
            [id] => 11
            [name] => Aero
            [group] => 8
            [text] => старое ядро
            [created_at] => 2022-02-06 23:44:30
        )

    [11] => Array
        (
            [id] => 12
            [name] => Aero2
            [group] => 8
            [text] => старое ядро2
            [created_at] => 2022-02-06 23:44:30
        )

    [12] => Array
        (
            [id] => 13
            [name] => Aero-Authentication
            [group] => 9
            [text] => в мусор
            [created_at] => 2022-02-06 23:44:30
        )

)
```
</details>
<details>
<summary>Lerma :: FETCH_OBJ</summary>

```php
$stmt = $lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetch( Lerma :: FETCH_OBJ ) );
```
```php
stdClass Object
(
    [id] => 1
    [name] => Nouvu-Skeleton
    [group] => 1
    [text] => скелет
    [created_at] => 2022-02-06 23:44:30
)
```
```php
$stmt = $lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetchAll( Lerma :: FETCH_OBJ ) );
```
```php
Array
(
    [0] => stdClass Object
        (
            [id] => 1
            [name] => Nouvu-Skeleton
            [group] => 1
            [text] => скелет
            [created_at] => 2022-02-06 23:44:30
        )

    [1] => stdClass Object
        (
            [id] => 2
            [name] => Nouvu-Framework
            [group] => 1
            [text] => ядро
            [created_at] => 2022-02-06 23:44:30
        )

    [2] => stdClass Object
        (
            [id] => 3
            [name] => Nouvu-Web
            [group] => 1
            [text] => веб
            [created_at] => 2022-02-06 23:44:30
        )

    [3] => stdClass Object
        (
            [id] => 4
            [name] => ContainerPHP
            [group] => 2
            [text] => ленивая загрузка
            [created_at] => 2022-02-06 23:44:30
        )

    [4] => stdClass Object
        (
            [id] => 5
            [name] => Logger
            [group] => 3
            [text] => логирование
            [created_at] => 2022-02-06 23:44:30
        )

    [5] => stdClass Object
        (
            [id] => 6
            [name] => Query-Storage-Bank
            [group] => 4
            [text] => хранимые процедуры
            [created_at] => 2022-02-06 23:44:30
        )

    [6] => stdClass Object
        (
            [id] => 7
            [name] => Neuronet
            [group] => 5
            [text] => Нейросеть
            [created_at] => 2022-02-06 23:44:30
        )

    [7] => stdClass Object
        (
            [id] => 8
            [name] => Lerma
            [group] => 6
            [text] => Инструмент
            [created_at] => 2022-02-06 23:44:30
        )

    [8] => stdClass Object
        (
            [id] => 9
            [name] => McBanner
            [group] => 7
            [text] => Счетчик
            [created_at] => 2022-02-06 23:44:30
        )

    [9] => stdClass Object
        (
            [id] => 10
            [name] => Piramid
            [group] => 7
            [text] => Пирамида цветная
            [created_at] => 2022-02-06 23:44:30
        )

    [10] => stdClass Object
        (
            [id] => 11
            [name] => Aero
            [group] => 8
            [text] => старое ядро
            [created_at] => 2022-02-06 23:44:30
        )

    [11] => stdClass Object
        (
            [id] => 12
            [name] => Aero2
            [group] => 8
            [text] => старое ядро2
            [created_at] => 2022-02-06 23:44:30
        )

    [12] => stdClass Object
        (
            [id] => 13
            [name] => Aero-Authentication
            [group] => 9
            [text] => в мусор
            [created_at] => 2022-02-06 23:44:30
        )

)
```
</details>
<details>
<summary>Lerma :: FETCH_COLUMN</summary>

```php
$stmt = $lerma -> query( [ 'SELECT `name` FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetch( Lerma :: FETCH_COLUMN ) );
```
```php
Nouvu-Skeleton
```
```php
$stmt = $lerma -> query( [ 'SELECT `name` FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetchAll( Lerma :: FETCH_COLUMN ) );
```
```php
Array
(
    [0] => Nouvu-Skeleton
    [1] => Nouvu-Framework
    [2] => Nouvu-Web
    [3] => ContainerPHP
    [4] => Logger
    [5] => Query-Storage-Bank
    [6] => Neuronet
    [7] => Lerma
    [8] => McBanner
    [9] => Piramid
    [10] => Aero
    [11] => Aero2
    [12] => Aero-Authentication
)
```
</details>
<details>
<summary>Lerma :: FETCH_FUNC</summary>

```php
$stmt = $lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetch( Lerma :: FETCH_FUNC, function ( object $data ): string
{
	return implode ( ' - ', ( array ) $data );
} ) );
```
```php
1 - Nouvu-Skeleton - 1 - скелет - 2022-02-06 23:44:30
```
```php
$stmt = $lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetchAll( Lerma :: FETCH_FUNC, function ( object $data ): string
{
	return implode ( ' - ', ( array ) $data );
} ) );
```
```php
Array
(
    [0] => 1 - Nouvu-Skeleton - 1 - скелет - 2022-02-06 23:44:30
    [1] => 2 - Nouvu-Framework - 1 - ядро - 2022-02-06 23:44:30
    [2] => 3 - Nouvu-Web - 1 - веб - 2022-02-06 23:44:30
    [3] => 4 - ContainerPHP - 2 - ленивая загрузка - 2022-02-06 23:44:30
    [4] => 5 - Logger - 3 - логирование - 2022-02-06 23:44:30
    [5] => 6 - Query-Storage-Bank - 4 - хранимые процедуры - 2022-02-06 23:44:30
    [6] => 7 - Neuronet - 5 - Нейросеть - 2022-02-06 23:44:30
    [7] => 8 - Lerma - 6 - Инструмент - 2022-02-06 23:44:30
    [8] => 9 - McBanner - 7 - Счетчик - 2022-02-06 23:44:30
    [9] => 10 - Piramid - 7 - Пирамида цветная - 2022-02-06 23:44:30
    [10] => 11 - Aero - 8 - старое ядро - 2022-02-06 23:44:30
    [11] => 12 - Aero2 - 8 - старое ядро2 - 2022-02-06 23:44:30
    [12] => 13 - Aero-Authentication - 9 - в мусор - 2022-02-06 23:44:30
)
```

Возможность - Generator

```php
$statement = $lerma -> query( 'SELECT `name` ...' );

$statement -> fetchAll( Lerma :: FETCH_FUNC, function ( object $data ) ): iterable
{
	yield $data -> id => $data;
}
```
</details>
<details>
<summary>Lerma :: FETCH_KEY_PAIR</summary>

```php
$stmt = $lerma -> query( [ 'SELECT `name`, `text` FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetch( Lerma :: FETCH_KEY_PAIR ) );
```
```php
Array
(
    [Nouvu-Skeleton] => скелет
)
```
```php
$stmt = $lerma -> query( [ 'SELECT `name`, `text` FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetchAll( Lerma :: FETCH_KEY_PAIR ) );
```
```php
Array
(
    [Nouvu-Skeleton] => скелет
    [Nouvu-Framework] => ядро
    [Nouvu-Web] => веб
    [ContainerPHP] => ленивая загрузка
    [Logger] => логирование
    [Query-Storage-Bank] => хранимые процедуры
    [Neuronet] => Нейросеть
    [Lerma] => Инструмент
    [McBanner] => Счетчик
    [Piramid] => Пирамида цветная
    [Aero] => старое ядро
    [Aero2] => старое ядро2
    [Aero-Authentication] => в мусор
)
```
</details>
<details>
<summary>Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC</summary>

```php
$stmt = $lerma -> query( [ 'SELECT `name`, `text` FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetchAll( Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC, function ( string $first, string $second ): string
{
	return "--- {{$first} | {$second}} ---";
} ) );
```
```php
Array
(
    [Nouvu-Skeleton] => --- {Nouvu-Skeleton | скелет} ---
    [Nouvu-Framework] => --- {Nouvu-Framework | ядро} ---
    [Nouvu-Web] => --- {Nouvu-Web | веб} ---
    [ContainerPHP] => --- {ContainerPHP | ленивая загрузка} ---
    [Logger] => --- {Logger | логирование} ---
    [Query-Storage-Bank] => --- {Query-Storage-Bank | хранимые процедуры} ---
    [Neuronet] => --- {Neuronet | Нейросеть} ---
    [Lerma] => --- {Lerma | Инструмент} ---
    [McBanner] => --- {McBanner | Счетчик} ---
    [Piramid] => --- {Piramid | Пирамида цветная} ---
    [Aero] => --- {Aero | старое ядро} ---
    [Aero2] => --- {Aero2 | старое ядро2} ---
    [Aero-Authentication] => --- {Aero-Authentication | в мусор} ---
)
```
</details>
<details>
<summary>Lerma :: FETCH_UNIQUE</summary>

```php
$stmt = $lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetchAll( Lerma :: FETCH_UNIQUE, 'group' ) );
```
```php
Array
(
    [1] => stdClass Object
        (
            [id] => 3
            [name] => Nouvu-Web
            [group] => 1
            [text] => веб
            [created_at] => 2022-02-06 23:44:30
        )

    [2] => stdClass Object
        (
            [id] => 4
            [name] => ContainerPHP
            [group] => 2
            [text] => ленивая загрузка
            [created_at] => 2022-02-06 23:44:30
        )

    [3] => stdClass Object
        (
            [id] => 5
            [name] => Logger
            [group] => 3
            [text] => логирование
            [created_at] => 2022-02-06 23:44:30
        )

    [4] => stdClass Object
        (
            [id] => 6
            [name] => Query-Storage-Bank
            [group] => 4
            [text] => хранимые процедуры
            [created_at] => 2022-02-06 23:44:30
        )

    [5] => stdClass Object
        (
            [id] => 7
            [name] => Neuronet
            [group] => 5
            [text] => Нейросеть
            [created_at] => 2022-02-06 23:44:30
        )

    [6] => stdClass Object
        (
            [id] => 8
            [name] => Lerma
            [group] => 6
            [text] => Инструмент
            [created_at] => 2022-02-06 23:44:30
        )

    [7] => stdClass Object
        (
            [id] => 10
            [name] => Piramid
            [group] => 7
            [text] => Пирамида цветная
            [created_at] => 2022-02-06 23:44:30
        )

    [8] => stdClass Object
        (
            [id] => 12
            [name] => Aero2
            [group] => 8
            [text] => старое ядро2
            [created_at] => 2022-02-06 23:44:30
        )

    [9] => stdClass Object
        (
            [id] => 13
            [name] => Aero-Authentication
            [group] => 9
            [text] => в мусор
            [created_at] => 2022-02-06 23:44:30
        )

)
```
</details>
<details>
<summary>Lerma :: FETCH_GROUP</summary>

```php
$stmt = $lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetchAll( Lerma :: FETCH_GROUP, 'group' ) );
```
```php
Array
(
    [1] => Array
        (
            [0] => stdClass Object
                (
                    [id] => 1
                    [name] => Nouvu-Skeleton
                    [group] => 1
                    [text] => скелет
                    [created_at] => 2022-02-06 23:44:30
                )

            [1] => stdClass Object
                (
                    [id] => 2
                    [name] => Nouvu-Framework
                    [group] => 1
                    [text] => ядро
                    [created_at] => 2022-02-06 23:44:30
                )

            [2] => stdClass Object
                (
                    [id] => 3
                    [name] => Nouvu-Web
                    [group] => 1
                    [text] => веб
                    [created_at] => 2022-02-06 23:44:30
                )

        )

    [2] => Array
        (
            [0] => stdClass Object
                (
                    [id] => 4
                    [name] => ContainerPHP
                    [group] => 2
                    [text] => ленивая загрузка
                    [created_at] => 2022-02-06 23:44:30
                )

        )

    [3] => Array
        (
            [0] => stdClass Object
                (
                    [id] => 5
                    [name] => Logger
                    [group] => 3
                    [text] => логирование
                    [created_at] => 2022-02-06 23:44:30
                )

        )

    [4] => Array
        (
            [0] => stdClass Object
                (
                    [id] => 6
                    [name] => Query-Storage-Bank
                    [group] => 4
                    [text] => хранимые процедуры
                    [created_at] => 2022-02-06 23:44:30
                )

        )

    [5] => Array
        (
            [0] => stdClass Object
                (
                    [id] => 7
                    [name] => Neuronet
                    [group] => 5
                    [text] => Нейросеть
                    [created_at] => 2022-02-06 23:44:30
                )

        )

    [6] => Array
        (
            [0] => stdClass Object
                (
                    [id] => 8
                    [name] => Lerma
                    [group] => 6
                    [text] => Инструмент
                    [created_at] => 2022-02-06 23:44:30
                )

        )

    [7] => Array
        (
            [0] => stdClass Object
                (
                    [id] => 9
                    [name] => McBanner
                    [group] => 7
                    [text] => Счетчик
                    [created_at] => 2022-02-06 23:44:30
                )

            [1] => stdClass Object
                (
                    [id] => 10
                    [name] => Piramid
                    [group] => 7
                    [text] => Пирамида цветная
                    [created_at] => 2022-02-06 23:44:30
                )

        )

    [8] => Array
        (
            [0] => stdClass Object
                (
                    [id] => 11
                    [name] => Aero
                    [group] => 8
                    [text] => старое ядро
                    [created_at] => 2022-02-06 23:44:30
                )

            [1] => stdClass Object
                (
                    [id] => 12
                    [name] => Aero2
                    [group] => 8
                    [text] => старое ядро2
                    [created_at] => 2022-02-06 23:44:30
                )

        )

    [9] => Array
        (
            [0] => stdClass Object
                (
                    [id] => 13
                    [name] => Aero-Authentication
                    [group] => 9
                    [text] => в мусор
                    [created_at] => 2022-02-06 23:44:30
                )

        )

)
```
</details>
<details>
<summary>Lerma :: FETCH_GROUP | Lerma :: FETCH_COLUMN</summary>

```php
$stmt = $lerma -> query( [ 'SELECT `group`, `name` FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetchAll( Lerma :: FETCH_GROUP | Lerma :: FETCH_COLUMN ) );
```
```php
Array
(
    [1] => Array
        (
            [0] => Nouvu-Skeleton
            [1] => Nouvu-Framework
            [2] => Nouvu-Web
        )

    [2] => Array
        (
            [0] => ContainerPHP
        )

    [3] => Array
        (
            [0] => Logger
        )

    [4] => Array
        (
            [0] => Query-Storage-Bank
        )

    [5] => Array
        (
            [0] => Neuronet
        )

    [6] => Array
        (
            [0] => Lerma
        )

    [7] => Array
        (
            [0] => McBanner
            [1] => Piramid
        )

    [8] => Array
        (
            [0] => Aero
            [1] => Aero2
        )

    [9] => Array
        (
            [0] => Aero-Authentication
        )

)
```
</details>
<details>
<summary>Lerma :: MYSQL_FETCH_FIELD</summary>

```php
$stmt = $lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetch( Lerma :: MYSQL_FETCH_FIELD ) );
```
```php
stdClass Object
(
    [name] => id
    [orgname] => id
    [table] => github_test
    [orgtable] => github_test
    [def] => 
    [db] => dbtest
    [catalog] => def
    [max_length] => 0
    [length] => 11
    [charsetnr] => 63
    [flags] => 49667
    [type] => 3
    [decimals] => 0
)
```
```php
$stmt = $lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );

print_r ( $stmt -> fetchAll( Lerma :: MYSQL_FETCH_FIELD ) );
```
```php
Array
(
    [0] => stdClass Object
        (
            [name] => id
            [orgname] => id
            [table] => github_test
            [orgtable] => github_test
            [def] => 
            [db] => dbtest
            [catalog] => def
            [max_length] => 0
            [length] => 11
            [charsetnr] => 63
            [flags] => 49667
            [type] => 3
            [decimals] => 0
        )

    [1] => stdClass Object
        (
            [name] => name
            [orgname] => name
            [table] => github_test
            [orgtable] => github_test
            [def] => 
            [db] => dbtest
            [catalog] => def
            [max_length] => 0
            [length] => 196605
            [charsetnr] => 33
            [flags] => 4113
            [type] => 252
            [decimals] => 0
        )

    [2] => stdClass Object
        (
            [name] => group
            [orgname] => group
            [table] => github_test
            [orgtable] => github_test
            [def] => 
            [db] => dbtest
            [catalog] => def
            [max_length] => 0
            [length] => 11
            [charsetnr] => 63
            [flags] => 36865
            [type] => 3
            [decimals] => 0
        )

    [3] => stdClass Object
        (
            [name] => text
            [orgname] => text
            [table] => github_test
            [orgtable] => github_test
            [def] => 
            [db] => dbtest
            [catalog] => def
            [max_length] => 0
            [length] => 196605
            [charsetnr] => 33
            [flags] => 16
            [type] => 252
            [decimals] => 0
        )

    [4] => stdClass Object
        (
            [name] => created_at
            [orgname] => created_at
            [table] => github_test
            [orgtable] => github_test
            [def] => 
            [db] => dbtest
            [catalog] => def
            [max_length] => 0
            [length] => 19
            [charsetnr] => 63
            [flags] => 129
            [type] => 12
            [decimals] => 0
        )

)
```
</details>
<details>
<summary>Lerma :: MYSQL_FETCH_BIND</summary>

```php
$stmt = $lerma -> prepare( [ 'SELECT * FROM `%s` WHERE ?', $this -> table ], [ 1 ] );

print_r ( $stmt -> fetch( Lerma :: MYSQL_FETCH_BIND ) );
```
```php
Array
(
    [0] => 1
    [1] => Nouvu-Skeleton
    [2] => 1
    [3] => скелет
    [4] => 2022-02-06 23:44:30
)
```
</details>
<details>
<summary>Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN</summary>

```php
$stmt = $lerma -> prepare( [ 'SELECT `name` FROM `%s` WHERE ?', $this -> table ], [ 1 ] );

print_r ( $stmt -> fetch( Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN ) );
```
```php
Nouvu-Skeleton
```
</details>

***

Create by [MouseZver](//php.ru/forum/members/40235)
