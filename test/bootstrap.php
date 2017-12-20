<?php

use Aero\Supports\Lerma;

require '../autoload.php';

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
	Lerma::FETCH_CLASSTYPE | Lerma::FETCH_UNIQUE
*/

# ------------------------

$table = 'lerma';

$sql = [ 'SELECT * FROM %s', $table ]; # or 'SELECT * FROM lerma'

$query = Lerma::query( $sql ) -> fetchAll( Lerma::FETCH_OBJ );

# ------------------------

$sql = [ [ 'SELECT * FROM %s WHERE id IN ( :id,?,?,? )', $table ], [ 3,9,81,':id'=>1 ] ];

$prepare = Lerma::prepare( ...$sql ) -> fetchAll( Lerma::FETCH_OBJ );

# ------------------------

print_r ( compact ( 'query', 'prepare' ) );