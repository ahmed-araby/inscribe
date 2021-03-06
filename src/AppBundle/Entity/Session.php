<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 3/3/17
 * Time: 11:49 PM
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SessionRepository")
 * @ORM\Table(name="session")
 * @ORM\HasLifecycleCallbacks()
 */
class Session
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="sessions")
	 */
	protected $user;
	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $startedAt;
	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 * @Assert\NotBlank()
	 * @Assert\Type("\DateTime")
	 */
	protected $endedAt;
	/**
	 * @var integer
	 * @ORM\Column(type="integer", options={"default":0})
	 */
	protected $period = 0;

	/**
	 * Session constructor.
	 */
	public function __construct()
	{
		$this->startedAt = new \DateTime();
	}

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startedAt
     *
     * @param \DateTime $startedAt
     *
     * @return Session
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * Get startedAt
     *
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set endedAt
     *
     * @param \DateTime $endedAt
     *
     * @return Session
     */
    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    /**
     * Get endedAt
     *
     * @return \DateTime
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    /**
     * Set period
     *
     * @param integer $period
     *
     * @return Session
     */
    private function setPeriod($period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return integer
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Session
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

	/**
	 * @param null $relatimeTo
	 *
	 * @return int
	 */
	public function getPassedInSeconds($relatimeTo=null)
	{
		if (!$relatimeTo)
		{
			$relatimeTo = time();
		}
		return $relatimeTo - $this->getStartedAt()->getTimestamp();
    }

	/**
	 * @return int
	 */
	public function getPassedMilliseconds()
	{
		return $this->getPassedInSeconds()*1000;
    }

	/**
	 * @ORM\PreFlush()
	 */
	public function updatePeriod(  )
	{
		if ($this->getEndedAt())
		{
			$getPassedInSeconds = $this->getPassedInSeconds($this->getEndedAt()->getTimestamp());
			$getPassedInSeconds = max($getPassedInSeconds,0);
			$this->setPeriod(round($getPassedInSeconds/60));
		}
    }

	public function getStartedAtFormatted(  )
	{
		return $this->getStartedAt()->format('Y-m-d H:i');
	}

	public function getPeriodFormatted(  )
	{
		$minutes = $this->getPeriod();
		$remHours = floor($minutes / 60);
		$remMinutes = $minutes - (60*$remHours);
		return sprintf("%s hours , %s minutes", $remHours , $remMinutes);
	}
}
