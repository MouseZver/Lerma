# Lerma
Multi-screwdriver for the database.

***
### Installation:
***
> composer require aero/lerma=def-master

or

Download Lerma src to root directory, edit directory name 'src/Lerma' on 'Aero' and create autoloader.
```PHP
<?php

spl_autoload_register ( function ( $name )
{
	include strtr ( $name, [ '\\' => DIRECTORY_SEPARATOR ] ) . '.php';
} );
```

***
### Configures:
***
> directory: src/Lerma/Configures/Lerma.php

```PHP
<?php

namespace Aero\Configures;

class Lerma
{
	private const USER = 'root';
	private const PASSWORD = '';
	
	# Назначение драйвера для подключения базы данных
	public $driver = 'mysqli';
	
	# Параметры для драйвера mysqli
	public $mysqli = [
		'host' => '127.0.0.1',
		'user' => self::USER,
		'password' => self::PASSWORD,
		'dbname' => 'single',
		'port' => 3306
	];
};
```

***
### Start Project:
***

```PHP
<?php

use Aero\Supports\Lerma;

# Autoloader <name.php>

/* 
Lerma:: ...
*/
```