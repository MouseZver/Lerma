<?php

use Nouvu\Database\Lerma;
use Nouvu\Database\Exception\LermaException;

use function Nouvu\Database\Helpers\{ debug, connect };

require dirname ( __DIR__, 2 ) . '/init.php';

require 'LermaResponseTest.php';

$mode = [
	1 => 'Lerma :: FETCH_NUM',
	2 => 'Lerma :: FETCH_ASSOC',
	4 => 'Lerma :: FETCH_OBJ',
	265 => 'Lerma :: FETCH_COLUMN',
	586 => 'Lerma :: FETCH_FUNC',
	307 => 'Lerma :: FETCH_KEY_PAIR',
	891 => 'Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC',
	333 => 'Lerma :: FETCH_UNIQUE',
	428 => 'Lerma :: FETCH_GROUP',
	429 => 'Lerma :: FETCH_GROUP | Lerma :: FETCH_COLUMN',
	343 => 'Lerma :: MYSQL_FETCH_FIELD',
	663 => 'Lerma :: MYSQL_FETCH_BIND',
	927 => 'Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN'
];

$test = new \LermaResponseTest( 'github_test', $lerma );

foreach ( Lerma :: MODE AS $constId => $listActions )
{
	foreach ( $listActions AS $isAll => $action )
	{
		echo $mode[$constId] . ( empty ( $isAll ) ? ' ( fetch )' : ' ( fetchAll )' ) . PHP_EOL;
		
		try
		{
			//echo json_encode ( $test -> $action( $constId ), 480 );
			print_r ( $test -> $action( $constId ) );
		}
		catch ( LermaException $e )
		{
			echo $e -> getMessage();
		}
		
		echo str_repeat ( PHP_EOL, 4 );
	}
}

exit;

$connect = connect( $lerma );// -> get();

$connect -> prepare( 'SELECT * FROM `github_test` where ?' );

$connect -> binding( [ [ 1 ] ] );

$res = [ &$a, &$b, &$c, &$d, &$e ];

$connect -> bindResult( $res );

/*foreach ( $connect -> fetch( Lerma :: MYSQL_FETCH_BIND ) AS $bool )
{
	var_dump ( $res );
	
	var_dump ( $bool );
}*/



while ( ( $data = $connect -> fetch( Lerma :: MYSQL_FETCH_BIND ) ) -> current() )
{
	var_dump ( $res );
}


//$lerma -> prepare( 'SELECT * FROM `github_test` where ?', [ 1 ] );





//echo json_encode ( debug(), 480 );