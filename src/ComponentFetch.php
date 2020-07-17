<?php

declare ( strict_types = 1 );

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ url-source: http://github.com/MouseZver/Lerma
	@ php-version 7.4
*/

namespace Nouvu\Database;

use Error;

class ComponentFetch
{
	protected array $_fetch = [
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
	
	/*
		-
	*/
	protected function fetchall_num( int $fetch_style, $fetch_argument ): array
	{
		return $this -> InterfaceDriver -> fetchAll( Lerma :: FETCH_NUM );
	}
	
	/*
		-
	*/
	protected function fetchall_assoc( int $fetch_style, $fetch_argument ): array
	{
		return $this -> InterfaceDriver -> fetchAll( Lerma :: FETCH_ASSOC );
	}
	
	/*
		-
	*/
	protected function fetchall_field( int $fetch_style, $fetch_argument ): array
	{
		$all = [];
		
		while ( ! is_null ( $res = $this -> fetch( $fetch_style, $fetch_argument ) ) ) 
		{ 
			$all[] = $res; 
		}

		return $all;
	}
	
	/*
		-
	*/
	protected function fetchall_obj( int $fetch_style, $fetch_argument ): array
	{
		$all = [];
		
		while ( ! is_null ( $res = $this -> fetch( $fetch_style, $fetch_argument ) ) ) 
		{ 
			$all[] = $res; 
		}

		return $all;
	}
	
	/*
		-
	*/
	protected function fetchall_key_pair( int $fetch_style, callable $fetch_argument = null ): array
	{
		if ( $this -> InterfaceDriver -> columnCount() != 2 )
		{
			throw new Error( $this -> config -> get( 'errMessage.statement.columnCount.only.2' ) );
		}
		
		$all = [];
		
		while ( $num = $this -> InterfaceDriver -> fetch( Lerma :: FETCH_NUM ) )
		{
			if ( $fetch_style === ( Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_NAMED ) && isset ( $all[$num[0]] ) )
			{
				if ( is_array ( $all[$num[0]] ) )
				{
					$all[$num[0]][] = $num[1];
				}
				else
				{
					$all[$num[0]] = [ $all[$num[0]], $num[1] ];
				}
			}
			else
			{
				if ( $fetch_style === ( Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC ) )
				{
					$all[$num[0]] = $fetch_argument( $num[1] );
				}
				else
				{
					$all[$num[0]] = $num[1];
				}
			}
		}
		
		return $all;
	}
	
	/*
		-
	*/
	protected function fetchall_unique( int $fetch_style, string $fetch_argument = null  ): array
	{
		if ( $this -> InterfaceDriver -> columnCount() < 2 )
		{
			throw new Error( $this -> config -> get( 'errMessage.statement.columnCount.min' ) );
		}
		
		$all = [];
		
		foreach ( $this -> InterfaceDriver -> fetchAll( Lerma :: FETCH_ASSOC ) AS $items )
		{
			$all[array_shift ( $items )] = $items;
		}
		
		return $all;
	}
	
	/*
		-
	*/
	protected function fetchall_group( int $fetch_style, $fetch_argument ): array
	{
		if ( $this -> InterfaceDriver -> columnCount() < 2 )
		{
			throw new Error( $this -> config -> get( 'errMessage.statement.columnCount.min' ) );
		}
		
		$all = [];
		
		foreach ( $this -> InterfaceDriver -> fetchAll( Lerma :: FETCH_ASSOC ) AS $s )
		{
			$all[array_shift ( $s )][] = $s;
		}
		
		return $all;
	}
	
	/*
		-
	*/
	protected function fetchall_group_column( int $fetch_style, $fetch_argument ): array
	{
		if ( $this -> InterfaceDriver -> columnCount() != 2 )
		{
			throw new Error( $this -> config -> get( 'errMessage.statement.columnCount.only.2' ) );
		}
		
		$all = [];
		
		foreach ( $this -> InterfaceDriver -> fetchAll( Lerma :: FETCH_NUM ) AS $s )
		{
			$all[array_shift ( $s )][] = $s[0];
		}
		
		return $all;
	}
	
	/*
		-
	*/
	protected function fetch_num( int $fetch_style, $fetch_argument )
	{
		return $this -> InterfaceDriver -> fetch( Lerma :: FETCH_NUM );
	}

	/*
		-
	*/
	protected function fetch_assoc( int $fetch_style, $fetch_argument )
	{
		return $this -> InterfaceDriver -> fetch( Lerma :: FETCH_ASSOC );
	}
	
	/*
		-
	*/
	protected function fetch_field( int $fetch_style, $fetch_argument )
	{
		$info = $this -> InterfaceDriver -> fetch( Lerma :: MYSQL_FETCH_FIELD );
		
		if ( is_null ( $info ) || array_key_exists ( 0, $info ) )
		{
			return null;
		}
		
		return $info[$fetch_argument] ?? $info;
	}
	
	/*
		-
	*/
	protected function fetch_obj( int $fetch_style, $fetch_argument )
	{
		return $this -> InterfaceDriver -> fetch( Lerma :: FETCH_OBJ );
	}
	
	/*
		-
	*/
	protected function fetch_bind( int $fetch_style, $fetch_argument )
	{
		if ( ! $this -> bind() -> fetch( Lerma :: MYSQL_FETCH_BIND ) )
		{
			return $this -> bind_result = [];
		}

		if ( $fetch_style == ( Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN ) )
		{
			if ( $this -> InterfaceDriver -> columnCount() != 1 )
			{
				throw new Error( $this -> config -> get( 'errMessage.statement.columnCount.only.1' ) );
			}

			return $this -> bind_result[0];
		}

		return $this -> bind_result;
	}

	/*
		-
	*/
	protected function fetch_column( int $fetch_style, $fetch_argument )
	{
		return $this -> InterfaceDriver -> fetch( Lerma :: FETCH_NUM )[0] ?? null;
	}

	/*
		-
	*/
	protected function fetch_key_pair( int $fetch_style, $fetch_argument ): ?array # column1 => column2
	{
		if ( is_null ( $items = $this -> InterfaceDriver -> fetch( Lerma :: FETCH_NUM ) ) )
		{
			return null;
		}
		
		return [ $items[0] => $items[1] ];
	}

	/*
		-
	*/
	protected function fetch_func( int $fetch_style, callable $fetch_argument )
	{
		if ( $items = $this -> InterfaceDriver -> fetch( Lerma :: FETCH_NUM ) )
		{
			return $fetch_argument( ...$items );
		}
		
		return null;
	}
}
