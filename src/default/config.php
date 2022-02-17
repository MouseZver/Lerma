<?php

use Nouvu\Config\Config;
use Nouvu\Database\Lerma;
use Nouvu\Database\Modules;

return [
	'dsn_default' => 'mysql',
	'drivers' => [
		'mysql' => [
			'module' => Modules\MySQLi :: class,
			'dbname' => 'test',
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
	/*'StartingDriver' => false,
	'ShemaStartingDriver' => [
		'sqlite' => static function ( Nouvu\Database\Lerma $lrm, Nouvu\Config\Config $config )
		{
			//$lrm -> query( 'SET SESSION group_concat_max_len = 1024000' );
		},
		'mysql' => static function ( Nouvu\Database\Lerma $lrm, Nouvu\Config\Config $config )
		{
			//$lrm -> query( 'SET SESSION group_concat_max_len = 1024000' );
		}
	],*/
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
];
