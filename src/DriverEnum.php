<?php

declare ( strict_types = 1 );

namespace Nouvu\Database;

enum DriverEnum: string
{
	case MySQLi = 'mysql';
	case SQLite3 = 'sqlite';
}