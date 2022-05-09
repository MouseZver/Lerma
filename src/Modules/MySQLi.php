<?php

declare ( strict_types = 1 );


namespace Nouvu\Database\Modules;

use Nouvu\Database\{ Lerma, ModuleInterface, DriverEnum };
use Nouvu\Database\Exception\LermaDriveException;

use function Nouvu\Database\Helpers\{ config, debug };

final class MySQLi implements ModuleInterface
{
	private $statement = null;
	
	private $query = null;
	
	private $result;
	
	private \mysqli $connect;
	
	public function __construct ( private Lerma $lerma )
	{
		$driver = new \mysqli_driver;
		
		$driver -> reconnect = true;
		
		$driver -> report_mode = \MYSQLI_REPORT_STRICT;
		
		//$this -> connect();
	}
	
	private function connect()
	{
		$params = config( 'drivers.' . DriverEnum :: MySQLi -> value );
		
		try
		{
			$this -> connect = new \mysqli( 
				$params['host'], 
				$params['username'], 
				$params['password'], 
				$params['dbname'], 
				( int ) $params['port']
			);
			
			if ( $this -> connect -> connect_error ) 
			{
				throw new LermaDriveException( sprintf ( 'Error connect (%s) %s', 
					$this -> connect -> connect_errno, $this -> connect -> connect_error ) );
			}
			
			$this -> connect -> set_charset( $params['charset'] );
		}
		catch ( \mysqli_sql_exception $e )
		{
			config( 'ShemaExceptionConnect.' . DriverEnum :: MySQLi -> value )( $e );
		}
	}
	
	public function query( string $sql ): void
	{
		$this -> connect ?-> ping() ?: $this -> connect();
		
		$this -> query = $this -> connect -> query( $sql );
	}
	
	public function prepare( string $sql ): void
	{
		$this -> connect ?-> ping() ?: $this -> connect();
		
		$this -> statement = $this -> connect -> prepare( $sql );
	}
	
	public function fetch( int $mode ): iterable
	{
		try
		{
			$row = match ( $mode )
			{
				Lerma :: FETCH_OBJ => $this -> result() -> fetch_object(),
				Lerma :: FETCH_NUM => $this -> result() -> fetch_array( \MYSQLI_NUM ),
				Lerma :: FETCH_ASSOC => $this -> result() -> fetch_array( \MYSQLI_ASSOC ),			
				Lerma :: MYSQL_FETCH_BIND => $this -> statement -> fetch(),
				Lerma :: MYSQL_FETCH_FIELD => $this -> result() -> fetch_field(),
			};
			
			if ( ! is_null ( $row ) )
			{
				yield $row;
			}
		} 
		catch ( \UnhandledMatchError $e )
		{
			throw new LermaDriveException( 'Selected mode for the result was not found' );
		}
	}
	
	public function fetchAll( int $mode ): iterable
	{
		try
		{
			return match ( $mode )
			{
				Lerma :: FETCH_NUM => yield from $this -> result() -> fetch_all( \MYSQLI_NUM ),
				Lerma :: FETCH_ASSOC => yield from $this -> result() -> fetch_all( \MYSQLI_ASSOC ),
				Lerma :: MYSQL_FETCH_FIELD => yield from $this -> result() -> fetch_fields(),
			};
		} 
		catch ( \UnhandledMatchError $e )
		{
			throw new LermaDriveException( 'Selected mode for the result was not found' );
		}
	}
	
	public function columnCount(): int
	{
		return $this -> connect -> field_count;
	}
	
	public function rowCount(): int
	{
		return $this -> result() -> num_rows;
	}
	
	public function InsertID(): int
	{
		return $this -> connect -> insert_id;
	}
	
	public function rollBack( mixed ...$rollback ): bool
	{
		return $this -> connect -> rollback( ...$rollback );
	}
	
	public function beginTransaction( mixed ...$rollback ): bool
	{
		return $this -> connect -> begin_transaction( ...$rollback );
	}
	
	public function commit( mixed ...$commit ): bool
	{
		return $this -> connect -> commit( ...$commit );
	}
	
	public function isError(): void
	{
		$obj = $this -> statement ?: $this -> connect;
		
		if ( $obj -> errno )
		{
			throw new LermaDriveException( $obj -> error );
		}
	}
	
	/*
		- Конструирование и привязка значений
	*/
	public function binding( array $binding ): void
	{
		$this -> result = null;
		
		$bind_param = [ '' ];
		
		$count = 0;
		
		foreach ( $this -> lerma -> executeHolders( $binding[0] ) AS $args )
		{
			$short = [
				'integer'	=> 'i', 
				'double'	=> 'd', 
				'string'	=> 's',
				'NULL'		=> 's'
			];
            
			$type = gettype ( $args );
			
			if ( ! isset ( $short[$type] ) )
			{
				throw new LermaDriveException( 'Invalid type ' . $type );
			}
			
			$bind_param[0] .= $short[$type];
			
			$count++;
		}
		
		for ( $i = 0; $i < $count; $bind_param[] = &${ 'bind_' . $i++ } ){}
		
		$this -> statement -> bind_param( ...$bind_param );
		
		foreach ( $binding AS $items )
		{
			$items = $this -> lerma -> executeHolders( $items );
			
			debug() -> setBindData( $items );
			
			extract ( $items, EXTR_PREFIX_ALL, 'bind' );
			
			$this -> statement -> execute();
		}
	}
	
	public function bindResult( $result ): bool
	{
		if ( is_null ( $this -> result ) )
		{
			return $this -> statement -> bind_result( ...$result );
		}
		
		throw new LermaDriveException( 'Binding data to variables is not possible after the result obtained from the database.' );
	}
	
	public function close(): ModuleInterface
	{
		$close = ( $this -> statement ?: $this -> query );
		
		if ( ! is_null ( $close ) && ! is_bool ( $close ) )
		{
			$close -> close();
		}
		
		$this -> statement = $this -> query = $this -> result = null;
		
		return $this;
	}
	
	/*
		- Переносим результат в результативное хранилище.
	*/
	public function result(): mixed
	{
		if ( ! is_null ( $this -> statement ) )
		{
			return $this -> result ??= $this -> statement -> get_result();
		}
		
		return $this -> query;
	}
	
	public function get(): mixed
	{
		return $this -> connect;
	}
}
