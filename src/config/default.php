<?php

return [
	'dsn_default' => 'mysql',
	'drivers' => [
		'mysql' => [
			'namespace' => Nouvu\Database\LermaExt\Mysql :: class,
			'dbname' => 'git',
			'host' => '127.0.0.1',
			'port' => 3306,
			'charset' => 'utf8',
			'username' => 'root',
			'password' => 'root'
		],
		'sqlite' => [
			'namespace' => Nouvu\Database\LermaExt\Sqlite :: class,
			'db' => 'lerma.db'
		],
	],
	'ShemaActiveFun' => [
		'replaceHolders' => [ 
			'mysql' => true,
			'sqlite' => true
		]
	],
	'StartingDriver' => false,
	'ShemaStartingDriver' => [
		'sqlite' => static function ( Nouvu\Database\Lerma $lrm, Nouvu\Config\Config $config )
		{
			//$lrm -> query( 'SET SESSION group_concat_max_len = 1024000' );
		},
		'mysql' => static function ( Nouvu\Database\Lerma $lrm, Nouvu\Config\Config $config )
		{
			//$lrm -> query( 'SET SESSION group_concat_max_len = 1024000' );
		}
	],
	'ShemaExceptionConnect' => [
		'mysql' => static function ( mysqli_sql_exception $mysqli_sql_exception )
		{
			throw $mysqli_sql_exception;
		},
	],
];
