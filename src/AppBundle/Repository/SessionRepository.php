<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Session;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * SessionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SessionRepository extends EntityRepository
{
	/**
	 * @param User $user
	 *
	 * @return Session
	 */
	public function getLatestWithoutOffSessionByUser(User $user)
	{
		$qb = $this->createQueryBuilder( 's' );
		$qb->andWhere( 's.endedAt is NULL or s.endedAt=0' )
		   ->andWhere( 's.user = :user' )
		   ->setParameter( 'user', $user )
		   ->orderBy( 's.id', 'DESC' )
		   ->setMaxResults( 1 );
		return $qb->getQuery()->getOneOrNullResult();
	}

	/**
	 * @param User $user
	 *
	 * @param \DateTime $start
	 *
	 * @return Session[]
	 */
	public function getTodayTotalLoggedSessions(User $user, \DateTime $start=null)
	{
		if (!$start)
		{
			$start = new \DateTime('today');
		}
		$qb = $this->createQueryBuilder( 's' );
		$qb->andWhere( 's.endedAt is NOT NULL' )
			->andWhere( 's.endedAt > 0 ' )
		   ->andWhere( 's.user = :user' )->setParameter( 'user', $user )
			->andWhere( 's.startedAt >= :startedAt' )->setParameter( 'startedAt', $start )
		   ->orderBy( 's.id', 'DESC' );
		return $qb->getQuery()->execute();
	}

	public function getLastSessionEnd(  )
	{
		$qb = $this->createQueryBuilder( 's' );
		$qb->andWhere( 's.endedAt is NOT NULL' )
		   ->andWhere( 's.endedAt > 0 ' )
		   ->orderBy( 's.id', 'DESC' )
			->setMaxResults(1);
		return $qb->getQuery()->getOneOrNullResult();
	}

	/**
	 * @return Session[]
	 */
	public function getCurrentActiveSessions(  )
	{
		$qb = $this->createQueryBuilder( 's' );
		$qb->andWhere( 's.endedAt is NULL' )
		   ->orderBy( 's.id', 'DESC' )
			;
		return $qb->getQuery()->execute();
	}
}
