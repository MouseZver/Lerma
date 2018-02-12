<?php

use Aero\Supports\Lerma;

require 'autoload.php';

# ------------------------

/* 
fetch()
	FETCH_NUM
	FETCH_ASSOC
	FETCH_OBJ
	FETCH_BIND
	FETCH_BIND | FETCH_COLUMN
	FETCH_COLUMN
	FETCH_KEY_PAIR
	FETCH_FUNC
	FETCH_CLASS
	FETCH_CLASSTYPE

fetchAll()
	FETCH_NUM
	FETCH_ASSOC
	FETCH_OBJ
	FETCH_COLUMN
	FETCH_KEY_PAIR
	FETCH_KEY_PAIR | FETCH_NAMED
	FETCH_UNIQUE
	FETCH_GROUP
	FETCH_GROUP | FETCH_COLUMN
	FETCH_FUNC
	FETCH_CLASS
	FETCH_CLASSTYPE
	FETCH_CLASSTYPE | FETCH_UNIQUE
*/

# ------------------------

# Lerma::FETCH_NUM

$a = Lerma::query( 'SELECT * FROM lerma' ) -> fetch( Lerma::FETCH_NUM );
/*
array (
  0 => '138',
  1 => 'Aero\\test\\Aero',
  2 => '111',
)
*/

$b = Lerma::query( 'SELECT * FROM lerma' ) -> fetchAll( Lerma::FETCH_NUM );
/*
array (
  0 => 
  array (
    0 => '138',
    1 => 'Aero\\test\\Aero',
    2 => '111',
  ),
  1 => 
  array (
    0 => '139',
    1 => 'Lerma',
    2 => '111',
  ),
  2 => 
  array (
    0 => '140',
    1 => 'Migrate',
    2 => '111',
  ),
  3 => 
  array (
    0 => '141',
    1 => 'Database',
    2 => '111',
  ),
  4 => 
  array (
    0 => '142',
    1 => 'Configures',
    2 => '222',
  ),
  5 => 
  array (
    0 => '143',
    1 => 'Interfaces',
    2 => '333',
  ),
  6 => 
  array (
    0 => '144',
    1 => 'LermaDrivers',
    2 => '333',
  ),
)
*/

# Lerma::FETCH_ASSOC

$a = Lerma::query( 'SELECT * FROM lerma' ) -> fetch( Lerma::FETCH_ASSOC );
/*
array (
  'id' => '138',
  'name' => 'Aero\\test\\Aero',
  'num' => '111',
)
*/

$b = Lerma::query( 'SELECT * FROM lerma' ) -> fetchAll( Lerma::FETCH_ASSOC );
/*
array (
  0 => 
  array (
    'id' => '138',
    'name' => 'Aero\\test\\Aero',
    'num' => '111',
  ),
  1 => 
  array (
    'id' => '139',
    'name' => 'Lerma',
    'num' => '111',
  ),
  2 => 
  array (
    'id' => '140',
    'name' => 'Migrate',
    'num' => '111',
  ),
  3 => 
  array (
    'id' => '141',
    'name' => 'Database',
    'num' => '111',
  ),
  4 => 
  array (
    'id' => '142',
    'name' => 'Configures',
    'num' => '222',
  ),
  5 => 
  array (
    'id' => '143',
    'name' => 'Interfaces',
    'num' => '333',
  ),
  6 => 
  array (
    'id' => '144',
    'name' => 'LermaDrivers',
    'num' => '333',
  ),
)
*/

# Lerma::FETCH_OBJ

$a = Lerma::query( 'SELECT * FROM lerma' ) -> fetch( Lerma::FETCH_OBJ );
/*
stdClass::__set_state(array(
   'id' => '138',
   'name' => 'Aero\\test\\Aero',
   'num' => '111',
))
*/

