<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\NamedEntityInterface;
use Fitch\CommonBundle\Entity\NamedEntityTrait;
use Fitch\CommonBundle\Entity\TimestampableEntityInterface;
use Fitch\CommonBundle\Entity\TimestampableEntityTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CompetencyType.
 *
 * @ORM\Table(name="competency_type")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\CompetencyTypeRepository")
 */
class CompetencyType implements
    IdentityEntityInterface,
    TimestampableEntityInterface,
    NamedEntityInterface
{
    use IdentityEntityTrait, TimestampableEntityTrait, NamedEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="competencyTypes")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     *
     * @var Category
     */
    protected $category;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128)
     * @Assert\NotBlank()
     */
    protected $name = '...';

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }
}
