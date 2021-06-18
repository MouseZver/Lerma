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

class Core
{
	protected function parseDsn( string $dsn ): void
	{
		if ( ( $n = strpos ( $dsn, ':' ) ) !== false )
		{
			$this -> driver = substr ( $dsn, 0, $n++ );
			
			$this -> inDriverName();
			
			parse_str ( $db = strtr ( substr ( $dsn, $n ), [ ';' => '&', '+' => '%2B' ] ), $get );
			
			foreach ( $get AS $key => $item )
			{
				if ( ! is_null ( $this -> config -> get( "drivers.{$this -> driver}.{$key}" ) ) )
				{
					$this -> config -> set( "drivers.{$this -> driver}.{$key}", fn( &$a ) => $a = $item );
				}
				else if ( ! empty ( $key ) && empty ( $item ) )
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
	
	protected function inDriverName(): void
	{
		if ( is_null ( $this -> config -> get( "drivers.{$this -> driver}" ) ) )
		{
			throw new RequestException( $this -> driver, 100 );
		}
	}
	
	protected function connect(): void
	{
		$ext = $this -> config -> get( "drivers.{$this -> driver}.namespace" );
		
		$this -> InterfaceDriver = new $ext( $this, $this -> config, $this -> driver );
	}
	
	protected function binding( array $items ): int | Lerma
	{
		$this -> InterfaceDriver -> binding( $items );
		
		$this -> InterfaceDriver -> isError();
		
		return $this -> InterfaceDriver -> columnCount() ?: $this;
	}
	
	protected function replaceHolders( string $sql ): string
	{
		if ( strpos ( $sql, ':' ) !== false && $this -> config -> get( "ShemaActiveFun.replaceHolders.{$this -> driver}" ) )
		{
			preg_match_all ( '/(\?|:[a-z]{1,})/i', $sql, $matches );
			
			$sql = strtr ( $sql, array_fill_keys ( $this -> matches = $matches[1], '?' ) );
		}
		else
		{
			$this -> matches = [];
		}
		
		return $sql;
	}
	
	public function executeHolders( array $execute, int $keys = 0 ): array
	{
		$new = [];
		
		$id = 0;
		
		// [ ':test', '?', '?', ':id', '?' ]
		foreach ( $this -> matches as $key => $placeholders )
		{
			if ( $placeholders == '?' )
			{
				$new[$key + $keys] = $execute[$id++];
			}
			else
			{
				$new[$key + $keys] = $execute[substr ( $placeholders, 1 )];
			}
		}
		
		if ( empty ( $this -> matches ) && ! empty ( $keys ) && ! empty ( $execute ) )
		{
			return array_combine ( range ( $keys, array_key_last ( $execute ) + $keys ), $execute );
		}
		
		return $new ?: $execute;
	}
}
