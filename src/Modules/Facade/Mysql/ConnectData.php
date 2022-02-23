<?php

declare ( strict_types = 1 );

namespace Nouvu\Database\Modules\Facade\Mysql;

use Nouvu\Database\Lerma;
use Nouvu\Database\Modules\Facade\ConnectDataInterface;

use function Nouvu\Database\Helpers\{ config };

final class ConnectData implements ConnectDataInterface
{
	public function __construct ( private \Closure $closure )
	{}
	
	private function setValues( array $args ): void
	{
		foreach ( $args AS $key => $value )
		{
			if ( ! is_null ( $value ) )
			{
				config() -> set( "drivers.mysql.{$key}", $value );
			}
		}
	}
	
	public function setData( string $host = null, string $username = null, string $password = null ): self
	{
		$this -> setValues( compact ( [ 'host', 'username', 'password' ] ) );
		
		return $this;
	}
	
	public function setDatabaseName( string $dbname ): self
	{
		$this -> setValues( compact ( [ 'dbname' ] ) );
		
		return $this;
	}
	
	public function setCharset( string $charset ): self
	{
		$this -> setValues( compact ( [ 'charset' ] ) );
		
		return $this;
	}
	
	public function setPort( int $port ): self
	{
		$this -> setValues( compact ( [ 'port' ] ) );
		
		return $this;
	}
	
	public function getLerma(): Lerma
	{
		return ( $this -> closure )();
	}
}