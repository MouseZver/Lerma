<?php

declare ( strict_types = 1 );

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ url-source: http://github.com/MouseZver/Lerma
	@ php-version 7.4
*/

namespace Nouvu\Database;

interface InterfaceDriver
{
	public function isError();
	
	/*
		- Простой запрос
	*/
	public function query( string $item ): void;
	
	/*
		- Подготовленный запрос
	*/
	public function prepare( string $item ): void;
	
	/*
		- посылаем данные
	*/
	public function binding( array $items );
	
	/*
		- 
	*/
	public function close();
	
	/*
		- Стиль возвращаемого результата с одной строки
	*/
	public function fetch( int $int );
	
	/*
		- Стиль возвращаемого результата со всех строк
	*/
	public function fetchAll( int $int );
	
	/*
		- Кол-во затронутых колонок
	*/
	public function columnCount(): int;
	
	/*
		- Возвращает кол-во затронутых строк
	*/
	public function rowCount(): int;
	
	/*
		- Ид последней добавленной строки
	*/
	public function InsertID(): int;
	
	/*
		- Откат текущей транзакции
	*/
	public function rollBack( ...$items ): bool;
	
	/*
		- Стартует транзакцию
	*/
	public function beginTransaction( ...$items ): bool;
	
	/*
		- Завершает текущую транзакцию
	*/
	public function commit( ...$items ): bool;
}