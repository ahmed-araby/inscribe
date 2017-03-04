<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 3/3/17
 * Time: 11:39 PM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Session;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class SignService
 * @package AppBundle\Service
 */
class SignService
{
	/** @var EntityManager */
	protected $em;
	/** @var TokenStorage */
	protected $tokenStorage;

	/**
	 * SignService constructor.
	 *
	 * @param EntityManager $entityManager
	 * @param TokenStorage $tokenStorage
	 */
	public function __construct( EntityManager $entityManager, TokenStorage $tokenStorage )
	{
		$this->em           = $entityManager;
		$this->tokenStorage = $tokenStorage;
	}

	/**
	 * @return User
	 * @throws UnauthorizedHttpException
	 */
	private function getUser()
	{
		$user = $this->tokenStorage->getToken()->getUser();
		if (!$user instanceof User)
		{
			throw new UnauthorizedHttpException("Current user not logged in");
		}

		return $user;
	}

	/**
	 * @return Session
	 */
	public function getCurrentUserOpenSession()
	{
		$user = $this->getUser();
		$repo = $this->em->getRepository('AppBundle:Session');
		return $repo->getLatestWithoutOffSessionByUser($user);
	}

	/**
	 * @throws \RuntimeException
	 */
	public function in()
	{
		if ($this->getCurrentUserOpenSession() instanceof Session)
		{
			throw new \RuntimeException("Can not sign in while there is active session");
		}
		$user = $this->getUser();
		$session = new Session();
		$session->setUser($user);
		$this->em->persist($session);
		$this->em->flush($session);
	}

	public function getMonthTotalInterval(  )
	{
		$user = $this->getUser();
		$repo = $this->em->getRepository('AppBundle:Session');
		if (date('%d') > 23)
		{
			$month = date('M');
		}else{
			$month = date('M', strtotime('last month'));
		}
		$monthSTart = new \DateTime("23 $month");
		$sessions =  $repo->getTodayTotalLoggedSessions($user);
		if (!$sessions || count($sessions) ==0)
		{
			return null;
		}

		$minutes = 0;
		foreach ($sessions AS $session)
		{
			$minutes += $session->getPeriod();
		}

		return $minutes;
	}


	/**
	 * @return int
	 */
	public function getTodayTotalInterval()
	{
		$user = $this->getUser();
		$repo = $this->em->getRepository('AppBundle:Session');
		$sessions =  $repo->getTodayTotalLoggedSessions($user);
		if (!$sessions || count($sessions) ==0)
		{
			return null;
		}

		$minutes = 0;
		foreach ($sessions AS $session)
		{
			$minutes += $session->getPeriod();
		}

		return $minutes;
	}

	/**
	 * @throws \RuntimeException
	 */
	public function out()
	{
		$session = $this->getCurrentUserOpenSession();
		if (!$session instanceof Session)
		{
			throw new \RuntimeException("Can not sign out while there is no active session");
		}
		$getPassedInSeconds = $session->getPassedInSeconds();
		$session->setPeriod(ceil($getPassedInSeconds/60));
		$session->setEndedAt(new \DateTime());
		$this->em->persist($session);
		$this->em->flush($session);
	}
}