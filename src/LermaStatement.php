<?php

declare ( strict_types = 1 );

namespace Nouvu\Database;

use Nouvu\Database\Exception\LermaStatementException;

use function Nouvu\Database\Helpers\{ connect, config, getExtension };

final class LermaStatement extends ComponentMode implements \IteratorAggregate
{
	protected array $bind_result = [];
	
	private int $hash;
	
	public function __construct ( protected LermaInterface $lerma )
	{
		$this -> hash = $lerma -> hash = mt_rand ();
	}
	
	private function hashVerify(): void
	{
		if ( $this -> hash != $this -> lerma -> hash )
		{
			throw new LermaStatementException( 'Previous API methods usage denied after new request' );
		}
	}
	
	/*
		- Контроль доступа к режиму вывода
		- Режим возвращаемого результата с одной строки
		- mode - Идентификатор возвращаемого стиля. Default Lerma :: FETCH_NUM
		- argument - атрибут для совершения действий над данными
	*/
	public function fetch( int $mode = null, \Closure | string | null $argument = null ): mixed
	{
		$mode ??= config( 'mode' );
		
		$this -> hashVerify();
		
		if ( isset ( Lerma :: MODE[ $mode ][0] ) )
		{
			$fetch = $this -> {Lerma :: MODE[ $mode ][0]}( $mode, $argument );
			
			if ( $fetch instanceOf \Generator )
			{
				$result = $fetch -> current();
				
				$fetch -> next();
				
				return $result;
			}
			else 
			{
				return $fetch;
			}
		}
		
		throw new LermaStatementException( 'Selected mode for the result was not found' );
	}

	/*
		- Контроль доступа к режиму вывода
		- Режим возвращаемого результата со всех строк
		- mode - Идентификатор возвращаемого стиля. Default Lerma :: FETCH_NUM
		- argument - атрибут для совершения действий над данными
	*/
	public function fetchAll( int $mode = null, \Closure | string | null $argument = null ): iterable
	{
		$mode ??= config( 'mode' );
		
		$this -> hashVerify();
		
		if ( isset ( Lerma :: MODE[ $mode ]['all'] ) )
		{
			$fetch = $this -> {Lerma :: MODE[ $mode ]['all']}( $mode, $argument );
			
			if ( $fetch instanceOf \Generator )
			{
				return iterator_to_array ( $fetch );
			}
			else 
			{
				return $fetch;
			}
		}
		
		throw new LermaStatementException( 'Selected mode for the result was not found' );
	}
	
	/*
		- Использование напрямую интератора, от самого драйвера. Только MySQLi
	*/
	public function getIterator(): \Traversable
	{
		$this -> hashVerify();
		
		if ( DriverEnum :: MySQLi -> value == getExtension() )
		{
			return connect( $this -> lerma ) -> result();
		}
		
		throw new LermaStatementException( 'Only MySQLi can use iteration' );
	}
	
	/*
		- Кол-во возвращаемых строк, затронутых запросом
	*/
	public function rowCount(): int
	{
		$this -> hashVerify();
		
		return connect( $this -> lerma ) -> rowCount();
	}
	
	/*
		- Кол-во возвращаемых колонок
	*/
	public function columnCount(): int
	{
		$this -> hashVerify();
		
		return connect( $this -> lerma ) -> columnCount();
	}
	
	/*
		- Создание переменных подготовленного запроса для данных с астрала
	*/
	protected function bind(): ModuleInterface
	{
		$this -> hashVerify();
		
		if ( empty ( $this -> bind_result ) )
		{
			$i = 0;
			
			while ( ( $i++ ) < $this -> columnCount() )
			{
				$this -> bind_result[] = &${ 'result_' . $i };
			}
			
			connect( $this -> lerma ) -> bindResult( $this -> bind_result );
		}
		
		return connect( $this -> lerma );
	}
}