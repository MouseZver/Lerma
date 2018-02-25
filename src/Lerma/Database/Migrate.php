<?php

namespace Aero\Database;

use Aero;
use Aero\Supports\Lerma;
use Aero\Interfaces\Lerma\IDrivers;
use Exception AS Error;
use Throwable;

class Migrate extends LermaStatement
{
	private static $instance;					# Объект среды Lerma
	protected $driver;							# Объект подключенного драйвера
	protected $bind_result;

	/*
		- Выбор и загрузка драйвера для работы с базой данных
	*/
	protected function IDrivers( string $name ): Migrate
	{
		$this -> driver = require 'driver' . DIRECTORY_SEPARATOR . ( $Lerma = new $name ) -> driver . '.php';

		if ( !is_a ( $this -> driver, IDrivers::class ) )
		{
			throw new Error( 'Загруженный драйвер не соответсвует требованиям интерфейсу IDrivers' );
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
				self::$instance -> exceptionIDriver( $t );
			}
		}

		return self::$instance;
	}

	/*
		- Моем посуду
	*/
	protected function dead(): Migrate
	{
		$this -> bind_result = [];

		if ( $this -> statement !== null )
		{
				$this -> driver -> close();

				$this -> statement = null;
		}

		return $this;
	}

	/*
		- Определение запроса на форматирование строки
	*/
	protected static function query( $sql ): Migrate
	{
		self::instance() -> query = self::instance() -> dead() -> driver -> query( is_array ( $sql ) ? sprintf ( ...$sql ) : $sql );

		self::instance() -> driver -> isError();

		return self::instance();
	}

	/*
		- Создание переменных подготовленного запроса для данных с астрала
	*/
	protected function bind(): IDrivers
	{
		if ( $this -> statement !== null )
		{
			if ( empty ( $this -> bind_result ) )
			{
				for ( $i = 0; $i < $this -> driver -> countColumn(); $i++, $this -> bind_result[] = &${ 'result_' . $i } );

				$this -> driver -> bindResult( $this -> bind_result );
			}

			return $this -> driver;
		}

		throw new Error( 'Not bind result to query empty placeholders' );
	}

	protected function rowCount(): int
	{
		return $this -> driver -> rowCount();
	}

	/*
		- Стиль возвращаемого результата с одной строки
		- fetch_style - Идентификатор выбираемого стиля. Default Lerma::FETCH_NUM
		- fetch_argument - атрибут для совершения действий над данными
	*/
	protected function fetch( int $fetch_style = Lerma::FETCH_NUM, $fetch_argument = null )
	{
		switch ( $fetch_style )
		{
			/*
				-
			*/
			case Lerma::FETCH_NUM:
				return $this -> driver -> fetch( Lerma::FETCH_NUM );
			break;

			/*
				-
			*/
			case Lerma::FETCH_ASSOC:
				return $this -> driver -> fetch( Lerma::FETCH_ASSOC );
			break;

			/*
				-
			*/
			case Lerma::FETCH_OBJ:
				return $this -> driver -> fetch( Lerma::FETCH_OBJ );
			break;

			/*
				-
			*/
			case Lerma::FETCH_BIND:

			/*
				-
			*/
			case Lerma::FETCH_BIND | Lerma::FETCH_COLUMN:
				if ( !$this -> bind() -> fetch( Lerma::FETCH_BIND ) )
				{
					self::instance() -> driver -> isError( $this -> statement );

					return $this -> bind_result = false;
				}

				if ( $fetch_style === ( Lerma::FETCH_BIND | Lerma::FETCH_COLUMN ) )
				{
					if ( $this -> driver -> countColumn() !== 1 )
					{
						throw new Error( 'Требуется выбрать только одну колонку' );
					}

					return $this -> bind_result[0];
				}

				return $this -> bind_result;
			break;

			/*
				-
			*/
			case Lerma::FETCH_COLUMN:
				if ( $this -> driver -> countColumn() !== 1 )
				{
					throw new Error( 'Требуется выбрать только одну колонку' );
				}

				return $this -> driver -> fetch( Lerma::FETCH_NUM )[0];
			break;

			/*
				-
			*/
			case Lerma::FETCH_KEY_PAIR: # column1 => column2
				if ( $this -> driver -> countColumn() !== 2 )
				{
					throw new Error( 'Требуется выбрать только две колонки' );
				}

				if ( ( $items = $this -> driver -> fetch( Lerma::FETCH_NUM ) ) === null )
				{
					return null;
				}

				[ $key, $value ] = $items;

				return [ $key => $value ];
			break;

			/*
				-
			*/
			case Lerma::FETCH_FUNC:
				if ( !is_callable ( $fetch_argument ) )
				{
					throw new Error( 'Invalid argument2 is not type callable' );
				}

				if ( ( $items = $this -> driver -> fetch( Lerma::FETCH_NUM ) ) === null )
				{
					return null;
				}

				return $fetch_argument( ...$items );
			break;

			/*
				-
			*/
			case Lerma::FETCH_CLASS:

			/*
				-
			*/
			case Lerma::FETCH_CLASSTYPE:
				/* if ( !is_string ( $fetch_argument ) && Lerma::FETCH_CLASS === $fetch_style )
				{
					throw new Error( 'Invalid argument2 is not type string' );
				}
				elseif ( Lerma::FETCH_CLASSTYPE === $fetch_style && $this -> driver -> countColumn() < 2 )
				{
					throw new Error( 'Допустимое кол - во выбраных колонок: не менее двух' );
				}

				if ( ( $items = $this -> driver -> fetch( Lerma::FETCH_ASSOC ) ) === null )
				{
					return null;
				}

				$RefClass = ( new \ReflectionClass( ( Lerma::FETCH_CLASSTYPE === $fetch_style ?
					array_shift ( $items ) : $fetch_argument ) ) ) -> newInstanceWithoutConstructor();

				foreach ( $items AS $name => $item )
				{
					$RefClass -> $name = $item;
				}

				$RefClass -> __construct(); #&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

				return $RefClass; */
				throw new Error( 'Test...' );
			break;
			default:
				throw new Error( sprintf ( 'Invalid fetch_style %s is not switch', $fetch_style ) );
		}
	}

	/*
		- Стиль возвращаемого результата со всех строк
		- fetch_style - Идентификатор выбираемого стиля. Default Lerma::FETCH_NUM
		- fetch_argument - атрибут для совершения действий над данными
	*/
	protected function fetchAll( int $fetch_style = Lerma::FETCH_NUM, $fetch_argument = null ): array
	{
		switch ( $fetch_style )
		{
			/*
				-
			*/
			case Lerma::FETCH_NUM:
				return $this -> driver -> fetchAll( Lerma::FETCH_NUM );
			break;

			/*
				-
			*/
			case Lerma::FETCH_ASSOC:
				return $this -> driver -> fetchAll( Lerma::FETCH_ASSOC );
			break;

			/*
				-
			*/
			case Lerma::FETCH_OBJ:

			/*
				-
			*/
			case Lerma::FETCH_COLUMN:

			/*
				-
			*/
			case Lerma::FETCH_FUNC:

			/*
				-
			*/
			case Lerma::FETCH_CLASS:

			/*
				-
			*/
			case Lerma::FETCH_CLASSTYPE:
				$all = [];

				while ( $res = $this -> fetch( $fetch_style, $fetch_argument ) ) { $all[] = $res; }

				return $all;
			break;

			/*
				-
			*/
			case Lerma::FETCH_KEY_PAIR:

			/*
				-
			*/
			case Lerma::FETCH_KEY_PAIR | Lerma::FETCH_NAMED:
				if ( $this -> driver -> countColumn() !== 2 )
				{
					throw new Error( 'Требуется выбрать только две колонки' );
				}

				$all = [];

				while ( [ $a, $b ] = $this -> driver -> fetch( Lerma::FETCH_NUM ) )
				{
					if ( $fetch_style === ( Lerma::FETCH_KEY_PAIR | Lerma::FETCH_NAMED ) && isset ( $all[$a] ) )
					{
						if ( is_array ( $all[$a] ) )
						{
							$all[$a][] = $b;
						}
						else
						{
							$all[$a] = [ $all[$a], $b ];
						}
					}
					else
					{
						$all[$a] = $b;
					}
				}

				return $all;
			break;

			/*
				-
			*/
			case Lerma::FETCH_UNIQUE:

			/*
				-
			*/
			case Lerma::FETCH_CLASSTYPE | Lerma::FETCH_UNIQUE:
				if ( $this -> driver -> countColumn() < 2 )
				{
					throw new Error( 'Допустимое кол - во выбраных колонок не менее двух' );
				}

				$all = [];

				foreach ( $this -> driver -> fetchAll( Lerma::FETCH_ASSOC ) AS $items )
				{
					if ( ( Lerma::FETCH_CLASSTYPE | Lerma::FETCH_UNIQUE ) === $fetch_style )
					{
						$class = array_shift ( $items );

						$RefClass = ( $c = new \ReflectionClass( $class ) ) -> newInstanceWithoutConstructor();

						foreach ( $items AS $name => $item )
						{
							$RefClass -> $name = $item;
						}

						$RefClass -> __construct();

						$all[( $fetch_argument === true ? $c -> getShortName() : $class )] = $RefClass;
					}
					else
					{
						$all[array_shift ( $items )] = $items;
					}
				}

				return $all;
			break;

			/*
				-
			*/
			case Lerma::FETCH_GROUP:
				if ( $this -> driver -> countColumn() < 2 )
				{
					throw new Error( 'Допустимое кол - во выбраных колонок не менее двух' );
				}

				$all = [];

				foreach ( $this -> driver -> fetchAll( Lerma::FETCH_ASSOC ) AS $s )
				{
					$all[array_shift ( $s )][] = $s;
				}

				return $all;
			break;

			/*
				-
			*/
			/* case Lerma::FETCH_GROUP | Lerma::FETCH_COLUMN:
				if ( $this -> driver -> countColumn() !== 2 )
				{
					throw new Error( 'Требуется выбрать только две колонки' );
				}

				$all = [];

				foreach ( $this -> driver -> fetchAll( Lerma::FETCH_NUM ) AS $s )
				{
					$all[array_shift ( $s )][] = $s[0];
				}

				return $all;
			break; */
			default:
				throw new Error( sprintf ( 'Invalid fetch_style %s is not switch', $fetch_style ) );
		}
	}
}
