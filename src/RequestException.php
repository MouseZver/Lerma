<?php

declare ( strict_types = 1 );

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ url-source: http://github.com/MouseZver/Lerma
	@ php-version 8.0
*/

namespace Nouvu\Database;

class RequestException extends \Exception
{
	protected array $code = [
		100 => 'Error in driver selection: %s',
		110 => 'Argument 2 is empty',
		111 => 'Missing pseudo-variables in the request',
		200 => 'Session ended by calling another',
		201 => 'unrecognized key name in fetch_style argument',
		210 => 'You only need to select one column',
		211 => 'You only need to select two columns',
		212 => 'Allowed number of selected columns at least two',
		220 => 'For proper operation, do not use the rowСount method during sampling in the unbuffered result',
		300 => 'Error connect (%s) %s',
	];
	
	public function __construct ( array | string $messages = null, int $code = 0 )
	{
		if ( isset ( $this -> code[$code] ) )
		{
			$messages = call_user_func_array ( 'sprintf', array_merge ( [ $this -> code[$code] ], ( is_array ( $messages ) ? $messages : [ $messages ] ) ) );
		}
		else if ()
		{
			$messages = implode ( ',', $messages );
		}
		
		parent :: __construct ( $messages );
	}
}