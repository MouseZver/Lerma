<?php

namespace Aero\Supports;

use Aero\
{
	Database\Migrate
	# Interfaces\Instance
};

final class Lerma extends Migrate # implements Instance
{
	public const 
		FETCH_NUM		= 1,
		FETCH_ASSOC		= 2,
		FETCH_OBJ		= 4,
		FETCH_BIND		= 663,
		FETCH_COLUMN	= 265,
		FETCH_KEY_PAIR	= 307,
		FETCH_NAMED		= 173,
		FETCH_UNIQUE	= 333,
		FETCH_GROUP		= 428,
		FETCH_FUNC		= 586,
		FETCH_CLASS		= 977,
		FETCH_CLASSTYPE	= 473;
	
	/* public static function select( array $execute, callable $callable )
	{
		self::load( __METHOD__, ( $execute ?: NULL ), $callable );
	}
	public static function insert( array $execute, callable $callable )
	{
		
	}
	public static function create( array $execute, callable $callable )
	{
		
	}
	public static function delete( array $execute, callable $callable )
	{
		
	} */
	public static function __callStatic( $method, $args )
	{
		if ( in_array ( $method, [ 'query', 'prepare' ] ) )
		{
			return self::$method( ...$args );
		}
		/* elseif ( isset ( self::$start[$method] ) )
		{
			return self::load( self::$start[$method], ...$args );
		} */
		
		return self::instance() -> driver -> $method( ...$args );
	}
}