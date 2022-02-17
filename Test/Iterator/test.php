<?php

require dirname ( __DIR__, 2 ) . '/init.php';

$statement = $lerma -> query( 'SELECT * FROM `github_test`' );

foreach ( $statement AS $test )
{
	print_r ( $test );
}