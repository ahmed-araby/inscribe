<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TimesheetController extends Controller
{
	/**
	 * @param $name
	 * @Route("/timesheet")
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function indexAction()
    {
	    return $this->render('@App/timesheet/index.html.twig');
    }
}
