<?php

declare ( strict_types = 1 );

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ url-source: http://github.com/MouseZver/Lerma
	@ php-version 8.0
*/

namespace Nouvu\Database;

use Error;
use Nouvu\Config\Config;

final class Lerma extends Core implements InterfaceLerma
{
	protected string $default_config = 'config/default.php';
	
	protected Config $config;
	
	protected InterfaceDriver $InterfaceDriver;
	
	protected string $driver;
	
	protected bool $inTransaction = false;
	
	public int $hash;
	
	public function __construct ( string | array $dsn = null, callable $callable = null )
	{
		$this -> config = new Config( include $this -> default_config, '.' );
		
		if ( is_callable ( $callable ) )
		{
			$callable( $this -> config );
		}
		
		if ( is_array ( $dsn ) )
		{
			$dsn = sprintf ( ...$dsn );
		}
		
		$this -> parseDsn( $dsn ?? $this -> config -> get( 'dsn_default' ) );
		
		$this -> connect();
		
		if ( $this -> config -> get( 'StartingDriver' ) )
		{
			$this -> config -> get( "ShemaStartingDriver.{$this -> driver}" )( $this, $this -> config );
		}
	}
	
	public function prepare( string | array $sql, array $items = null ): LermaStatement
	{
		$this -> InterfaceDriver -> close();
		
		$sql = $this -> replaceHolders( is_array ( $sql ) ? sprintf ( ...$sql ) : $sql );
		
		// full :var1 :var_N and no replaceHolders ?
		if ( strpbrk ( $sql, '?:' ) === false )
		{
			throw new RequestException( code: 111 );
		}
		
		try
		{
			$this -> InterfaceDriver -> prepare( $sql );
			
			$this -> InterfaceDriver -> isError();
			
			if ( ! is_null ( $items ) )
			{
				reset ( $items );
				
				$this -> binding( is_array ( current ( $items ) ) ? $items : [ $items ] );
			}
			
			return new LermaStatement( $this, $this -> InterfaceDriver, $this -> config );
		}
		catch ( Error $e )
		{
			$this -> rollBack();
			
			throw $e;
		}
	}
	
	public function execute( array $items ): int | InterfaceLerma
	{
		reset ( $items );
		
		return $this -> binding( 
			is_array ( current ( $items ) ) ? $items : [ $items ] 
		);
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
			return $this -> InterfaceDriver -> rollback( ...$rollback );
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
		
		return $this -> InterfaceDriver -> beginTransaction( ...$rollback );
	}
	
	public function commit( ...$commit ): bool
	{
		if ( $this -> inTransaction )
		{
			$this -> inTransaction = false;
			
			return $this -> InterfaceDriver -> commit( ...$commit );
		}
		
		return false;
	}
	
	public function InsertID(): int
	{
		return $this -> InterfaceDriver -> InsertID();
	}
	
	public static function getAvailableDrivers(): array
	{
		return array_keys ( ( include $this -> default_config )['drivers'] );
	}
}
