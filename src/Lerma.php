<?php

declare ( strict_types = 1 );

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ url-source: http://github.com/MouseZver/Lerma
	@ php-version 7.4
*/

namespace Nouvu\Database;

use Error;
use Nouvu\Config\Config;

class Lerma extends Core
{
	public const
		FETCH_NUM			= 1,
		FETCH_ASSOC			= 2,
		FETCH_OBJ			= 4,
		MYSQL_FETCH_BIND	= 663,
		FETCH_COLUMN		= 265,
		FETCH_KEY_PAIR		= 307,
		FETCH_NAMED			= 173,
		FETCH_UNIQUE		= 333,
		FETCH_GROUP			= 428,
		FETCH_FUNC			= 586,
		MYSQL_FETCH_FIELD	= 343;
	
	private string $default_config = 'def.conf.php';
	
	protected Config $config;
	
	protected InterfaceDriver $InterfaceDriver;
	
	protected string $driver;
	
	private bool $inTransaction = false;
	
	public int $hash;
	
	public function __construct( string $dsn = null, callable $callable = null )
	{
		$this -> config = new Config( include_once $this -> default_config, '.' );
		
		if ( is_callable ( $callable ) )
		{
			$callable( $this -> config );
		}
		
		$this -> parseDsn( $dsn ?? $this -> config -> get( 'dsn_default' ) );
		
		$this -> connect( $this, $this -> config, $this -> driver );
		
		if ( $this -> config -> get( 'StartingDriver' ) )
		{
			$this -> config -> get( "ShemaStartingDriver.{$this -> driver}" )( $this, $this -> config );
		}
	}
	
	private function parseDsn( string $dsn ): void
	{
		if ( ( $n = strpos ( $dsn, ':' ) ) !== false )
		{
			$this -> driver = substr ( $dsn, 0, $n++ );
			
			$this -> inDriverName();
			
			parse_str ( $db = strtr ( substr ( $dsn, $n ), ';', '&' ), $get );
			
			foreach ( $get AS $key => $item )
			{
				if ( ! is_null ( $this -> config -> get( "drivers.{$this -> driver}.{$key}" ) ) )
				{
					$this -> config -> set( "drivers.{$this -> driver}.{$key}", fn( &$a ) => $a = $item );
				}
				elseif ( !empty ( $key ) && empty ( $item ) )
				{
					$this -> config -> set( "drivers.{$this -> driver}.db", fn( &$a ) => $a = $db );
				}
			}
		}
		else
		{
			$this -> driver = $dsn;
			
			$this -> inDriverName();
		}
	}
	
	private function inDriverName(): void
	{
		if ( is_null ( $this -> config -> get( "drivers.{$this -> driver}" ) ) )
		{
			throw new Error( sprintf ( $this -> config -> get( 'errMessage.driver' ), $this -> driver ) );
		}
	}
	
	private function connect( ...$a ): void
	{
		$ext = $this -> config -> get( "drivers.{$this -> driver}.namespace" );
		
		$this -> InterfaceDriver = new $ext( ...$a );
	}
	
	public function prepare( $sql, array $items = [] ): LermaStatement
	{
		$this -> InterfaceDriver -> close();
		
		$sql = $this -> replaceHolders( is_array ( $sql ) ? sprintf ( ...$sql ) : $sql );
		
		if ( strpbrk ( $sql, '?:' ) === false )
		{
			throw new Error( $this -> config -> get( 'errMessage.prepare.vars' ) );
		}
		
		try
		{
			$this -> InterfaceDriver -> prepare( $sql );
			
			$this -> InterfaceDriver -> isError();
			
			reset ( $items );
			
			$this -> execute( is_array ( current ( $items ) ) ? $items : [ $items ] );
			
			return new LermaStatement( $this, $this -> InterfaceDriver, $this -> config );
		}
		catch ( Error $e )
		{
			$this -> rollBack();
			
			throw $e;
		}
	}
	
	public function query( $sql ): LermaStatement
	{
		$this -> InterfaceDriver -> close();
		
		$this -> InterfaceDriver -> query( is_array ( $sql ) ? sprintf ( ...$sql ) : $sql );
		
		$this -> InterfaceDriver -> isError();
		
		return new LermaStatement( $this, $this -> InterfaceDriver, $this -> config );
	}
	
	public function rollBack( ...$rollback ): bool
	{
		if ( $this -> inTransaction )
		{
			return $this -> connect -> rollback( ...$rollback );
		}
		
		return false;
	}
	
	public function beginTransaction( ...$rollback ): bool
	{
		if ( $this -> inTransaction )
		{
			return false;
		}
		
		$this -> inTransaction = true;
		
		return $this -> connect -> beginTransaction( ...$rollback );
	}
	
	public function commit( ...$commit ): bool
	{
		if ( $this -> inTransaction )
		{
			$this -> inTransaction = false;
			
			return $this -> connect -> commit( ...$commit );
		}
		
		return false;
	}
	
	public static function getAvailableDrivers(): array
	{
		return array_keys ( ( include_once $this -> default_config )['drivers'] );
	}
	
	public function InsertID(): int
	{
		return $this -> connect -> InsertID();
	}
}
# END