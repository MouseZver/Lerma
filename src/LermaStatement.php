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
use Nouvu\Config;

final class LermaStatement extends ComponentFetch
{
	protected Config $config;
	
	protected InterfaceDriver $InterfaceDriver;
	
	protected array $bind_result = [];
	
	private Lerma $lerma;
	
	private int $hash;
	
	public function __construct( Lerma $lerma, InterfaceDriver $InterfaceDriver, Config $config )
	{
		$this -> InterfaceDriver = $InterfaceDriver;
		
		$this -> config = $config;
		
		$this -> lerma = $lerma;
		
		$this -> hash = $this -> lerma -> hash = mt_rand ();
	}
	
	private function hash(): void
	{
		if ( $this -> hash != $this -> lerma -> hash )
		{
			throw new Error( $this -> config -> get( 'errMessage.statement.hash' ) );
		}
	}
	
	/*
		- Контроль доступа к стилям
		- Стиль возвращаемого результата с одной строки
		- fetch_style - Идентификатор выбираемого стиля. Default Lerma :: FETCH_NUM
		- fetch_argument - атрибут для совершения действий над данными
	*/
	public function fetch( int $fetch_style = Lerma :: FETCH_NUM, $fetch_argument = null )
	{
		$this -> hash();
		
		if ( $this -> _fetch[$fetch_style][0] ?? null )
		{
			return $this -> {$this -> _fetch[$fetch_style][0]}( $fetch_style, $fetch_argument );
		}
		
		throw new Error( $this -> config -> get( 'errMessage.statement.keyName' ) );
	}

	/*
		- Контроль доступа к стилям
		- Стиль возвращаемого результата со всех строк
		- fetch_style - Идентификатор выбираемого стиля. Default Lerma :: FETCH_NUM
		- fetch_argument - атрибут для совершения действий над данными
	*/
	public function fetchAll( int $fetch_style = Lerma :: FETCH_NUM, $fetch_argument = null )
	{
		$this -> hash();
		
		if ( $this -> _fetch[$fetch_style]['all'] ?? null )
		{
			return $this -> {$this -> _fetch[$fetch_style]['all']}( $fetch_style, $fetch_argument );
		}
		
		throw new Error( $this -> config -> get( 'errMessage.statement.keyName' ) );
	}
	
	/*
		- Кол-во возвращаемых строк, затронутых запросом
	*/
	public function rowCount(): int
	{
		$this -> hash();
		
		return $this -> InterfaceDriver -> rowCount();
	}
	
	/*
		- Кол-во возвращаемых колонок
	*/
	public function columnCount(): int
	{
		$this -> hash();
		
		return $this -> InterfaceDriver -> columnCount();
	}
	
	/*
		- Создание переменных подготовленного запроса для данных с астрала
	*/
	protected function bind(): InterfaceDriver
	{
		$this -> hash();
		
		if ( $this -> bind_result == [] )
		{
			$i = 0;
			
			while ( ( $i++ ) < $this -> InterfaceDriver -> columnCount() )
			{
				$this -> bind_result[] = &${ 'result_' . $i };
			}
			
			$this -> InterfaceDriver -> bindResult( $this -> bind_result );
		}
		
		return $this -> InterfaceDriver;
	}
}