<?php

namespace Aero\Interfaces\Lerma;

interface IDrivers
{
	public function isError();
	
	/*
		- Простой запрос
	*/
	public function query( string $item );
	
	/*
		- Подготовленный запрос
	*/
	public function prepare( string $item );
	
	/*
		- Посылаем данные в бд по подготовленному запросу
	*/
	public function execute();
	
	/*
		- 
	*/
	public function bindParam( array ...$items );
	
	/*
		- 
	*/
	public function bindResult( $item );
	
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
	public function countColumn(): int;
	
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