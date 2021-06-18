# Nouvu/Lerma
[![Latest Unstable Version](https://poser.pugx.org/nouvu/lerma/v/stable)](https://packagist.org/packages/nouvu/lerma) [![License](https://poser.pugx.org/nouvu/lerma/license)](//packagist.org/packages/nouvu/lerma)

> composer require nouvu/lerma

***

#### dsn-minimum options:

```php
$lrm = new Nouvu\Database\Lerma; // default load -> mysql ext

$lrm = new Nouvu\Database\Lerma( 'sqlite' );

$lrm = new Nouvu\Database\Lerma( 'mysql' );

$lrm = new Nouvu\Database\Lerma( 'mysql:dbname=git;charset=utf8;username=root;password=root' );

$lrm = new Nouvu\Database\Lerma( 'sqlite:db=test.db' );
```

#### dsn-max options:

```php
$lrm = new Nouvu\Database\Lerma( sprintf ( 'mysql:namespace=%s;host=%s;port=%s;dbname=%s;charset=%s;username=%s;password=%s',
	
	# namespace string for load Ext
	Nouvu\Database\LermaExt\Mysql :: class,
	
	# host / no use 'localhost' if exists problem
	'127.0.0.1',
	
	# port
	3306,
	
	# dbname
	'git',
	
	# charset
	'utf8',
	
	# username
	'root',
	
	# password
	'root'
	
) );
```

#### emulate prepares:

```php
$lrm = new Nouvu\Database\Lerma( 'mysql', static function ( Nouvu\Config\Config $config )
{
	$config -> set( 'ShemaActiveFun.replaceHolders.mysql', fn( &$a ) => $a = true ); // default true
} );

$stmt = $lrm -> prepare( [ 'SELECT * FROM `%s` WHERE `num` = :num', 'lerma' ], [ 'num' => 111 ] );
```

#### Fatal Error: Session ended by calling another

```php
$stmt1 = $lrm -> query( 'SELECT 1' ); // session 1

$stmt2 = $lrm -> query( 'SELECT 2' ); // session 1 close & start session 2

var_dump ( $stmt1 -> fetchAll(), $stmt2 -> fetchAll() );
```

#### fetch constants:
```php
Lerma :: FETCH_NUM => [ 'fetch_num', 'all' => 'fetchall_num' ],
Lerma :: FETCH_ASSOC => [ 'fetch_assoc', 'all' => 'fetchall_assoc' ],
Lerma :: FETCH_OBJ => [ 'fetch_obj', 'all' => 'fetchall_obj' ],
Lerma :: MYSQL_FETCH_FIELD => [ 'fetch_field', 'all' => 'fetchall_field' ],
Lerma :: MYSQL_FETCH_BIND => [ 'fetch_bind' ],
Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN => [ 'fetch_bind' ],
Lerma :: FETCH_COLUMN => [ 'fetch_column', 'all' => 'fetchall_obj' ],
Lerma :: FETCH_KEY_PAIR => [ 'fetch_key_pair', 'all' => 'fetchall_key_pair' ],
Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_NAMED => [ 'all' => 'fetchall_key_pair' ],
Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC => [ 'all' => 'fetchall_key_pair' ],
Lerma :: FETCH_FUNC => [ 'fetch_func', 'all' => 'fetchall_obj' ],
Lerma :: FETCH_UNIQUE => [ 'all' => 'fetchall_unique' ],
Lerma :: FETCH_GROUP => [ 'all' => 'fetchall_group' ],
Lerma :: FETCH_GROUP | Lerma :: FETCH_COLUMN => [ 'all' => 'fetchall_group_column' ],
```

#### TestingMethods:
<details>
  <summary>All result fetch/fetchAll</summary>
  
  ```php
fetch( Lerma :: FETCH_NUM )

Array
(
    [0] => 138
    [1] => Nouvu\Database\Lerma
    [2] => 111
)

fetchall( Lerma :: FETCH_NUM )

Array
(
    [0] => Array
        (
            [0] => 138
            [1] => Nouvu\Database\Lerma
            [2] => 111
        )

    [1] => Array
        (
            [0] => 139
            [1] => Nouvu\Database\ComponentFetch
            [2] => 111
        )

    [2] => Array
        (
            [0] => 140
            [1] => php7.4
            [2] => 111
        )

    [3] => Array
        (
            [0] => 141
            [1] => Database
            [2] => 111
        )

    [4] => Array
        (
            [0] => 142
            [1] => Nouvu\Database\Core
            [2] => 222
        )

    [5] => Array
        (
            [0] => 143
            [1] => InterfaceDriver
            [2] => 333
        )

    [6] => Array
        (
            [0] => 144
            [1] => Nouvu\Database\LermaStatement
            [2] => 333
        )

)

fetch( Lerma :: FETCH_ASSOC )

Array
(
    [id] => 138
    [name] => Nouvu\Database\Lerma
    [num] => 111
)

fetchall( Lerma :: FETCH_ASSOC )

Array
(
    [0] => Array
        (
            [id] => 138
            [name] => Nouvu\Database\Lerma
            [num] => 111
        )

    [1] => Array
        (
            [id] => 139
            [name] => Nouvu\Database\ComponentFetch
            [num] => 111
        )

    [2] => Array
        (
            [id] => 140
            [name] => php7.4
            [num] => 111
        )

    [3] => Array
        (
            [id] => 141
            [name] => Database
            [num] => 111
        )

    [4] => Array
        (
            [id] => 142
            [name] => Nouvu\Database\Core
            [num] => 222
        )

    [5] => Array
        (
            [id] => 143
            [name] => InterfaceDriver
            [num] => 333
        )

    [6] => Array
        (
            [id] => 144
            [name] => Nouvu\Database\LermaStatement
            [num] => 333
        )

)

fetch( Lerma :: FETCH_OBJ )

stdClass Object
(
    [id] => 138
    [name] => Nouvu\Database\Lerma
    [num] => 111
)

fetchall( Lerma :: FETCH_OBJ )

Array
(
    [0] => stdClass Object
        (
            [id] => 138
            [name] => Nouvu\Database\Lerma
            [num] => 111
        )

    [1] => stdClass Object
        (
            [id] => 139
            [name] => Nouvu\Database\ComponentFetch
            [num] => 111
        )

    [2] => stdClass Object
        (
            [id] => 140
            [name] => php7.4
            [num] => 111
        )

    [3] => stdClass Object
        (
            [id] => 141
            [name] => Database
            [num] => 111
        )

    [4] => stdClass Object
        (
            [id] => 142
            [name] => Nouvu\Database\Core
            [num] => 222
        )

    [5] => stdClass Object
        (
            [id] => 143
            [name] => InterfaceDriver
            [num] => 333
        )

    [6] => stdClass Object
        (
            [id] => 144
            [name] => Nouvu\Database\LermaStatement
            [num] => 333
        )

)

fetch( Lerma :: MYSQL_FETCH_FIELD )

Array
(
    [name] => id
    [orgname] => id
    [table] => lerma
    [orgtable] => lerma
    [def] => 
    [db] => git
    [catalog] => def
    [max_length] => 3
    [length] => 11
    [charsetnr] => 63
    [flags] => 49667
    [type] => 3
    [decimals] => 0
)

fetchall( Lerma :: MYSQL_FETCH_FIELD )

Array
(
    [0] => Array
        (
            [name] => id
            [orgname] => id
            [table] => lerma
            [orgtable] => lerma
            [def] => 
            [db] => git
            [catalog] => def
            [max_length] => 3
            [length] => 11
            [charsetnr] => 63
            [flags] => 49667
            [type] => 3
            [decimals] => 0
        )

    [1] => Array
        (
            [name] => name
            [orgname] => name
            [table] => lerma
            [orgtable] => lerma
            [def] => 
            [db] => git
            [catalog] => def
            [max_length] => 29
            [length] => 196605
            [charsetnr] => 33
            [flags] => 4113
            [type] => 252
            [decimals] => 0
        )

    [2] => Array
        (
            [name] => num
            [orgname] => num
            [table] => lerma
            [orgtable] => lerma
            [def] => 
            [db] => git
            [catalog] => def
            [max_length] => 3
            [length] => 11
            [charsetnr] => 63
            [flags] => 36865
            [type] => 3
            [decimals] => 0
        )

)

fetch( Lerma :: MYSQL_FETCH_BIND )

Array
(
    [0] => 138
    [1] => Nouvu\Database\Lerma
    [2] => 111
)

fetch( Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN )

Nouvu\Database\Lerma

fetch( Lerma :: FETCH_COLUMN )

Nouvu\Database\Lerma

fetchall( Lerma :: FETCH_COLUMN )

Array
(
    [0] => Nouvu\Database\Lerma
    [1] => Nouvu\Database\ComponentFetch
    [2] => php7.4
    [3] => Database
    [4] => Nouvu\Database\Core
    [5] => InterfaceDriver
    [6] => Nouvu\Database\LermaStatement
)

fetch( Lerma :: FETCH_KEY_PAIR )

Array
(
    [138] => Nouvu\Database\Lerma
)

fetchall( Lerma :: FETCH_KEY_PAIR )

Array
(
    [111] => Database
    [222] => Nouvu\Database\Core
    [333] => Nouvu\Database\LermaStatement
)

fetchall( Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_NAMED )

Array
(
    [111] => Array
        (
            [0] => Nouvu\Database\Lerma
            [1] => Nouvu\Database\ComponentFetch
            [2] => php7.4
            [3] => Database
        )

    [222] => Nouvu\Database\Core
    [333] => Array
        (
            [0] => InterfaceDriver
            [1] => Nouvu\Database\LermaStatement
        )

)

fetchall( Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC )

Array
(
    [111] => Array
        (
            [Database] => name
        )

    [222] => Array
        (
            [Nouvu\Database\Core] => name
        )

    [333] => Array
        (
            [Nouvu\Database\LermaStatement] => name
        )

)

fetch( Lerma :: FETCH_FUNC )

138 - Nouvu\Database\Lerma - 111

fetchall( Lerma :: FETCH_FUNC )

Array
(
    [0] => 138 - Nouvu\Database\Lerma - 111
    [1] => 139 - Nouvu\Database\ComponentFetch - 111
    [2] => 140 - php7.4 - 111
    [3] => 141 - Database - 111
    [4] => 142 - Nouvu\Database\Core - 222
    [5] => 143 - InterfaceDriver - 333
    [6] => 144 - Nouvu\Database\LermaStatement - 333
)

fetchall( Lerma :: FETCH_UNIQUE )

Array
(
    [138] => Array
        (
            [name] => Nouvu\Database\Lerma
            [num] => 111
        )

    [139] => Array
        (
            [name] => Nouvu\Database\ComponentFetch
            [num] => 111
        )

    [140] => Array
        (
            [name] => php7.4
            [num] => 111
        )

    [141] => Array
        (
            [name] => Database
            [num] => 111
        )

    [142] => Array
        (
            [name] => Nouvu\Database\Core
            [num] => 222
        )

    [143] => Array
        (
            [name] => InterfaceDriver
            [num] => 333
        )

    [144] => Array
        (
            [name] => Nouvu\Database\LermaStatement
            [num] => 333
        )

)

fetchall( Lerma :: FETCH_GROUP )

Array
(
    [111] => Array
        (
            [0] => Array
                (
                    [id] => 138
                    [name] => Nouvu\Database\Lerma
                )

            [1] => Array
                (
                    [id] => 139
                    [name] => Nouvu\Database\ComponentFetch
                )

            [2] => Array
                (
                    [id] => 140
                    [name] => php7.4
                )

            [3] => Array
                (
                    [id] => 141
                    [name] => Database
                )

        )

    [222] => Array
        (
            [0] => Array
                (
                    [id] => 142
                    [name] => Nouvu\Database\Core
                )

        )

    [333] => Array
        (
            [0] => Array
                (
                    [id] => 143
                    [name] => InterfaceDriver
                )

            [1] => Array
                (
                    [id] => 144
                    [name] => Nouvu\Database\LermaStatement
                )

        )

)

fetchall( Lerma :: FETCH_GROUP | Lerma :: FETCH_COLUMN )

Array
(
    [111] => Array
        (
            [0] => Nouvu\Database\Lerma
            [1] => Nouvu\Database\ComponentFetch
            [2] => php7.4
            [3] => Database
        )

    [222] => Array
        (
            [0] => Nouvu\Database\Core
        )

    [333] => Array
        (
            [0] => InterfaceDriver
            [1] => Nouvu\Database\LermaStatement
        )

)

  ```
  
</details>

Create by [MouseZver](//php.ru/forum/members/40235)
