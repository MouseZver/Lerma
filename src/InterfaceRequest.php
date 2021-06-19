<?php

declare ( strict_types = 1 );

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ url-source: http://github.com/MouseZver/Lerma
	@ php-version 8.0
*/

namespace Nouvu\Database;

interface InterfaceRequest
{
	public const FETCH = [
		Lerma :: FETCH_NUM => [ 'fetch_num', 'all' => 'fetchall_num' ],
		Lerma :: FETCH_ASSOC => [ 'fetch_assoc', 'all' => 'fetchall_assoc' ],
		Lerma :: FETCH_OBJ => [ 'fetch_obj', 'all' => 'fetchall_obj' ],
		Lerma :: MYSQL_FETCH_FIELD => [ 'fetch_field', 'all' => 'fetchall_field' ],
		Lerma :: MYSQL_FETCH_BIND => [ 'fetch_bind' ],
		Lerma :: MYSQL_FETCH_BIND | Lerma :: FETCH_COLUMN => [ 'fetch_bind' ],
		Lerma :: FETCH_COLUMN => [ 'fetch_column', 'all' => 'fetchall_obj' ],
		Lerma :: FETCH_KEY_PAIR => [ 'fetch_key_pair', 'all' => 'fetchall_key_pair' ],
		Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_NAMED => [ 'all' => 'fetchall_key_pair' ],
		Lerma :: FETCH_KEY_PAIR | Lerma :: FETCH_FUNC => [ 'all' => 'fetchall_key_pair' ],
		Lerma :: FETCH_FUNC => [ 'fetch_func', 'all' => 'fetchall_obj' ],
		Lerma :: FETCH_UNIQUE => [ 'all' => 'fetchall_unique' ],
		Lerma :: FETCH_GROUP => [ 'all' => 'fetchall_group' ],
		Lerma :: FETCH_GROUP | Lerma :: FETCH_COLUMN => [ 'all' => 'fetchall_group_column' ],
	];
}