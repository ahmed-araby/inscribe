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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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
	/** @var AuthorizationCheckerInterface  */
	protected $authorizationChecker;

	/**
	 * SignService constructor.
	 *
	 * @param EntityManager $entityManager
	 * @param TokenStorage $tokenStorage
	 */
	public function __construct(
		EntityManager $entityManager,
		TokenStorage $tokenStorage,
		AuthorizationCheckerInterface $authorizationChecker
	)
	{
		$this->em           = $entityManager;
		$this->tokenStorage = $tokenStorage;
		$this->authorizationChecker  = $authorizationChecker;
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
		$monthSTart = $this->getCurrentMonthStart();
		$sessions =  $repo->getTodayTotalLoggedSessions($user, $monthSTart);
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
		$session->setEndedAt(new \DateTime());
		$this->em->persist($session);
		$this->em->flush($session);
	}

	/**
	 * @return mixed
	 */
	public function getLastSessionEnd()
	{
		$repo = $this->em->getRepository('AppBundle:Session');
		return $repo->getLastSessionEnd();
	}

	public function getCurrentActiveSessions()
	{
		$repo = $this->em->getRepository('AppBundle:Session');
		return $repo->getCurrentActiveSessions();
	}

	public function getCurrentMonthStart()
	{
		if (date('d') > 23)
		{
			$month = date('M');
		}else{
			$month = date('M', strtotime('last month'));
		}

		return new \DateTime("23 $month");
	}

	public function getUsersTimeSheet()
	{
		$monthSTart = $this->getCurrentMonthStart();
		$user = null;
		if (!$this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN'))
		{
			$user = $this->getUser();
		}
		$repo = $this->em->getRepository('AppBundle:Session');
		return $repo->getUsersSessions($user, $monthSTart);
	}
}