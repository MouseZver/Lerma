<?php

declare ( strict_types = 1 );

namespace Nouvu\Database\Modules\Facade;

use Nouvu\Database\Lerma;

interface ConnectDataInterface
{
	public function getLerma(): Lerma;
}