<?php

declare ( strict_types = 1 );

namespace Nouvu\Database\Modules\Facade\Sqlite;

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
				config() -> set( "drivers.sqlite.{$key}", $value );
			}
		}
	}
	
	public function setFile( string $db ): self
	{
		$this -> setValues( compact ( [ 'db' ] ) );
		
		return $this;
	}
	
	public function getLerma(): Lerma
	{
		return ( $this -> closure )();
	}
}