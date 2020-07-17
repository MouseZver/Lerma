[![Latest Unstable Version](https://poser.pugx.org/Nouvu/lerma/v)](https://packagist.org/packages/nouvu/lerma) [![License](https://poser.pugx.org/nouvu/lerma/license)](https://packagist.org/packages/nouvu/lerma)

# Lerma
Multi-screwdriver for the database.

***
### Installation ( Установка ):
***
> composer require aero/lerma

or

Download Lerma src to root directory, edit directory name 'src/Lerma' on 'Aero' and create autoloader.

[Скачайте](https://github.com/MouseZver/Lerma/archive/v1.1.2.1.zip) библиотеку Lerma из src в корень вашего проекта и переименуйте папку в Aero. Создайте в единой точке автозагрузчик: 

```PHP
<?php

spl_autoload_register ( function ( $name )
{
	include strtr ( $name, [ '\\' => DIRECTORY_SEPARATOR ] ) . '.php';
} );
```

[Тестовый пример](https://github.com/MouseZver/Lerma/blob/master/tests/test.php)

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
