<?php

namespace AppBundle;

use AppBundle\DependencyInjection\ServiceLoaderExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
	public function getContainerExtension()
	{
		return new ServiceLoaderExtension();
	}
}
