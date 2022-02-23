<?php

declare ( strict_types = 1 );

namespace Nouvu\Database;

interface LermaInterface
{
	public const
		FETCH_NUM = 1,
		FETCH_ASSOC = 2,
		FETCH_OBJ = 4,
		MYSQL_FETCH_BIND = 663,
		FETCH_COLUMN = 265,
		FETCH_KEY_PAIR = 307,
		FETCH_NAMED = 173,
		FETCH_UNIQUE = 333,
		FETCH_GROUP = 428,
		FETCH_FUNC = 586,
		MYSQL_FETCH_FIELD = 343;
	
	public const MODE = [
		Lerma :: FETCH_NUM => [
			'fetch_num', 
			'all' => 'fetch_num_all' 
		],
		Lerma :: FETCH_ASSOC => [
			'fetch_assoc', 
			'all' => 'fetch_assoc_all' 
		],
		Lerma :: FETCH_OBJ => [
			'fetch_obj', 
			'all' => 'fetch_obj_all' 
		],
		Lerma :: FETCH_COLUMN => [ 
			'fetch_column', 
			'all' => 'fetch_obj_all' 
		],
		Lerma :: FETCH_FUNC => [ 
			'fetch_func', 
			'all' => 'fetch_obj_all' 
		],
		Lerma :: FETCH_KEY_PAIR => [ 
			'fetch_key_pair', 
			'all' => 'fetch_key_pair_all' 
		],
		//Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_NAMED => [ 'all' => 'fetchall_key_pair' ],
		Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC => [ 'all' => 'fetch_key_pair_all' ],
		Lerma :: FETCH_UNIQUE => [ 'all' => 'fetch_unique_all' ],
		Lerma :: FETCH_GROUP => [ 'all' => 'fetch_group_all' ],
		Lerma :: FETCH_GROUP | Lerma :: FETCH_COLUMN => [ 'all' => 'fetch_group_column_all' ],
		Lerma :: MYSQL_FETCH_FIELD => [ 
			'fetch_field', 
			'all' => 'fetch_field_all' 
		],
		Lerma :: MYSQL_FETCH_BIND => [ 'fetch_bind' ],
		Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN => [ 'fetch_bind' ],
	];
	
	public function prepare( string | array $sql, array $data ): LermaStatement;
	
	public function execute( array $data ): void;
	
	public function query( string | array $sql ): LermaStatement;
	
	public function rollBack( ...$rollback ): bool;
	
	public function beginTransaction( ...$rollback ): bool;
	
	public function commit( ...$commit ): bool;
	
	public function InsertID(): int;
}