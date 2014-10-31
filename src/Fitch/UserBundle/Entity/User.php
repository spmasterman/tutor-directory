<?php

namespace Fitch\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;

use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Fitch\UserBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="person")
 */
class User extends BaseUser implements TimestampableTraitInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="full_name", type="string", length=128, nullable=true)
     *
     * @var string
     */
    protected $fullName;



    public function __construct()
    {
        parent::__construct();
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
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     * @return $this
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }
}
