<?php
namespace AppBundle\Twig;

use AppBundle\Entity\Session;
use AppBundle\Service\SignService;

/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 3/4/17
 * Time: 5:33 AM
 */
class AppExtension extends \Twig_Extension
{
	/** @var SignService  */
	protected $signService;

	/**
	 * AppExtension constructor.
	 *
	 * @param SignService $signService
	 */
	function __construct(SignService $signService )
	{
		$this->signService = $signService;
	}

	/**
	 * @return \Twig_SimpleFunction[]
	 */
	public function getFunctions(  )
	{
		return [
			new \Twig_SimpleFunction('getCurrentSession', [$this, 'getCurrentSession']),
			new \Twig_SimpleFunction('getTodayPeriod', [$this, 'getTodayPeriod']),
			new \Twig_SimpleFunction('getMonthPeriod', [$this, 'getMonthPeriod']),
			new \Twig_SimpleFunction('getLastSessionEnd', [$this, 'getLastSessionEnd'])
		];
	}

	/**
	 * @return \DateTime|string
	 */
	public function getLastSessionEnd(  )
	{
		$lastSession =  $this->signService->getLastSessionEnd();
		if ($lastSession instanceof Session)
		{
			return $lastSession->getEndedAt()->format('Y-m-d H:i');
		}
		return '';
	}

	/**
	 * @return string
	 */
	public function getMonthPeriod()
	{
		$minutes = $this->signService->getMonthTotalInterval();
		$remHours = floor($minutes / 60);
		$remMinutes = $minutes - (60*$remHours);
		return sprintf("%s hours , %s minutes", $remHours , $remMinutes);
	}

	/**
	 * @return string
	 */
	public function getTodayPeriod()
	{
		$minutes = $this->signService->getTodayTotalInterval();
		$remHours = floor($minutes / 60);
		$remMinutes = $minutes - (60*$remHours);
		return sprintf("%s hours , %s minutes", $remHours , $remMinutes);
	}

	/**
	 * @return Session
	 */
	public function getCurrentSession()
	{
		return $this->signService->getCurrentUserOpenSession();
	}
}