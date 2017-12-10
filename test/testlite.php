<?php

require 'LermaLite.php';

# ------------------------

$query = Lerma::query( 'SELECT * FROM lerma' ) -> fetchAll( Lerma::FETCH_OBJ );

# ------------------------

$prepare = Lerma::prepare( 'SELECT * FROM lerma WHERE id IN ( ?,?,? )' ) -> execute( [ 3,9,81 ] ) -> fetchAll( Lerma::FETCH_OBJ );

# ------------------------

print_r ( compact ( 'query', 'prepare' ) );