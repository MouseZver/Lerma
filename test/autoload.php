<?php

spl_autoload_register ( function ( $name )
{
	include strtr ( '..\\src\\' . $name, [ '\\' => DIRECTORY_SEPARATOR ] ) . '.php';
} );