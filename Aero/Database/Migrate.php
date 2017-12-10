<?php

namespace Aero\Database;

use Aero;
use Exception;
use Throwable;

class Migrate
{
	private static $instance;
	protected $migrate;
	protected $driver;
	
	/*
		- Выбор и загрузка драйвера для работы с базой данных
	*/
	protected function IDrivers( string $name ): Migrate
	{
		$this -> migrate = ( $Lerma = new $name() ) -> migrate;
		
		$driverPath = 'driver' . DIRECTORY_SEPARATOR . $Lerma -> driver . '.php';
		
		if ( !file_exists ( __DIR__ . DIRECTORY_SEPARATOR . $driverPath ) )
		{
			throw new Exception( 'Драйвер Лермы не найден. ' . $driverPath );
		}
		
		$this -> driver = require $driverPath;
		
		return $this;
	}
	
	/*
		- Запуск ядра
	*/
	protected static function instance(): Migrate
	{
		if ( self::$instance === NULL )
		{
			self::$instance = ( new static ) -> IDrivers( Aero\Configures\Lerma::class );
		}
		
		return self::$instance;
	}
	
	/*
		- Определение запроса на форматирование строки
	*/
	protected static function query( $sql )
	{
		return ( $static = self::instance() ) -> driver -> query( is_array ( $sql ) ? sprintf ( ...$sql ) : $sql );
	}
	
	/*
		- Определение подготовленного запроса на форматирование строки
	*/
	protected static function prepare( $sql, array $execute = [] )
	{
		if ( empty ( $execute ) )
		{
			throw new Exception( 'Значения для подготовления пусты' );
		}
		
		( $static = self::instance() ) -> driver -> prepare( is_array ( $sql ) ? sprintf ( ...$sql ) : $sql );
		
		if ( $static -> isMulti( $execute ) )
		{
			try
			{
				$static -> driver -> begin_transaction();
				
				$static -> multiExecute( $execute );
				
				$static -> driver -> commit();
			}
			catch ( Throwable $Throwable )
			{
				$static -> driver -> rollback();
				
				exit ( $Throwable -> getMessage() );
			}
		}
		else
		{
			$static -> driver -> execute( $execute );
		}
		
		return $static -> driver;
	}
	
	/*
		- Проверяем данные на мульти-запрос
	*/
	protected function isMulti( array $array )
	{
		if ( is_array ( current ( $array ) ) )
		{
			foreach ( $array AS $items )
			{
				if ( !is_array ( $items ) )
				{
					throw new Exception( 'Ошибка в мульти добавлении, запрос не выполнен. Ожидался полный многомерный массив.' );
				}
			}
			
			return TRUE;
		}
		else
		{
			foreach ( $array AS $items )
			{
				if ( is_array ( $items ) )
				{
					throw new Exception( 'Ошибка в добавлении, запрос не выполнен. Ожидался не многомерный массив.' );
				}
			}
			
			return FALSE;
		}
	}
	
	/*
		- Многократное добавление данных подготовленного запроса в бд
	*/
	protected function multiExecute( array $executes )
	{
		foreach ( $executes AS $execute )
		{
			self::$instance -> driver -> execute( $execute );
		}
	}
}