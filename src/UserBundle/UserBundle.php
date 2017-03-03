<?php
namespace UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;


/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 3/3/17
 * Time: 2:17 AM
 */
class UserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}