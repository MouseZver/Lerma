<?php

declare ( strict_types = 1 );

namespace Nouvu\Database;

use Nouvu\Database\Exception\LermaException;
use Nouvu\Database\Modules\Facade\ConnectDataInterface;

use function Nouvu\Database\Helpers\{ debug, queryFormatting, config };

class Lerma extends LermaCore implements LermaInterface
{
	protected bool $usingTransaction = false;
	
	public int $hash;
	
	private string $current_driver;
	
	/*
	config() -> set( 'drivers.mysql.module', \Nouvu\Database\Modules\MySQLi :: class );
	
	$lerma = Lerma :: create( driver: DriverEnum :: MySQLi )
		-> setData( host: '127.0.0.1', username: 'root', password: 'root' )
		-> setDatabaseName( dbname: 'test' )
		-> setCharset( charset: 'utf8' )
		-> setPort( port: 3306 )
		-> getLerma();
	*/
	public static function create( DriverEnum $driver ): ConnectDataInterface
	{
		$connectDataName = config( \Facade\Create :: class . '.' . $driver -> value );
		
		return new $connectDataName( fn(): static => new static ( $driver -> value ) );
	}
	
	/*
	config() -> set( 'drivers.mysql.module', \Nouvu\Database\Modules\MySQLi :: class );

	$lrm = new Lerma( dsn: 'mysql:charset=utf8' );
	*/
	public function __construct ( string | array $dsn )
	{
		$this -> parseDsn( is_array ( $dsn ) ? sprintf ( ...$dsn ) : $dsn );
    }
	
	public function prepare( string | array $sql, array $data = null ): LermaStatement
	{
		$this -> connect() -> free_result();
		
		queryFormatting( $sql );
		
		debug( true ) -> setRawQuery( $sql );
		
		$this -> replaceHolders( $sql );
		
		debug() -> setQuery( $sql );
		
		if ( strpbrk ( $sql, '?:' ) === false )
		{
			throw new LermaException( 'Request was rejected. There are no placeholders.' );
		}
		
		$this -> connect() -> prepare( $sql );
		
		$this -> connect() -> isError();
        
		if ( is_array ( $data ) )
		{
			$this -> execute( $data );
		}
		
		return new LermaStatement( $this );
	}
    
    public function query( string | array $sql ): LermaStatement
	{
        $this -> connect() -> free_result();
		
		queryFormatting( $sql );
		
		$this -> connect() -> query( $sql );
		
		$this -> connect() -> isError();
		
		return new LermaStatement( $this );
	}

	public function execute( array $data ): void
	{
		try
		{
			$first = reset ( $data );
			
			if ( ! is_array ( $first ) )
			{
				$this -> binding( [ $data ] );
				
				return;
			}
			
			$all = count ( $data );
			
			if ( $all > 1 && count ( $first ) * $all + $all != count ( $data, COUNT_RECURSIVE ) )
			{
				debug() -> setRawBindData( $data );
				
				throw new LermaException( 'Collected data in the array for execution is incorrect.' );
			}
			
			$this -> binding( $data );
		}
		catch ( LermaException $e )
		{
			$this -> rollBack();
            
			throw $e;
		}
	}

	public function rollBack( mixed ...$rollback ): bool
	{
		if ( $this -> usingTransaction )
		{
			$this -> usingTransaction = false;
			
			return $this -> connect() -> rollback( ...$rollback );
		}
		
		return false;
	}

	public function beginTransaction( mixed ...$rollback ): bool
	{
		if ( $this -> usingTransaction )
		{
			return false;
		}
		
		$this -> usingTransaction = true;
		
		return $this -> connect() -> beginTransaction( ...$rollback );
	}

	public function commit( mixed ...$commit ): bool
	{
		if ( $this -> usingTransaction )
		{
			$this -> usingTransaction = false;
			
			return $this -> connect() -> commit( ...$commit );
		}
		
		return false;
	}

	public function InsertID(): int
	{
		return $this -> connect() -> InsertID();
	}
}
