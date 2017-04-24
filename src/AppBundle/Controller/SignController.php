<?php

namespace AppBundle\Controller;

use AppBundle\Form\SessionEndType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
		$this->get( 'app.service.sign_service' )->in();

		return $this->redirectToRoute( 'homepage' );
	}

	/**
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/out")
	 */
	public function outAction()
	{
		$this->get( 'app.service.sign_service' )->out();

		return $this->redirectToRoute( 'homepage' );
	}

	/**
	 * @Route("/edit/lastout")
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editSignOut( Request $request )
	{
		$lastSession = $this->get( 'app.service.sign_service' )->getLastSessionEnd();
		if ( ! $lastSession )
		{
			return $this->redirectToRoute( 'homepage' );
		}

		$formType = $this->createForm( new SessionEndType( $lastSession ), $lastSession );
		$em = $this->get('doctrine.orm.default_entity_manager');

		if ( $request->getMethod() == Request::METHOD_POST )
		{
			$formType->handleRequest($request);
			if ($formType->isValid())
			{
				$em->flush($lastSession);
				return $this->redirectToRoute( 'homepage' );
			}

		}

		return $this->render(
			'@App/signin/lastout.html.twig',
			[ 'form' => $formType->createView() ]
		);
	}
}