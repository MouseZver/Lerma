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
	'errMessage' => [
		'driver' => 'Error in driver selection: %s',
		'prepare' => [
			'items' => 'Argument 2 is empty',
			'vars' => 'Missing pseudo-variables in the request',
		],
		'statement' => [
			'hash' => 'Session ended by calling another',
			'keyName' => 'unrecognized key name in fetch_style argument',
			'columnCount' => [
				'only' => [
					1 => 'You only need to select one column',
					2 => 'You only need to select two columns',
				],
				'min' => 'Allowed number of selected columns at least two',
			],
			'bindResult' => 'For proper operation, do not use the rowСount method during sampling in the unbuffered result',
		],
		'connect' => [
			'mysql' => 'Error connect (%s) %s',
		],
	],
];
