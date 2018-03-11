<?php

error_reporting ( E_ALL );

use Aero\Supports\Lerma;

require 'autoload.php';

$query = Lerma::query( 'SELECT id, num, name FROM `lerma`' );

$result = $query -> fetchAll( Lerma::FETCH_FUNC, function ( ...$items )
{
	return sprintf ( 'id: %d | num: %d | name: %s', ...$items );
} );

printf ( '<pre>%s</pre>', implode ( PHP_EOL, $result ) );

/* 
id: 138 | num: 111 | name: Aero\test\Aero
id: 139 | num: 111 | name: Lerma
id: 140 | num: 111 | name: Migrate
id: 141 | num: 111 | name: Database
id: 142 | num: 222 | name: Configures
id: 143 | num: 333 | name: Interfaces
id: 144 | num: 333 | name: LermaDrivers
 */

$query = Lerma::query( 'SELECT * FROM `lerma`' );

print_r ( $query -> fetchAll( Lerma::FETCH_FIELD, 'orgname' ) );
print_r ( $query -> fetchAll( Lerma::FETCH_ASSOC ) );