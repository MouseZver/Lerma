<?php

use Aero\Supports\Lerma;
use Aero\Interfaces\Lerma\IDrivers;
use Exception AS Error;

return new class ( $Lerma -> {$Lerma -> driver} ) implements IDrivers
{
	private $connect;
	private $statement;
	private $result;
	
	/*
		- Подключение...
	*/
	public function __construct ( array $params = [] )
	{
		if ( empty ( $params ) )
		{
			throw new Error( 'Params expects most parameter values, returned empty' );
		}
		
		$params = array_values ( $params );
		
		$this -> connect = new mysqli( ...$params );
		$this -> connect -> set_charset( 'utf8' );
		
		if ( $this -> connect -> connect_error ) 
		{
			throw new Error( sprintf ( 'Error connect (%s) %s', $this -> connect -> connect_errno, $this -> connect -> connect_error ) );
		}
	}
	
	public function isError( $obj = null )
	{
		$obj = $obj ?? $this -> connect;
		
		if ( $obj -> errno )
		{
			throw new Error( $obj -> error );
		}
	}
	
	public function query( string $sql )
	{
		return $this -> query = $this -> connect -> query( $sql );
	}
	
	public function prepare( string $sql )
	{
		return $this -> statement = $this -> connect -> prepare( $sql );
	}
	
	public function execute()
	{
		return $this -> statement -> execute();
	}
	
	public function bindParam( array $types, array $array )
	{
		$arguments = array_merge ( [ implode ( '', $types ) ], $array );
		
		return $this -> statement -> bind_param( ...$arguments );
	}
	
	public function bindResult( $result )
	{
		return $this -> statement -> bind_result( ...$result );
	}
	
	public function close()
	{
		( $this -> statement ?? $this -> query ) -> close();
		
		$this -> statement = $this -> query = $this -> result = null;
	}
	
	protected function result()
	{
		if ( $this -> statement !== null )
		{
			return $this -> result ?? $this -> result = $this -> statement -> get_result();
		}
		
		return $this -> query;
	}
	
	public function fetch( int $int )
	{
		switch ( $int )
		{
			case Lerma::FETCH_NUM:
				return $this -> result() -> fetch_array( MYSQLI_NUM );
			break;
			case Lerma::FETCH_ASSOC:
				return $this -> result() -> fetch_array( MYSQLI_ASSOC );
			break;
			case Lerma::FETCH_OBJ:
				return $this -> result() -> fetch_object();
			break;
			case Lerma::FETCH_BIND:
				return $this -> statement -> fetch();
			break;
			default:
				return null;
		}
	}
	
	public function fetchAll( int $int )
	{
		switch ( $int )
		{
			case Lerma::FETCH_NUM:
				return $this -> result() -> fetch_all( MYSQLI_NUM );
			break;
			case Lerma::FETCH_ASSOC:
				return $this -> result() -> fetch_all( MYSQLI_ASSOC );
			break;
			default:
				return null;
		}
	}
	
	public function countColumn(): int
	{
		return $this -> connect -> field_count ?? 0;
	}
	
	public function rowCount(): int
	{
		return $this -> result() -> num_rows;
	}
	
	public function InsertID(): int
	{
		return $this -> connect -> insert_id;
	}
	
	public function rollBack( ...$rollback ): bool
	{
		return $this -> connect -> rollback( ...$rollback );
	}
	
	public function beginTransaction( ...$rollback ): bool
	{
		return $this -> connect -> begin_transaction( ...$rollback );
	}
	
	public function commit( ...$commit ): bool
	{
		return $this -> connect -> commit( ...$commit );
	}
	
	public function __call( $method, $arguments )
	{
		return $this -> statement -> $method( ...$arguments );
	}
};