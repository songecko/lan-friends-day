<?php

namespace Gecko\BackendBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GeckoBackendBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
