<?php

use Aero\Database\Placebo;

require 'autoload.php';

$bilder = function ( string $name, callable $call )
{
	$p = new Placebo( $name );
	
	$call( $p );
	
	return $p -> get();
};

/* echo $bilder( 'SELECT', function ( Placebo $p )
{
	$p -> table( 'wer' );
} ); */

echo $bilder( 'INSERT', function ( Placebo $p )
{
	$p -> table( 'wer' );
	$p -> columns( 'f' );
} );