<?php

declare ( strict_types = 1 );

namespace Nouvu\Database;

interface ModuleInterface
{
	public function isError(): void;
	
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
	public function binding( array $items ): void;
	
	/*
		- очистка
	*/
	public function close(): self;
	
	/*
		- Стиль возвращаемого результата с одной строки
	*/
	public function fetch( int $int ): mixed;
	
	/*
		- Стиль возвращаемого результата со всех строк
	*/
	public function fetchAll( int $int ): mixed;
	
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
	public function rollBack( mixed ...$items ): bool;
	
	/*
		- Стартует транзакцию
	*/
	public function beginTransaction( mixed ...$items ): bool;
	
	/*
		- Завершает текущую транзакцию
	*/
	public function commit( mixed ...$items ): bool;
	
	/*
		- вернуть объект самого драйвера
	*/
	public function get(): mixed;
}