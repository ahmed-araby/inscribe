<?php
namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var Session[]
	 *
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\Session", mappedBy="user")
	 */
	protected $sessions;

	public function __construct()
	{
		parent::__construct();
		// your own logic
	}

    /**
     * Add session
     *
     * @param \AppBundle\Entity\Session $session
     *
     * @return User
     */
    public function addSession(\AppBundle\Entity\Session $session)
    {
        $this->sessions[] = $session;

        return $this;
    }

    /**
     * Remove session
     *
     * @param \AppBundle\Entity\Session $session
     */
    public function removeSession(\AppBundle\Entity\Session $session)
    {
        $this->sessions->removeElement($session);
    }

    /**
     * Get sessions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSessions()
    {
        return $this->sessions;
    }
}
