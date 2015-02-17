<?php

namespace Fitch\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Fitch\EntityAttributeValueBundle\Annotation as EAV;
use Fitch\EntityAttributeValueBundle\Entity\AttributedEntityInterface;
use Fitch\EntityAttributeValueBundle\Entity\AttributedEntityTrait;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @EAV\Entity()
 * @ORM\Entity(repositoryClass="Fitch\UserBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="person")
 * @Gedmo\Loggable
 *
 * @UniqueEntity(fields="email", message="Email is already in use")
 * @UniqueEntity(fields="username", message="Username is already in use")
 */
class User extends BaseUser implements TimestampableTraitInterface, AttributedEntityInterface
{
    use TimestampableTrait, AttributedEntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="full_name", type="string", length=128, nullable=true)
     * @Gedmo\Versioned
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max="255",
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @var string
     */
    protected $fullName;

    // Re-declare these fields so that we can make them loggable
    // When/if this PR is accepted we will be able to do this in the class annotation

    /**
     * @Gedmo\Versioned
     */
    protected $email;

    /**
     * @Gedmo\Versioned
     */
    protected $username;

    /**
     * @ORM\Column(name="is_sidebar_open", type="boolean")
     * @var bool
     */
    protected $sideBarOpen = true;

    /**
     * @Gedmo\Versioned
     */
    protected $roles;

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

    /**
     * @return boolean
     */
    public function isSideBarOpen()
    {
        return $this->sideBarOpen;
    }

    /**
     * @param boolean $sideBarOpen
     * @return $this
     */
    public function setSideBarOpen($sideBarOpen)
    {
        $this->sideBarOpen = $sideBarOpen;
        return $this;
    }

    /**
     * Toggles the sidebar
     *
     * @return $this
     */
    public function toggleSidebar()
    {
        $this->setSideBarOpen(! $this->isSideBarOpen());
        return $this;
    }
}