$b = Lerma::query( 'SELECT * FROM lerma' ) -> fetchAll( Lerma::FETCH_OBJ );
/*
array (
  0 => 
  stdClass::__set_state(array(
     'id' => '138',
     'name' => 'Aero\\test\\Aero',
     'num' => '111',
  )),
  1 => 
  stdClass::__set_state(array(
     'id' => '139',
     'name' => 'Lerma',
     'num' => '111',
  )),
  2 => 
  stdClass::__set_state(array(
     'id' => '140',
     'name' => 'Migrate',
     'num' => '111',
  )),
  3 => 
  stdClass::__set_state(array(
     'id' => '141',
     'name' => 'Database',
     'num' => '111',
  )),
  4 => 
  stdClass::__set_state(array(
     'id' => '142',
     'name' => 'Configures',
     'num' => '222',
  )),
  5 => 
  stdClass::__set_state(array(
     'id' => '143',
     'name' => 'Interfaces',
     'num' => '333',
  )),
  6 => 
  stdClass::__set_state(array(
     'id' => '144',
     'name' => 'LermaDrivers',
     'num' => '333',
  )),
)
*/

# Lerma::FETCH_BIND

Only statement query

$a = Lerma::prepare( 'SELECT * FROM lerma WHERE ?', [1] ) -> fetch( Lerma::FETCH_BIND );
/*
array (
  0 => 138,
  1 => 'Aero\\test\\Aero',
  2 => 111,
)
*/

# Lerma::FETCH_BIND | Lerma::FETCH_COLUMN

Only statement query

$a = Lerma::prepare( 'SELECT name FROM lerma WHERE ?', [1] ) -> fetch( Lerma::FETCH_BIND | Lerma::FETCH_COLUMN );
/*
'Aero\\test\\Aero'
*/

# Lerma::FETCH_COLUMN

$a = Lerma::query( 'SELECT name FROM lerma' ) -> fetch( Lerma::FETCH_COLUMN );
/*
'Aero\\test\\Aero'
*/

$b = Lerma::query( 'SELECT name FROM lerma' ) -> fetchAll( Lerma::FETCH_COLUMN );
/*
array (
  0 => 'Aero\\test\\Aero',
  1 => 'Lerma',
  2 => 'Migrate',
  3 => 'Database',
  4 => 'Configures',
  5 => 'Interfaces',
  6 => 'LermaDrivers',
)
*/

# Lerma::FETCH_KEY_PAIR

$a = Lerma::query( 'SELECT num, name FROM lerma' ) -> fetch( Lerma::FETCH_KEY_PAIR );
/*
array (
  111 => 'Aero\\test\\Aero',
)
*/

Перезаписывает существующие ключи

$b = Lerma::query( 'SELECT num, name FROM lerma' ) -> fetchAll( Lerma::FETCH_KEY_PAIR );
/*
array (
  111 => 'Database',
  222 => 'Configures',
  333 => 'LermaDrivers',
)
*/

# Lerma::FETCH_KEY_PAIR | Lerma::FETCH_NAMED

$b = Lerma::query( 'SELECT num, name FROM lerma' ) -> fetchAll( Lerma::FETCH_KEY_PAIR | Lerma::FETCH_NAMED );
/*
array (
  111 => 
  array (
    0 => 'Aero\\test\\Aero',
    1 => 'Lerma',
    2 => 'Migrate',
    3 => 'Database',
  ),
  222 => 'Configures',
  333 => 
  array (
    0 => 'Interfaces',
    1 => 'LermaDrivers',
  ),
)
*/

# Lerma::FETCH_UNIQUE

$b = Lerma::query( 'SELECT * FROM lerma' ) -> fetchAll( Lerma::FETCH_UNIQUE );
/*
array (
  138 => 
  array (
    'name' => 'Aero\\test\\Aero',
    'num' => '111',
  ),
  139 => 
  array (
    'name' => 'Lerma',
    'num' => '111',
  ),
  140 => 
  array (
    'name' => 'Migrate',
    'num' => '111',
  ),
  141 => 
  array (
    'name' => 'Database',
    'num' => '111',
  ),
  142 => 
  array (
    'name' => 'Configures',
    'num' => '222',
  ),
  143 => 
  array (
    'name' => 'Interfaces',
    'num' => '333',
  ),
  144 => 
  array (
    'name' => 'LermaDrivers',
    'num' => '333',
  ),
)
*/

