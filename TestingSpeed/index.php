<?php

declare ( strict_types = 1 );

error_reporting ( E_ALL );

set_time_limit ( 20 );

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ url-source: http://github.com/MouseZver/Lerma
	@ php-version 7.4
*/

use Nouvu\Config\Config;

session_start ();

require dirname ( __FILE__, 2 ) . '/vendor/autoload.php';

if ( ! empty ( $_SESSION['speed'] ) )
{
	require 'PDO.php';
	
	$test = new TestingSpeed\PDO( 'mysql:host=127.0.0.1;port=3306;dbname=git;charset=utf8', 'root', 'root', [
		TestingSpeed\PDO :: ATTR_EMULATE_PREPARES => true
	] );
	
	unset ( $_SESSION['speed'] );
}
else
{
	require 'Lerma.php';
	
	$test = new TestingSpeed\Lerma( 'mysql:host=127.0.0.1;port=3306;dbname=git;charset=utf8;username=root', 
		fn( Config $config ): void => $config -> set( 'ShemaActiveFun.replaceHolders.mysql', fn( &$a ) => $a = true
	);
	
	$_SESSION['speed'] = 1;
}

$test -> beginTransaction();

$test -> insert( file_get_contents ( '100000_items.txt' ) );



$test -> query( 'TRUNCATE TABLE `testingspeed`' );

#END