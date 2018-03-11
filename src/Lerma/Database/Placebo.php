<?php

namespace Aero\Database;

use Aero\Database\Placebo;
use Exception AS Error;

final class Placebo extends Compile
{
	protected $command;
	
	protected $structures = [
		'SELECT' => [
			'columns'	=> [],
			'table'		=> [],
			'join'		=> [],
			'where'		=> [],
			'group'		=> [],
			'havings'	=> [],
			'order'		=> [],
			'limit'		=> [],
		],
		'INSERT' => [
			'table'		=> [],
			'columns'	=> [],
			'values'	=> [],
			'sql'		=> [],
		],
	];
	
	protected $access = [
		'columns' => [
			'options' => [
				'unset', 'values'
			],
			'command' => [ 'SELECT', 'INSERT' ]
		],
		'table' => [
			'options' => [
				'unset', 'values'
			],
			'command' => [ 'SELECT', 'INSERT', 'DELETE', 'UPDATE' ]
		],
		'where' => [
			'options' => [
				'values'
			],
			'command' => [ 'SELECT', 'DELETE', 'UPDATE' ]
		],
	];
	
	/*
		- 
	*/
	public function __construct( string $command = null )
	{
		try
		{
			$this -> command = strtoupper ( $command );
			
			if ( !isset ( $this -> structures[$this -> command] ) )
			{
				throw new Error( '...' );
			}
		}
		catch ( Throwable $t )
		{
			exit ( $t -> getMessage() );
		}
	}
	
	/*
		- 
	*/
	public function __call( $method, $arg )
	{
		try
		{
			$this -> isAccessCall( $method ) 
				-> isStructureCall( $method )
				-> isValuesCall( $arg ) 
				-> isCommandCall( $method )
				-> $method( ...$arg );
			
			if ( in_array ( 'unset', $this -> access[$method]['options'] ) )
			{
				unset ( $this -> access[$method] );
			}
		}
		catch ( \Throwable $t )
		{
			exit ( sprintf ( '<pre>Placebo: %s' . PHP_EOL . '%s</pre>', $t -> getMessage(), $t -> getTraceAsString() ) );
		}
	
		return $this;
	}
	
	/*
		- 
	*/
	protected function query( string $command, callable $callable )
	{
		$callable( $p = new Placebo( $command ) );
		
		$this -> scobe( $p -> get() );
	}
	
	/*
		- 
	*/
	public function get(): string
	{
		foreach ( $this -> structures[$this -> command] AS $name => $items )
		{
			$this -> {$name . 'Compile'}( $items );
		}
		
		return implode ( ' ', $this -> structures[$this -> command] );
	}
	
	/*
		- 
	*/
	protected function funCompile( string $name, $items )
	{
		switch ( strtoupper ( $name ) )
		{
			case 'COUNT':
				if ( is_string ( $items ) )
				{
					return sprintf ( 'COUNT( `%s` )', $items );
				}
				
				if ( count ( $items ) === 2 )
				{
					return sprintf ( 'COUNT( `%s` ) AS `%s`', ...$items );
				}
				
				throw new Error( '...' );
			break;
			case 'IN':
				return sprintf ( 'IN( %s )', ( is_array ( $items ) ? implode ( ', ', $items ) : $items ) );
			break;
			case 'BETWEEN':
				return sprintf ( '( %s BETWEEN %s )', ( is_array ( $items ) ? implode ( ' AND ', $items ) : $items ) );
			break;
		}
	}
	
	/*
		- 
	*/
	protected function isAccessCall( string $a )
	{
		if ( !isset ( $this -> access[$a] ) )
		{
			throw new Error( sprintf ( 'isAccessCall: Метод %s недоступен', $a ) );
		}
		
		return $this;
	}
	
	/*
		- 
	*/
	protected function isStructureCall( string $a )
	{
		if ( !isset ( $this -> structures[$this -> command][$a] ) )
		{
			throw new Error( 'isStructureCall' );
		}
		
		return $this;
	}
	
	/*
		- 
	*/
	protected function isValuesCall( array $b )
	{
		if ( empty ( $b ) )
		{
			throw new Error( 'isValuesCall' );
		}
		
		return $this;
	}
	
	/*
		- 
	*/
	protected function isCommandCall( string $a )
	{
		if ( isset ( $this -> access[$a]['command'] ) && !in_array ( $this -> command, $this -> access[$a]['command'] ) )
		{
			throw new Error( 'isCommandCall' );
		}
		
		return $this;
	}
	
	/*
		- 
	*/
	protected function columns( ...$arguments ) # ( 'column1', [ 'col2', 'column2' ], [ 'count' => [ 'col3', 'column3' ] ] )
	{
		foreach ( $arguments AS $key => $item )
		{
			if ( is_string ( $item ) )
			{
				$this -> structures[$this -> command]['columns'][] = "`{$item}`";
			}
			elseif ( is_array ( $item ) )
			{
				switch ( count ( $item ) )
				{
					case 1:
						if ( !is_string ( $key ) )
						{
							throw new Error( '...' );
						}
						
						$this -> structures[$this -> command]['columns'][] = $this -> funCompile( $key, $item[$key] );
					break;
					case 2:
						$this -> structures[$this -> command]['columns'][] = sprintf ( '`%s` AS `%s`', ...$item );
					break;
					default:
						throw new Error( '...' );
				}
			}
			else
			{
				throw new Error( '...' . var_export ( $item ) );
			}
		}
	}
	
