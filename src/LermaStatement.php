<?php

declare ( strict_types = 1 );

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ url-source: http://github.com/MouseZver/Lerma
	@ php-version 8.0
*/

namespace Nouvu\Database;

use Error;
use Nouvu\Config\Config;
use Nouvu\Database\InterfaceRequest AS Request;

final class LermaStatement extends ComponentFetch implements Request
{
	protected array $bind_result = [];
	
	private int $hash;
	
	public function __construct ( private InterfaceLerma $lerma, protected InterfaceDriver $InterfaceDriver, protected Config $config )
	{
		$this -> hash = $this -> lerma -> hash = mt_rand ();
	}
	
	private function hash(): void
	{
		if ( $this -> hash != $this -> lerma -> hash )
		{
			throw new RequestException( code: 200 );
		}
	}
	
	/*
		- Контроль доступа к стилям
		- Стиль возвращаемого результата с одной строки
		- fetch_style - Идентификатор выбираемого стиля. Default Lerma :: FETCH_NUM
		- fetch_argument - атрибут для совершения действий над данными
	*/
	public function fetch( int $fetch_style = Lerma :: FETCH_NUM, callable | string $fetch_argument = null ): mixed
	{
		$this -> hash();
		
		if ( Request :: FETCH[$fetch_style][0] ?? null )
		{
			return $this -> {Request :: FETCH[$fetch_style][0]}( $fetch_style, $fetch_argument );
		}
		
		throw new RequestException( code: 201 );
	}

	/*
		- Контроль доступа к стилям
		- Стиль возвращаемого результата со всех строк
		- fetch_style - Идентификатор выбираемого стиля. Default Lerma :: FETCH_NUM
		- fetch_argument - атрибут для совершения действий над данными
	*/
	public function fetchAll( int $fetch_style = Lerma :: FETCH_NUM, callable | string $fetch_argument = null ): mixed
	{
		$this -> hash();
		
		if ( Request :: FETCH[$fetch_style]['all'] ?? null )
		{
			return $this -> {Request :: FETCH[$fetch_style]['all']}( $fetch_style, $fetch_argument );
		}
		
		throw new RequestException( code: 201 );
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
		
		if ( empty ( $this -> bind_result ) )
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