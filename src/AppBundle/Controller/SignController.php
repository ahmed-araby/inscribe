<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class SignController
 * @package AppBundle\Controller
 * @Route("/session")
 */
class SignController extends Controller
{
	/**
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/in")
	 */
    public function inAction()
    {
	    $this->get('app.service.sign_service')->in();
        return $this->redirectToRoute('homepage');
    }

	/**
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/out")
	 */
	public function outAction()
	{
		$this->get('app.service.sign_service')->out();
		return $this->redirectToRoute('homepage');
	}
}
