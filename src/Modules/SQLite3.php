<?php

declare ( strict_types = 1 );

namespace Nouvu\Database\Modules;

use Nouvu\Database\{ Lerma, ModuleInterface, DriverEnum };
use Nouvu\Database\Exception\LermaDriveException;

use function Nouvu\Database\Helpers\{ config, debug };

final class SQLite3 implements ModuleInterface
{
	private $statement = null;
	
	private $query = null;
	
	private $result;
	
	private \SQLite3 $connect;
	
	public function __construct ( private Lerma $lerma )
	{
		$this -> connect = new \SQLite3( config( 'drivers.' . DriverEnum :: SQLite3 -> value . '.db' ) );
	}
	
	public function isError(): void
	{}
	
	public function query( string $sql ): void
	{
		$this -> query = $this -> connect -> query( $sql );
	}
	
	public function prepare( string $sql ): void
	{
		$this -> statement = $this -> connect -> prepare( $sql );
	}
	
	public function binding( array $binding ): void
	{
		foreach ( $binding AS $items )
		{
			foreach ( $this -> lerma -> executeHolders( $items, 1 ) AS $key => $item )
			{
				if ( is_int ( $key ) )
				{
					$this -> statement -> bindValue( $key, $item );
				}
				elseif ( strpos ( $key, ':' ) !== false )
				{
					$this -> statement -> bindParam( $key, $item );
				}
			}
			
			$this -> result = $this -> statement -> execute();
		}
	}
	
	public function close(): ModuleInterface
	{
		if ( ! is_null ( $this -> statement ) && ! is_bool ( $this -> statement ) )
		{
			$this -> statement -> close();
		}
		
		$this -> statement = $this -> query = $this -> result = null;
		
		return $this;
	}
	
	/*
		- Определение типа запроса в базу данных
	*/
	protected function result(): mixed
	{
		return $this -> query ?: $this -> result;
	}
	
	public function fetch( int $mode ): iterable
	{
		try
		{
			$row = match ( $mode )
			{
				Lerma :: FETCH_NUM => $this -> result() -> fetchArray( \SQLITE3_NUM ),
				Lerma :: FETCH_ASSOC, Lerma :: FETCH_OBJ => $this -> result() -> fetchArray( \SQLITE3_ASSOC ),
			};
			
			if ( ! is_bool ( $row ) )
			{
				yield ( Lerma :: FETCH_OBJ == $mode ? ( object ) $row : $row );
			}
		} 
		catch ( \UnhandledMatchError $e )
		{
			throw new LermaDriveException( 'Selected mode for the result was not found' );
		}
	}
	
	public function fetchAll( int $mode ): iterable
	{
		while ( ! is_null ( $row = $this -> fetch( $mode ) -> current() ) )
		{
			yield $row;
		}
	}
	
	public function columnCount(): int
	{
		return $this -> result() -> numColumns();
	}
	
	public function rowCount(): int
	{
		if ( $this -> columnCount() && $this -> result() -> columnType( 0 ) != \SQLITE3_NULL )
		{
			return 1;
		}
		
		return 0;
	}
	
	public function InsertID(): int
	{
		return $this -> connect -> lastInsertRowID();
	}
	
	public function rollBack( mixed ...$rollback ): bool
	{
		return $this -> connect -> exec( 'ROLLBACK' );
	}
	
	public function beginTransaction( mixed ...$rollback ): bool
	{
		return $this -> connect -> exec( 'BEGIN TRANSACTION' );
	}
	
	public function commit( mixed ...$commit ): bool
	{
		return $this -> connect -> exec( 'END TRANSACTION' );
	}
	
	public function get(): mixed
	{
		return $this -> connect;
	}
}
