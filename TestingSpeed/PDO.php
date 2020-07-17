<?php

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ url-source: http://github.com/MouseZver/Lerma
	@ php-version 7.4
*/

namespace TestingSpeed;

final class PDO extends \PDO
{
	public function __construct ( ...$args )
	{
		parent :: __construct ( ...$args );
	}
	
	protected function startTime(): void
	{
		$this -> microtime = microtime ( 1 );
	}
	
	protected function endTime( string $fun ): void
	{
		$microtime = microtime ( 1 ) - $this -> microtime;
		
		printf ( '<b>Class</b>: %s<br><b>Method</b>: %s<br><b>complete</b>: %.2f<br>', __CLASS__, $fun, $microtime );
	}
	
	public function insert( string $items ): void
	{
		$this -> startTime();
		
		$stmt = $this -> prepare( 'INSERT INTO `testingspeed` ( `num`, `message` ) VALUES ( ?,? )' );
		
		foreach ( json_decode ( $items, true ) AS [ $num, $message ] )
		{
			$stmt -> execute( [ $num, $message ] );
		}
		
		$this -> endTime( __FUNCTION__ );
	}
	
	public function selectQuery( string $sql ): void
	{
		$this -> startTime();
		
		$this -> query( $sql );
		
		$this -> endTime( __FUNCTION__ );
	}
}