	/*
		- 
	*/
	protected function table( ...$arguments )
	{
		foreach ( $arguments AS $item )
		{
			if ( is_string ( $item ) )
			{
				switch ( $this -> command )
				{
					case 'SELECT':
						$this -> structures[$this -> command]['table'][] = "`{$item}`";
					break;
					default:
						$this -> structures[$this -> command]['table'] = "`{$item}`";
				}
			}
			elseif ( is_array ( $item ) && count ( $item ) === 2 )
			{
				$this -> structures[$this -> command]['table'][] = sprintf ( '`%s` AS `%s`', ...$item );
			}
			else
			{
				throw new Error( '...' );
			}
		}
	}
	
	/*
		- 
	*/
	protected function where( ...$arguments )
	{
		if ( !empty ( $this -> structures[$this -> command]['where'] ) )
		{
			$this -> structures[$this -> command]['where'][] = 'OR';
		}
		
		foreach ( $arguments AS $item )
		{
			if ( in_array ( $item ) )
			{
				foreach ( $item AS $name => $c )
				{
					if ( is_string ( $name ) )
					{
						$this -> structures[$this -> command]['where'][] = $this -> funCompile( $name, $c );
					}
					else
					{
						
					}
				}
			}
			else
			{
				//err
			}
		}
	}
	
	/* +
		@ LIMIT 1, 2
	*/
	protected function limit( int ...$int )
	{
		if ( in_array ( count ( $int ), [ 1, 2 ] ) )
		{
			$this -> structures[$this -> command]['limit'] = sprintf ( 'LIMIT %s', implode ( ', ', $int ) );
			
			return;
		}
		
		throw new Error( 'Кол-во атрибутов LIMIT не соответсвует требованиям' );
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/* protected function leftjoin( $table, array $operator, ...$columns )
	{
		$this -> join ( $table, $columns, $operator, 'LEFT JOIN' );
	}
	
	
	protected function rightjoin( $table, array $operator, ...$columns )
	{
		$this -> join ( $table, $columns, $operator, 'RIGHT JOIN' );
	}
	
	
	protected function join ( $table, array $columns, string $operator, string $type = 'JOIN' )
	{
		if ( in_array ( $type, [ 'LEFT JOIN', 'RIGHT JOIN', 'JOIN' ] ) )
		{
			$this -> structure = 'JOIN';
			$this -> bilder[] = $type;
			$this -> table( $table );
			
			$x = [];
			
			foreach ( $columns AS $column )
			{
				$x[] = columnFormat( $column );
			}
			
			# count 2..
			
			$this -> bilder[] = sprintf ( 'ON %2$s %1$s %3$s', $operator, ...$x );
		}
	}
	
	
	protected function orderasc( $column )
	{
		$this -> order( $column, 'ASC' );
	}
	
	
	protected function orderdesc( $column )
	{
		$this -> order( $column, 'DESC' );
	}
	
	
	protected function order( $column, $sort = 'ASC' )
	{
		if ( in_array ( $sort, [ 'ASC', 'DESC' ] ) )
		{
			$format = sprintf ( '%s %s', $this -> columnFormat( $column ), $sort );
			
			if ( isset ( $this -> bilder['ORDER'] ) )
			{
				$this -> bilder['ORDER'] = sprintf ( '%s, %s', $this -> bilder['ORDER'], $format );
				
				return;
			}
			
			$this -> bilder['ORDER'] = sprintf ( 'ORDER BY %s', $format );
		}
	}
	
	
	protected function limit( int ...$int )
	{
		$this -> bilder[] = sprintf ( 'LIMIT %s', implode ( ', ', $int ) );
	} */
	
	
	
	
	
	protected function format( $items, $format ): string
	{
		if ( is_array ( $items ) )
		{
			return sprintf ( $format, ...$items );
		}
		
		# if ( is_string ( $name ) )
		
		return "`{$items}`";
		
		# ( strpbrk ( $name, ':?' ) !== false ? $name : "`{$name}`" ); не путать с именами столбцов
	}
}

/* INSERT INTO [TABLE] ( [COLUMNS] ) 
	SET [SET]
	VALUES ( [VALUES] )
	( SQL )

SELECT [COLUMNS]
FROM [TABLE]
	[JOIN]
WHERE [COLUMNS]
ORDER BY [COLUMN SORT]
LIMIT [NUM]

sql( 'insert' ) 
	-> table( 'table' ) 
	-> columns( 'column', 'column2' ) 
	-> values( 1,2 );

insert
structure insert
binding = array ( 'INSERT' => 'INSERT INTO [TABLE]' )

table
structure insert
replaces = array ( '[TABLE]' => '`table`' )
structure insert

columns
structure insert
binding = array ( 'COLUMNS' => '( `column`, `column2` )' )
structure COLUMNS

values
structure COLUMNS
binding = array ( 'VALUES' => "VALUES ( 'value', 'value2' )" )
structure null


query( 'INSERT', function ( Placebo $Placebo )
{
	$Placebo -> table( 'table' ) 
	-> columns( 'column', 'column2' ) 
	-> query( 'SELECT', function ( Placebo $Placebo )
	{
		$Placebo -> columns( 'column', 'column2' ) 
		-> table( 'table' )
		-> leftjoin( 'table', [ 'column', 'column2' ], '=' )
		-> orderdesc( 'id' )
		-> limit(100);
	});
}); */