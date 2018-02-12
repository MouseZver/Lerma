<?php

namespace Aero\Configures;

class Lerma
{
	private const USER = 'root';
	private const PASSWORD = '';
	
	# Назначение драйвера для подключения базы данных
	public $driver = 'mysqli';
	
	# Параметры для драйвера mysqli
	public $mysqli = [
		'host' => '127.0.0.1',
		'user' => self::USER,
		'password' => self::PASSWORD,
		'dbname' => 'single',
		'port' => 3306
	];
};