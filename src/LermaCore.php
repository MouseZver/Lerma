<?php

declare ( strict_types = 1 );

namespace Nouvu\Database;

use Nouvu\Database\Exception\LermaException;

use function Nouvu\Database\Helpers\{ config };

class LermaCore
{
	protected const BINDCHR = '\?|[:][a-zA-Z][a-zA-Z0-9_]+';
	
	protected string $currentExtension;
	
	private ModuleInterface $connect;

	protected function parseDsn( string $dsn ): void
	{
		if ( ( $n = strpos ( $dsn, ':' ) ) !== false )
		{
			$this -> currentExtension = substr ( $dsn, 0, $n++ );
			
			if ( is_null ( DriverEnum :: tryFrom( $this -> currentExtension ) ) )
			{
				throw LermaException( "Unknown driver name \"{$this -> currentExtension}\"" );
			}
            
			parse_str ( $db = strtr ( substr ( $dsn, $n ), [ ';' => '&', '+' => '%2B' ] ), $get );

			foreach ( $get AS $key => $value )
			{
				if ( ! config() -> has( "drivers.{$this -> currentExtension}.{$key}" ) )
				{
					throw LermaException( "Invalid name '{$key}' in dsn construction." );
				}

				config() -> set( "drivers.{$this -> currentExtension}.{$key}", $value );
			}
		}
		else
		{
			$this -> currentExtension = $dsn;
		}
	}
	
	public function connect(): ModuleInterface
	{
		return $this -> connect ??= new ( config( "drivers.{$this -> currentExtension}.module" ) )( $this );
	}

	protected function binding( array $items ): void
	{
		$this -> connect() -> binding( $items );
	
		$this -> connect() -> isError();
	}
	
	protected function replaceHolders( string &$sql ): void
	{
		$this -> matches = [];
		
		if ( config( 'namedPlaceholders' ) && preg_match_all ( '/' . self :: BINDCHR . '/', $sql, $matches ) )
		{
			$sql = strtr ( $sql, array_fill_keys ( $this -> matches = $matches[0], '?' ) );
		}
	}
	
	/*
		matches: [ ':id', '?', ':var', ':id' ]
		execute: [ 'id' => 1, 'var' => 2, 4 ]
		result: [ 1, 4, 2, 1 ]
	*/
	public function executeHolders( array $execute, int $keys = 0 ): array
	{
		$new = [];
		
		$id = 0;
		
		//print_r ( $execute );
		
		// [ ':test', '?', '?', ':id', '?' ]
		foreach ( $this -> matches ?? [] AS $key => $placeholders )
		{
			if ( $placeholders == '?' )
			{
				$new[$key + $keys] = $execute[$id++];
			}
			else // :name
			{
				$new[$key + $keys] = $execute[substr ( $placeholders, 1 )];
			}
		}
		
		// replace keys +1
		if ( empty ( $this -> matches ) && $keys && ! empty ( $execute ) )
		{
			return array_combine ( range ( $keys, array_key_last ( $execute ) + $keys ), $execute );
		}

		return $new ?: $execute;
	}
}
