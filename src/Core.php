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

class Core
{
	protected function binding( array $items )/* : int | Lerma */
	{
		/* if ( is_null ( $items ) )
		{
			throw new Error( $this -> config -> get( 'errMessage.prepare.items' ) );
		} */
		
		$this -> InterfaceDriver -> binding( $items );
		
		$this -> InterfaceDriver -> isError();
		
		return $this -> InterfaceDriver -> columnCount() ?: $this;
	}
	
	protected function replaceHolders( string $sql ): string
	{
		if ( strpos ( $sql, ':' ) !== false && $this -> config -> get( "ShemaActiveFun.replaceHolders.{$this -> driver}" ) )
		{
			preg_match_all ( '/(\?|:[a-zA-Z]{1,})/', $sql, $matches );
			
			$sql = strtr ( $sql, array_fill_keys ( $this -> matches = $matches[1], '?' ) );
		}
		else
		{
			$this -> matches = [];
		}
		
		return $sql;
	}
	
	public function executeHolders( array $execute, int $keys = 0 ): array
	{
		$new = [];
		# [ '?', '?', ':id', '?' ]
		foreach ( $this -> matches as $key => $plaseholder )
		{
			if ( $plaseholder == '?' )
			{
				$new[$key + $keys] = $execute[$key];
			}
			else
			{
				$new[$key + $keys] = $execute[$plaseholder];
			}
		}
		
		if ( ! $this -> matches && $keys && $execute )
		{
			return array_combine ( range ( $keys, array_key_last ( $execute ) + $keys ), $execute );
		}
		
		return $new ?: $execute;
	}
}