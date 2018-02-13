<?php

spl_autoload_register ( function ( $name )
{
	$replaces = [ 
		'\\' => DIRECTORY_SEPARATOR, 
		
		# Aero Lerma
		'Aero\\' => '../src/Lerma/' 
	];
	
	include strtr ( $name, $replaces ) . '.php';
} );