# Lerma::FETCH_GROUP

$b = Lerma::query( 'SELECT num, id, name FROM lerma' ) -> fetchAll( Lerma::FETCH_GROUP );
/*
array (
  111 => 
  array (
    0 => 
    array (
      'id' => '138',
      'name' => 'Aero\\test\\Aero',
    ),
    1 => 
    array (
      'id' => '139',
      'name' => 'Lerma',
    ),
    2 => 
    array (
      'id' => '140',
      'name' => 'Migrate',
    ),
    3 => 
    array (
      'id' => '141',
      'name' => 'Database',
    ),
  ),
  222 => 
  array (
    0 => 
    array (
      'id' => '142',
      'name' => 'Configures',
    ),
  ),
  333 => 
  array (
    0 => 
    array (
      'id' => '143',
      'name' => 'Interfaces',
    ),
    1 => 
    array (
      'id' => '144',
      'name' => 'LermaDrivers',
    ),
  ),
)
*/

# Lerma::FETCH_GROUP | Lerma::FETCH_COLUMN

$b = Lerma::query( 'SELECT num, name FROM lerma' ) -> fetchAll( Lerma::FETCH_GROUP | Lerma::FETCH_COLUMN );
/*
array (
  111 => 
  array (
    0 => 'Aero\\test\\Aero',
    1 => 'Lerma',
    2 => 'Migrate',
    3 => 'Database',
  ),
  222 => 
  array (
    0 => 'Configures',
  ),
  333 => 
  array (
    0 => 'Interfaces',
    1 => 'LermaDrivers',
  ),
)
*/

# Lerma::FETCH_FUNC

$a = Lerma::query( 'SELECT * FROM lerma' ) -> fetch( Lerma::FETCH_FUNC, function ( ...$res ) 
{ 
	return implode ( ', ', $res );
} );
/*
'138, Aero\\test\\Aero, 111'
*/

$b = Lerma::query( 'SELECT * FROM lerma' ) -> fetchAll( Lerma::FETCH_FUNC, function ( ...$res ) 
{ 
	return implode ( ', ', $res );
} );
/*
array (
  0 => '138, Aero\\test\\Aero, 111',
  1 => '139, Lerma, 111',
  2 => '140, Migrate, 111',
  3 => '141, Database, 111',
  4 => '142, Configures, 222',
  5 => '143, Interfaces, 333',
  6 => '144, LermaDrivers, 333',
)
*/

# Lerma::FETCH_CLASS
# Lerma::FETCH_CLASSTYPE
# Lerma::FETCH_CLASSTYPE | Lerma::FETCH_UNIQUE

# test...























exit;

$table = 'lerma';

$sql = [ 'SELECT * FROM %s LIMIT 7', $table ]; # or 'SELECT * FROM lerma'

$query = Lerma::query( $sql ) -> fetchAll( Lerma::FETCH_UNIQUE );

$a = [];

foreach ( $query as $b )
{
	if ( $b['num'] == 222 )
	{
		$a[] = 555;
	}
	else
	{
		$a[] = $b;
	}
}

# ------------------------
/* 
$sql = [ [ 'SELECT * FROM %s WHERE id IN ( :id,?,?,? )', $table ], [ 3,9,81,':id'=>1 ] ];

$prepare = Lerma::prepare( ...$sql ) -> fetchAll( Lerma::FETCH_UNIQUE );
*/
# ------------------------

Lerma::query( 'DELETE FROM lerma' );
Lerma::prepare( 'INSERT INTO lerma ( name, num ) VALUES ( ?, ? )', $query ); # $a
