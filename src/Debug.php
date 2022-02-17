<?php

declare ( strict_types = 1 );

namespace Nouvu\Database;

final class Debug
{
	public function setRawQuery( string $query ): void
	{
		$this -> rawQuery = $query;
	}

	public function setQuery( string $query ): void
	{
		$this -> query = $query;
	}

	public function setRawBindData( array $bind ): void
	{
		$this -> rawBindData = $bind;
	}

	public function setBindData( array $bind ): void
	{
		$this -> bindData = $bind;
	}
}
