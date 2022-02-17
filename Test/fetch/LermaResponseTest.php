<?php

declare ( strict_types = 1 );

use Nouvu\Database\{ Lerma, LermaStatement };

class LermaResponseTest
{
	protected static int $repeat_fetch = 1;
	
	public function __construct ( private string $table, private Lerma $lerma )
	{}
	
	protected function result( LermaStatement $statement, int $mode, mixed $argument = null, int $repeat = null ): mixed
	{
		$repeat ??= self :: $repeat_fetch;
		
		if ( $repeat > 1 )
		{
			$a = [];
			
			for ( $i = 1; $i <= $repeat; $i++ )
			{
				$a[ 'fetch-' . $i ] = $statement -> fetch( $mode, $argument );
			}
			
			return $a;
		}
		
		return $statement -> fetch( $mode, $argument );
	}
	
	public function fetch_num( int $mode ): mixed
	{
		$stmt = $this -> lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );
		
		return $this -> result( statement: $stmt, mode: $mode );
	}
	
	public function fetchall_num( int $mode ): iterable
	{
		return $this -> lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] ) -> fetchAll( $mode );
	}
	
	public function fetch_assoc( int $mode ): mixed
	{
		$stmt = $this -> lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );
		
		return $this -> result( statement: $stmt, mode: $mode );
	}
	
	public function fetchall_assoc( int $mode ): iterable
	{
		return $this -> lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] ) -> fetchAll( $mode );
	}
	
	public function fetch_obj( int $mode ): mixed
	{
		$stmt = $this -> lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );
		
		return $this -> result( statement: $stmt, mode: $mode );
	}
	
	public function fetchall_obj( int $mode ): iterable
	{
		if ( Lerma :: FETCH_COLUMN == $mode )
		{
			return $this -> lerma -> query( [ 'SELECT `name` FROM `%s`', $this -> table ] ) -> fetchAll( $mode );
		}
		
		if ( Lerma :: FETCH_FUNC == $mode )
		{
			return $this -> lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] ) -> fetchAll( $mode, function ( object $data )
			{
				return implode ( ' - ', ( array ) $data );
			} );
		}
		
		return $this -> lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] ) -> fetchAll( $mode );
	}
	
	public function fetch_column( int $mode ): mixed
	{
		$stmt = $this -> lerma -> query( [ 'SELECT `name` FROM `%s`', $this -> table ] );
		
		return $this -> result( statement: $stmt, mode: $mode );
	}
	
	public function fetch_func( int $mode ): mixed
	{
		$stmt = $this -> lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );
		
		return $this -> result( statement: $stmt, mode: $mode, argument: function ( object $data ): string
		{
			return implode ( ' - ', ( array ) $data );
		} );
	}
	
	public function fetch_key_pair( int $mode ): mixed
	{
		$stmt = $this -> lerma -> query( [ 'SELECT `name`, `text` FROM `%s`', $this -> table ] );
		
		return $this -> result( statement: $stmt, mode: $mode );
	}
	
	public function fetchall_key_pair( int $mode ): iterable
	{
		$lrm = $this -> lerma -> query( [ 'SELECT `name`, `text` FROM `%s`', $this -> table ] );
		
		if ( $mode == ( Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC ) )
		{
			return $lrm -> fetchAll( $mode, function ( string $name ): string
			{
				return "--- {{$name}} ---";
			} );
		}
		
		return $lrm -> fetchAll( $mode );
	}
	
	public function fetchall_unique( int $mode ): iterable
	{
		return $this -> lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] ) -> fetchAll( $mode, 'group' );
	}
	
	public function fetchall_group( int $mode ): iterable
	{
		return $this -> lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] ) -> fetchAll( $mode, 'group' );
	}
	
	public function fetchall_group_column( int $mode ): iterable
	{
		return $this -> lerma -> query( [ 'SELECT `group`, `name` FROM `%s`', $this -> table ] ) -> fetchAll( $mode );
	}
	
	public function fetch_field( int $mode ): mixed
	{
		$stmt = $this -> lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] );
		
		return $this -> result( statement: $stmt, mode: $mode );
	}
	
	public function fetchall_field( int $mode ): iterable
	{
		return $this -> lerma -> query( [ 'SELECT * FROM `%s`', $this -> table ] ) -> fetchAll( $mode );
	}
	
	public function fetch_bind( int $mode ): mixed
	{
		if ( ( Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN ) == $mode )
		{
			$stmt = $this -> lerma -> prepare( [ 'SELECT `name` FROM `%s` WHERE :id', $this -> table ], [ 'id' => 1 ] );
		}
		else
		{
			$stmt = $this -> lerma -> prepare( [ 'SELECT * FROM `%s` WHERE ?', $this -> table ], [ 1 ] );
		}
		
		return $this -> result( statement: $stmt, mode: $mode );
	}
}