<?php

declare ( strict_types = 1 );

namespace Nouvu\Database\Helpers;

use Nouvu\Config\Config;
use Nouvu\Database\{ Lerma, Debug, ModuleInterface };

function config( string $offset = null ): mixed
{
	static $config;
	
	$config ??= new Config( include __DIR__ . '/default/config.php', '.' );
	
	if ( is_null ( $offset ) )
	{
		return $config;
	}
	
	return $config -> get( $offset );
}

function getExtension( Lerma $lerma ): string
{
	$closure = function (): string
	{
		return $this -> currentExtension;
	};
	
	return $closure -> call( $lerma );
}

function debug( bool $reset = false ): Debug
{
	static $debug;
	
	if ( is_null ( $debug ) || $reset )
	{
		$debug = new Debug;
	}
	
	return $debug;
}

function queryFormatting( string | array &$sql ): void
{
    if ( is_array ( $sql ) )
    {
        $sql = sprintf ( ...$sql );
    }
}