<?php

declare ( strict_types = 1 );

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ url-source: http://github.com/MouseZver/Lerma
	@ php-version 8.0
*/

namespace Nouvu\Database;

interface InterfaceLerma
{
	public const
		FETCH_NUM			= 1,
		FETCH_ASSOC			= 2,
		FETCH_OBJ			= 4,
		MYSQL_FETCH_BIND	= 663,
		FETCH_COLUMN		= 265,
		FETCH_KEY_PAIR		= 307,
		FETCH_NAMED			= 173,
		FETCH_UNIQUE		= 333,
		FETCH_GROUP			= 428,
		FETCH_FUNC			= 586,
		MYSQL_FETCH_FIELD	= 343;
	
	public function prepare( string | array $sql, array $items ): LermaStatement;
	
	public function execute( array $items ): int | InterfaceLerma;
	
	public function query( $sql ): LermaStatement;
	
	public function rollBack( ...$rollback ): bool;
	
	public function beginTransaction( ...$rollback ): bool;
	
	public function commit( ...$commit ): bool;
	
	public function InsertID(): int;
	
	public static function getAvailableDrivers(): array;
}