<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\NamedTrait;
use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CompetencyType.
 *
 * @ORM\Table(name="competency_type")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\CompetencyTypeRepository")
 */
class CompetencyType implements
    IdentityTraitInterface,
    TimestampableTraitInterface,
    NamedTraitInterface
{
    use IdentityTrait, TimestampableTrait, NamedTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="competencyTypes")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *
     * @var Category
     */
    protected $category;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128)
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
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }
}
