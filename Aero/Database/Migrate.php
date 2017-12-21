<?php

namespace Aero\Database;

use Aero;
use Aero\Supports\Lerma;
use Exception;
use Throwable;

class Migrate
{
	private static $instance;					# Объект среды Lerma
	protected $migrate;							# Options connect
	protected $driver;							# Объект подключенного драйвера
	protected $matches = [];					# Placeholders
	protected $pattern = '/(\?|:[a-z]{1,})/';	# Поиск плейсхолдеров в запросе
	
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
		
		if ( hash_file ( 'md5', __DIR__ . DIRECTORY_SEPARATOR . $driverPath ) !== Lerma::VERSION[$Lerma -> driver] )
		{
			throw new Exception( sprintf ( 'Хеш драйвера %s изменен', $Lerma -> driver ) );
		}
		
		$this -> driver = require $driverPath;
		
		if ( !is_a ( $this -> driver, Aero\Interfaces\Lerma\IDrivers::class ) )
		{
			throw new Exception( 'Загруженный драйвер не соответсвует требованиям интерфейсу IDrivers' );
		}
		
		return $this;
	}
	
	/*
		- Запуск ядра
	*/
	protected static function instance(): Migrate
	{
		if ( self::$instance === NULL )
		{
			try
			{
				self::$instance = ( new static ) -> IDrivers( Aero\Configures\Lerma::class );
			}
			catch ( Throwable $t )
			{
				exit ( $t -> getMessage() );
			}
		}
		
		if ( !empty ( self::$instance -> matches ) )
		{
			self::$instance -> matches = [];
		}
		
		return self::$instance;
	}
	
	/*
		- Определение запроса на форматирование строки
	*/
	protected static function query( $sql )
	{
		return self::instance() -> driver -> query( is_array ( $sql ) ? sprintf ( ...$sql ) : $sql );
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
		
		self::instance() -> driver -> prepare( self::$instance -> replaceHolders( $sql ) );
		
		if ( self::$instance -> isMulti( $execute ) )
		{
			try
			{
				self::$instance -> driver -> begin_transaction();
				
				self::$instance -> multiExecute( $execute );
				
				self::$instance -> driver -> commit();
			}
			catch ( Throwable $t )
			{
				self::$instance -> driver -> rollback();
				
				exit ( sprintf ( '<pre>IDriver: %s' . PHP_EOL . '%s</pre>', $t -> getMessage(), $t -> getTraceAsString() ) );
			}
		}
		else
		{
			self::$instance -> execute( $execute );
		}
		
		return self::$instance -> driver;
	}
	
	/*
		- Посылаем данные в астрал
	*/
	protected function execute( array $execute )
	{
		self::$instance -> driver -> execute( !empty ( $this -> matches ) ? self::$instance -> executeHolders( $execute ) : $execute );
	}
	
	/*
		- Простая подстановка элементов в запрос
		- и
		- Поиск ':', замена placeholders на '?'
	*/
	protected function replaceHolders( $sql ): string
	{
		$sql = ( is_array ( $sql ) ? sprintf ( ...$sql ) : $sql );
		
		if ( strpos ( $sql, ':' ) !== false )
		{
			preg_match_all ( $this -> pattern, $sql, $matches );
			
			$this -> matches = $matches[1];
			
			$sql = strtr ( $sql, array_fill_keys ( $this -> matches, '?' ) );
		}
		
		return $sql;
	}
	
	/*
		- Реформирование данных в массиве по найденным placeholders
	*/
	protected function executeHolders( array $execute ): array
	{
		$new = [];
		
		foreach ( $this -> matches as $plaseholder )
		{
			if ( $plaseholder === '?' )
			{
				$new[] = array_shift ( $execute );
			}
			else
			{
				if ( isset ( $new[$plaseholder] ) )
				{
					$new[] = $new[$plaseholder];
				}
				else
				{			
					$new[$plaseholder] = $execute[$plaseholder] ?? null;
					
					unset ( $execute[$plaseholder] );
				}
			}
		}

		return $new;
	}
	
	/*
		- Проверяем данные на мульти-запрос
	*/
	protected function isMulti( array $array ): bool
	{
		if ( !is_array ( current ( $array ) ) )
		{
			foreach ( $array AS $items )
			{
				if ( is_array ( $items ) )
				{
					throw new Exception( 'Ошибка в добавлении, запрос не выполнен. Ожидался не многомерный массив.' );
				}
			}
			
			return false;
		}
		
		return true;
	}
	
	/*
		- Многократное добавление данных подготовленного запроса в бд
	*/
	protected function multiExecute( array $executes )
	{
		foreach ( $executes AS $s => $execute )
		{
			if ( !is_array ( $execute ) )
			{
				throw new Exception( 'Ошибка в мульти добавлении, ожидался массив. Ступень: ' . $s );
			}
			
			self::$instance -> execute( $execute );
		}
	}
}