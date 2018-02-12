<?php

namespace Aero\Database;

use Exception AS Error;

class LermaStatement
{
	protected $statement;
	protected $matches;							# Placeholders
	protected $pattern = '/(\?|:[a-z]{1,})/';	# Поиск плейсхолдеров в запросе
	
	/*
		- Определение подготовленного запроса на форматирование строки
	*/
	protected static function prepare( $sql ): LermaStatement
	{
		if ( strpos ( $sql, '?' ) === false )
		{
			throw new Error( 'Метки параметров запроса отсутствуют. Используйте функцию query' );
		}
		
		static::instance() -> statement = static::instance() -> driver -> prepare( $sql );
		
		static::instance() -> driver -> isError();
		
		return static::instance();
	}
	
	/*
		- Простая подстановка элементов в запрос
		- и
		- Поиск ':', замена placeholders на '?'
	*/
	protected function replaceHolders( &$sql )
	{
		$this -> matches = [];
		
		$sql = ( is_array ( $sql ) ? sprintf ( ...$sql ) : $sql );
		
		if ( strpos ( $sql, ':' ) !== false )
		{
			preg_match_all ( $this -> pattern, $sql, $matches );
			
			$this -> matches = $matches[1];
			
			$sql = strtr ( $sql, array_fill_keys ( $this -> matches, '?' ) );
		}
	}
	
	/*
		- Проверяем данные на мульти-запрос
	*/
	protected function isMulti( array $array ): bool
	{
		if ( !is_array ( current ( $array ) ) )
		{
			foreach ( $array AS $items )
			{
				if ( is_array ( $items ) )
				{
					throw new Error( 'Ошибка в добавлении, запрос не выполнен. Ожидался не многомерный массив.' );
				}
			}
			
			return false;
		}
		
		return true;
	}
	
	/*
		- Многократное добавление данных подготовленного запроса в бд
	*/
	protected function multiExecute( array $executes ): array
	{
		$b = [];
		
		foreach ( $executes AS $s => $execute )
		{
			if ( !is_array ( $execute ) )
			{
				throw new Error( 'Ошибка в мульти добавлении, ожидался массив. Ступень: ' . $s );
			}
			
			$b[] = $this -> execute( $execute );
		}
		
		return $b;
	}
	
	/*
		- Посылаем данные в астрал
	*/
	protected function execute( array $execute )
	{
		if ( $this -> statement === null )
		{
			throw new Error( 'This is not statement' );
		}
		
		$types = $a = [];
		
		$execute = array_values ( !empty ( $this -> matches ) ? $this -> executeHolders( $execute ) : $execute );
		
		extract ( $execute, EXTR_PREFIX_ALL, 'bind' );
			
		foreach ( $execute AS $k => $v )
		{
			if ( !in_array ( $type = gettype ( $v ), [ 'integer', 'double', 'string' ] ) )
			{
				throw new Error( 'Invalid type ' . $type );
			}
			
			$a[] = &${ 'bind_' . $k };
			
			$types[] = $type{0};
		}
		
		$this -> driver -> bindParam( $types, $a );
		
		$bool = $this -> driver -> execute();
		
		$this -> driver -> isError( $this -> statement );
		
		return ( $this -> driver -> countColumn() === 0 ? $bool : $this );
	}
	
	/*
		- Реформирование данных в массиве по найденным placeholders
	*/
	protected function executeHolders( array $execute ): array
	{
		$new = [];
		
		foreach ( $this -> matches as $plaseholder )
		{
			if ( $plaseholder === '?' )
			{
				$new[] = array_shift ( $execute );
			}
			else
			{
				if ( isset ( $new[$plaseholder] ) )
				{
					$new[] = $new[$plaseholder];
				}
				else
				{			
					$new[$plaseholder] = $execute[$plaseholder] ?? null;
					
					unset ( $execute[$plaseholder] );
				}
			}
		}

		return $new;
	}
}
