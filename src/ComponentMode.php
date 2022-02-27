<?php

declare ( strict_types = 1 );

namespace Nouvu\Database;

use Nouvu\Database\Exception\LermaStatementException;

use function Nouvu\Database\Helpers\{ getExtension };

class ComponentMode
{
	// Lerma :: FETCH_NUM
	protected function fetch_num(): iterable
	{
		return $this -> lerma -> connect() -> fetch( Lerma :: FETCH_NUM );
	}
	
	// Lerma :: FETCH_NUM
	protected function fetch_num_all(): iterable
	{
		return $this -> lerma -> connect() -> fetchAll( Lerma :: FETCH_NUM );
	}
	
	// Lerma :: FETCH_ASSOC
	protected function fetch_assoc(): iterable
	{
		return $this -> lerma -> connect() -> fetch( Lerma :: FETCH_ASSOC );
	}
	
	// Lerma :: FETCH_ASSOC
	protected function fetch_assoc_all(): iterable
	{
		return $this -> lerma -> connect() -> fetchAll( Lerma :: FETCH_ASSOC );
	}
	
	// Lerma :: FETCH_OBJ
	protected function fetch_obj(): iterable
	{
		return $this -> lerma -> connect() -> fetch( Lerma :: FETCH_OBJ );
	}
	
	// Lerma :: FETCH_OBJ
	protected function fetch_obj_all( int $mode, \Closure | null $argument ): iterable
	{
		while ( $row = $this -> fetch( $mode, $argument ) )
		{
			yield $row;
		}
	}
	
	// Lerma :: MYSQL_FETCH_FIELD
	protected function fetch_field(): iterable
	{
		return $this -> lerma -> connect() -> fetch( Lerma :: MYSQL_FETCH_FIELD );
	}
	
	// Lerma :: MYSQL_FETCH_FIELD
	protected function fetch_field_all(): iterable
	{
		return $this -> lerma -> connect() -> fetchAll( Lerma :: MYSQL_FETCH_FIELD );
	}
	
	// Lerma :: FETCH_COLUMN
	protected function fetch_column(): mixed
	{
		foreach ( $this -> lerma -> connect() -> fetch( Lerma :: FETCH_NUM ) AS $row )
		{
			yield $row[0];
		}
	}
	
	// Lerma :: FETCH_KEY_PAIR
	protected function fetch_key_pair(): iterable
	{
		if ( $this -> lerma -> connect() -> columnCount() != 2 )
		{
			throw new LermaStatementException( 'FETCH_KEY_PAIR mode is demanding on the number of columns in the request - there must be strictly two of them.' );
		}
		
		foreach ( $this -> lerma -> connect() -> fetch( Lerma :: FETCH_NUM ) AS [ $first, $second ] )
		{
			yield [ $first => $second ];
		}
	}
	
	// Lerma :: FETCH_KEY_PAIR
	// Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC
	protected function fetch_key_pair_all( int $mode, \Closure | null $argument ): iterable
	{
		if ( $this -> lerma -> connect() -> columnCount() != 2 )
		{
			throw new LermaStatementException( 'FETCH_KEY_PAIR mode is demanding on the number of columns in the request - there must be strictly two of them.' );
		}
		
		foreach ( $this -> lerma -> connect() -> fetchAll( Lerma :: FETCH_NUM ) AS [ $first, $second ] )
		{
			if ( ( Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC ) == $mode )
			{
				if ( is_null ( $argument ) )
				{
					throw new LermaStatementException( 'FETCH_KEY_PAIR & FETCH_FUNC mode expected the second argument as a function, got null.' );
				}
				
				yield $first => $argument( $first, $second );
			}
			else // Lerma :: FETCH_KEY_PAIR
			{
				yield $first => $second;
			}
		}
	}
	
	// Lerma :: FETCH_UNIQUE
	protected function fetch_unique_all( int $mode, string | null $column ): iterable
	{
		if ( $this -> lerma -> connect() -> columnCount() < 2 )
		{
			throw new LermaStatementException( 'FETCH_UNIQUE mode requires at least two selected columns from table.' );
		}
		
		while ( $row = $this -> fetch( Lerma :: FETCH_ASSOC ) )
		{
			yield ( isset ( $row[$column] ) || array_key_exists ( $column, $row ) ? $row[$column] : reset ( $row ) ) => ( object ) $row;
		}
	}
	
	// Lerma :: FETCH_GROUP
	protected function fetch_group_all( int $mode, string | null $column ): iterable
	{
		if ( $this -> lerma -> connect() -> columnCount() < 2 )
		{
			throw new LermaStatementException( 'FETCH_GROUP mode requires at least two selected columns from table.' );
		}
		
		$all = [];
		
		foreach ( $this -> fetchAll( Lerma :: FETCH_ASSOC ) AS $row )
		{
			$all[ ( isset ( $row[$column] ) || array_key_exists ( $column, $row ) ? $row[$column] : reset ( $row ) ) ][] = ( object ) $row;
		}
		
		return $all;
	}
	
	// Lerma :: FETCH_GROUP | Lerma :: FETCH_COLUMN
	protected function fetch_group_column_all(): iterable
	{
		if ( $this -> lerma -> connect() -> columnCount() != 2 )
		{
			throw new RequestException( 'Lerma :: FETCH_GROUP | Lerma :: FETCH_COLUMN mode is demanding on the number of columns in the request - there must be strictly two of them.' );
		}
		
		$all = [];
		
		foreach ( $this -> lerma -> connect() -> fetchAll( Lerma :: FETCH_NUM ) AS [ $first, $second ] )
		{
			$all[$first][] = $second;
		}
		
		return $all;
	}
	
	// Lerma :: MYSQL_FETCH_BIND
	protected function fetch_bind( int $mode ): mixed
	{
		if ( DriverEnum :: MySQLi -> value != getExtension( $this -> lerma ) )
		{
			throw new LermaStatementException( 'Selected mode for the result was not found' );
		}
		
		$iterator = $this -> bind() -> fetch( Lerma :: MYSQL_FETCH_BIND );
		
		if ( is_null ( $iterator -> current() ) )
		{
			$this -> bind_result = [];
			
			return null;
		}

		if ( $mode == ( Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN ) )
		{
			if ( $this -> lerma -> connect() -> columnCount() != 1 )
			{
				throw new LermaStatementException( 'MYSQL_FETCH_BIND & FETCH_COLUMN mode requires the number of columns in the query - there should be strictly only one.' );
			}

			return $this -> bind_result[0];
		}
		
		return $this -> bind_result;
	}
	
	// Lerma :: FETCH_FUNC
	protected function fetch_func( int $mode, callable $argument ): iterable
	{
		foreach ( $this -> lerma -> connect() -> fetch( Lerma :: FETCH_OBJ ) AS $row )
		{
			yield $argument( $row );
		}
	}
}
