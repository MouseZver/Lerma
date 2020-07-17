<?php

declare ( strict_types = 1 );

error_reporting ( E_ALL );

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ url-source: http://github.com/MouseZver/Lerma
	@ php-version 7.4
*/

use Nouvu\Database\Lerma;

require dirname ( __FILE__, 2 ) . '/vendor/autoload.php';

require 'TestingMethods.php';

$test = new TestingMethods( 'mysql' );

$keys = [
	Lerma :: FETCH_NUM 									=> 'Lerma :: FETCH_NUM',
	Lerma :: FETCH_ASSOC 								=> 'Lerma :: FETCH_ASSOC',
	Lerma :: FETCH_OBJ 									=> 'Lerma :: FETCH_OBJ',
	Lerma :: MYSQL_FETCH_FIELD 							=> 'Lerma :: MYSQL_FETCH_FIELD',
	Lerma :: MYSQL_FETCH_BIND 							=> 'Lerma :: MYSQL_FETCH_BIND',
	Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN 	=> 'Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN',
	Lerma :: FETCH_COLUMN 								=> 'Lerma :: FETCH_COLUMN',
	Lerma :: FETCH_KEY_PAIR 							=> 'Lerma :: FETCH_KEY_PAIR',
	Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_NAMED 		=> 'Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_NAMED',
	Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC 		=> 'Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC',
	Lerma :: FETCH_FUNC 								=> 'Lerma :: FETCH_FUNC',
	Lerma :: FETCH_UNIQUE 								=> 'Lerma :: FETCH_UNIQUE',
	Lerma :: FETCH_GROUP 								=> 'Lerma :: FETCH_GROUP',
	Lerma :: FETCH_GROUP | Lerma :: FETCH_COLUMN 		=> 'Lerma :: FETCH_GROUP | Lerma :: FETCH_COLUMN',
];

foreach ( $test -> getFetches() AS $style => $methods )
{
	foreach ( $methods AS $all => $method )
	{
		$result = $test -> $method( $style );
		
		printf ( '<div><b>fetch%s( %s )</b><pre>%s</pre></div>', $all ?: '', $keys[$style], print_r ( $result, true ) );
	}
}
#END