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
			'password' => ''
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
			
		},
	],
	'errMessage' => [
		'driver' => 'Ошибка в выборе драйвера: %s',
		'prepare' => [
			'items' => 'Аргумент 2 пуст',
			'vars' => 'Отсутствуют в запросе псевдопеременные',
		],
		'statement' => [
			'hash' => 'Session ended by calling another',
			'keyName' => 'unrecognized key name in fetch_style argument',
			'columnCount' => [
				'only' => [
					1 => 'Требуется выбрать только одну колонку',
					2 => 'Требуется выбрать только две колонки',
				],
				'min' => 'Допустимое кол-во выбраных колонок не менее двух',
			],
			'bindResult' => 'Для правильной работы, откажитесь от использования метода rowCount, во время выборки в небуферизованном результате',
		],
		'connect' => [
			'mysql' => 'Error connect (%s) %s',
		],
	],
];