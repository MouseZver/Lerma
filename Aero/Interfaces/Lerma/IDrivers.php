<?php

namespace Aero\Interfaces\Lerma;

interface IDrivers
{
	public function query	( string $sql ): IDrivers;
	public function prepare	( string $sql ): IDrivers;
	public function execute	( array $arguments );
	public function fetch	( int $fetch_style, $fetch_argument );
	public function fetchAll( int $fetch_style, $fetch_argument );
	public function InsertId(): int;
}