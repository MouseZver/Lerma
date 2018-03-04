[![Latest Unstable Version](https://poser.pugx.org/aero/lerma/v/unstable)](https://packagist.org/packages/aero/lerma) [![License](https://poser.pugx.org/aero/lerma/license)](https://packagist.org/packages/aero/lerma) [![composer.lock](https://poser.pugx.org/aero/lerma/composerlock)](https://packagist.org/packages/aero/lerma)

# Lerma
Multi-screwdriver for the database.

***
### Installation ( Установка ):
***
> composer require aero/lerma

or

Download Lerma src to root directory, edit directory name 'src/Lerma' on 'Aero' and create autoloader.
Скачайте библиотеку Lerma из src, в корень вашего проекта и переименуйте в Aero. Создайте в единой точке автозагрузчик: 
```PHP
<?php

spl_autoload_register ( function ( $name )
{
	include strtr ( $name, [ '\\' => DIRECTORY_SEPARATOR ] ) . '.php';
} );
```

***
### Configures ( Данные для подключения ):
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
### Start Project ( Начало работы ):
***

```PHP
<?php

use Aero\Supports\Lerma;

# Autoloader <name.php>

/* 
Lerma:: ...
*/
```

***

[Lerma Wiki](https://github.com/MouseZver/Lerma/wiki)

Create by [MouseZver](https://php.ru/forum/members/40235)
