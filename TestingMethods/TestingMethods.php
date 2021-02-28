<?php

use Nouvu\Database\Lerma;

final class TestingMethods extends Lerma
{
	public function getFetches(): array
	{
		return [
			Lerma :: FETCH_NUM => [ 'fetch_num', 'all' => 'fetchall_num' ],
			Lerma :: FETCH_ASSOC => [ 'fetch_assoc', 'all' => 'fetchall_assoc' ],
			Lerma :: FETCH_OBJ => [ 'fetch_obj', 'all' => 'fetchall_obj' ],
			Lerma :: MYSQL_FETCH_FIELD => [ 'fetch_field', 'all' => 'fetchall_field' ],
			Lerma :: MYSQL_FETCH_BIND => [ 'fetch_bind' ],
			Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN => [ 'fetch_bind' ],
			Lerma :: FETCH_COLUMN => [ 'fetch_column', 'all' => 'fetchall_obj' ],
			Lerma :: FETCH_KEY_PAIR => [ 'fetch_key_pair', 'all' => 'fetchall_key_pair' ],
			Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_NAMED => [ 'all' => 'fetchall_key_pair' ],
			Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC => [ 'all' => 'fetchall_key_pair' ],
			Lerma :: FETCH_FUNC => [ 'fetch_func', 'all' => 'fetchall_obj' ],
			Lerma :: FETCH_UNIQUE => [ 'all' => 'fetchall_unique' ],
			Lerma :: FETCH_GROUP => [ 'all' => 'fetchall_group' ],
			Lerma :: FETCH_GROUP | Lerma :: FETCH_COLUMN => [ 'all' => 'fetchall_group_column' ],
		];
	}
	
	public function fetch_num( int $style )
	{
		return $this -> query( 'SELECT * FROM `lerma`' ) -> fetch( $style );
	}
	
	public function fetchall_num( int $style )
	{
		return $this -> query( 'SELECT * FROM `lerma`' ) -> fetchAll( $style );
	}
	
	public function fetch_assoc( int $style )
	{
		return $this -> query( 'SELECT * FROM `lerma`' ) -> fetch( $style );
	}
	
	public function fetchall_assoc( int $style )
	{
		return $this -> query( 'SELECT * FROM `lerma`' ) -> fetchAll( $style );
	}
	
	public function fetch_obj( int $style )
	{
		return $this -> query( 'SELECT * FROM `lerma`' ) -> fetch( $style );
	}
	
	public function fetchall_obj( int $style )
	{
		if ( Lerma :: FETCH_COLUMN == $style )
		{
			return $this -> query( 'SELECT `name` FROM `lerma`' ) -> fetchAll( $style );
		}
		
		if ( Lerma :: FETCH_FUNC == $style )
		{
			return $this -> query( 'SELECT * FROM `lerma`' ) -> fetchAll( $style, function ( ...$columns )
			{
				return implode ( ' - ', $columns );
			} );
		}
		
		return $this -> query( 'SELECT * FROM `lerma`' ) -> fetchAll( $style );
	}
	
	public function fetch_field( int $style )
	{
		if ( $this -> driver != 'mysql' )
		{
			return 'Lerma :: MYSQL_FETCH_FIELD доступен только для расширения MySQL';
		}
		
		return $this -> query( 'SELECT * FROM `lerma`' ) -> fetch( $style );
	}
	
	public function fetchall_field( int $style )
	{
		if ( $this -> driver != 'mysql' )
		{
			return 'Lerma :: MYSQL_FETCH_FIELD доступен только для расширения MySQL';
		}
		
		return $this -> query( 'SELECT * FROM `lerma`' ) -> fetchAll( $style );
	}
	
	public function fetch_bind( int $style )
	{
		if ( $this -> driver != 'mysql' )
		{
			return 'Lerma :: MYSQL_FETCH_BIND доступен только для расширения MySQL';
		}
		
		if ( ( Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN ) == $style )
		{
			return $this -> prepare( 'SELECT `name` FROM `lerma` WHERE :id', [ 'id' => 1 ] ) -> fetch( $style );
		}
		
		return $this -> prepare( 'SELECT * FROM `lerma` WHERE ?', [ 1 ] ) -> fetch( $style );
	}
	
	public function fetch_column( int $style )
	{
		return $this -> query( 'SELECT `name` FROM `lerma`' ) -> fetch( $style );
	}
	
	public function fetch_key_pair( int $style )
	{
		return $this -> query( 'SELECT * FROM `lerma`' ) -> fetch( $style );
	}
	
	public function fetchall_key_pair( int $style )
	{
		$lrm = $this -> query( 'SELECT `num`, `name` FROM `lerma`' );
		
		if ( $style == ( Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC ) )
		{
			return $lrm -> fetchAll( $style, function ( $name )
			{
				return [ $name => 'name' ];
			} );
		}
		
		return $lrm -> fetchAll( $style );
	}
	
	public function fetch_func( int $style )
	{
		return $this -> query( 'SELECT * FROM `lerma`' ) -> fetch( $style, function ( ...$columns )
		{
			return implode ( ' - ', $columns );
		} );
	}
	
	public function fetch_class( int $style )
	{
		return 'Lerma :: FETCH_CLASS* in development';
	}
	
	public function fetchall_unique( int $style )
	{
		return $this -> query( 'SELECT * FROM `lerma`' ) -> fetchAll( $style );
	}
	
	public function fetchall_group( int $style )
	{
		return $this -> query( 'SELECT `num`, `id`, `name` FROM `lerma`' ) -> fetchAll( $style );
	}
	
	public function fetchall_group_column( int $style )
	{
		return $this -> query( 'SELECT `num`, `name` FROM `lerma`' ) -> fetchAll( $style );
	}
}