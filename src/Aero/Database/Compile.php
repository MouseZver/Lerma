<?php

namespace Aero\Database;

use Aero\Database\Placebo;
use Exception AS Error;

class Compile
{
	protected $operators = [
		'=', '!=', '<', '>', '<=', '>=', '<>',
		'not',
	];
	
	protected function columnsCompile()
	{
		if ( empty ( $this -> structures[$this -> command]['columns'] ) )
		{
			switch ( $this -> command )
			{
				case 'SELECT':
					$this -> structures[$this -> command]['columns'] = 'SELECT `*`';
				break;
				default:
					unset ( $this -> structures[$this -> command]['columns'] );
			}
			
			return;
		}
		
		switch ( $this -> command )
		{
			case 'SELECT':
				$this -> structures[$this -> command]['columns'] = sprintf ( 'SELECT %s', implode ( ', ', $this -> structures[$this -> command]['columns'] ) );
			break;
			case 'INSERT':
				$this -> structures[$this -> command]['columns'] = sprintf ( '( %s )', $this -> structures[$this -> command]['columns'] );
			break;
		}
	}
	
	
	protected function tableCompile()
	{
		if ( empty ( $this -> structures[$this -> command]['table'] ) )
		{
			throw new Error( 'table...' );
		}
		
		switch ( $this -> command )
		{
			case 'SELECT':
				$this -> structures[$this -> command]['table'] = sprintf ( 'FROM %s', implode ( ', ', $this -> structures[$this -> command]['table'] ) );
			break;
			case 'INSERT':
				$this -> structures[$this -> command]['table'] = sprintf ( 'INSERT INTO %s', $this -> structures[$this -> command]['table'] );
			break;
		}
	}
	
	
	protected function joinCompile()
	{
		unset ( $this -> structures[$this -> command]['join'] );
	}
	
	
	protected function whereCompile()
	{
		foreach ( $a AS [ $a, $b, $c ] )
		{
			if (  )
		}
		
		unset ( $this -> structures[$this -> command]['where'] );
	}
	
	
	protected function groupCompile()
	{
		unset ( $this -> structures[$this -> command]['group'] );
	}
	
	
	protected function havingsCompile()
	{
		unset ( $this -> structures[$this -> command]['havings'] );
	}
	
	
	protected function orderCompile()
	{
		unset ( $this -> structures[$this -> command]['order'] );
	}
	
	
	protected function limitCompile()
	{
		if ( empty ( $this -> structures[$this -> command]['limit'] ) )
		{
			unset ( $this -> structures[$this -> command]['limit'] );
		}
	}
}