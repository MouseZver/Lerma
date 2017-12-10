<?php

spl_autoload_register ( function ( $name )
{
	include strtr ( $name, [ '\\' => DIRECTORY_SEPARATOR ] ) . '.php';
} );