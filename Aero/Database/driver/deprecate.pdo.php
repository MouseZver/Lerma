<?php

use Lerma\Interfaces\LermaDrivers;

return new class ( $Lerma -> {$Lerma -> driver} ) implements LermaDrivers
{
	private $connect;
	private $statement;
	
	public function __construct ( array $params = [] )
	{
		if ( empty ( $params ) )
		{
			throw new Exception( 'Params expects most parameter values, returned empty' );
		}
		
		$params = array_values ( $params );
		
		$this -> connect = new PDO( ...$params );
	}
	protected function error( $obj )
	{
		if ( !empty ( $obj -> errorInfo()[2] ) ) 
		{
			throw new Exception( $obj -> errorInfo()[2] );
		}
	}
	public function query( string $sql ): LermaDrivers
	{
		$this -> statement = $this -> connect -> query( $sql );
		$this -> error( $this -> statement );
		
		return $this;
	}
	public function prepare( string $sql ): LermaDrivers
	{
		$this -> statement = $this -> connect -> prepare( $sql );
		$this -> error( $this -> statement );
		
		return $this;
	}
	public function execute( array $arguments = [] )
	{
		if ( !is_a ( $this -> statement, PDOStatement::class ) )
		{
			throw new Exception( 'Not execute' );
		}
		
		$bool = $this -> statement -> execute( $arguments );
		$this -> error( $this -> statement );
		
		return $this;
	}
	public function fetch( int $fetch_style = 3 )
	{
		switch ( $fetch_style )
		{
			case 1:
				return $this -> statement -> fetch( PDO::FETCH_NUM );
			break;
			case 2:
				return $this -> statement -> fetch( PDO::FETCH_ASSOC );
			break;
			case 3:
				return $this -> statement -> fetch( PDO::FETCH_BOTH );
			break;
			case 4:
				return $this -> statement -> fetchObject();
			break;
			default:
				throw new Exception( sprintf ( 'Invalid fetch_style %s is not switch', $fetch_style ) );
		}
	}
	public function fetchAll( int $fetch_style = 3 ): array
	{
		switch ( $fetch_style )
		{
			case 1:
				return $this -> statement -> fetchAll( PDO::FETCH_NUM );
			break;
			case 2:
				return $this -> statement -> fetchAll( PDO::FETCH_ASSOC );
			break;
			case 3:
				return $this -> statement -> fetchAll( PDO::FETCH_BOTH );
			break;
			case 4:
				return $this -> statement -> fetchAll( PDO::FETCH_OBJ );
			break;
			default:
				throw new Exception( sprintf ( 'Invalid fetch_style %s is not switch', $fetch_style ) );
		}
	}
	public function InsertId(): int
	{
		return PDO::lastInsertId();
	}
	public function __call( $method, $arguments )
	{
		return ( method_exists ( $this, $method ) ? $this : $this -> statement ) -> $method( ...$arguments );
	}
};