<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * File.
 *
 * @ORM\Table(name="crop")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\CropInfoRepository")
 */
class CropInfo implements
    IdentityTraitInterface,
    TimestampableTraitInterface
{
    use IdentityTrait, TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="origin_x", type="integer")
     */
    protected $originX;

    /**
     * @var int
     *
     * @ORM\Column(name="origin_y", type="integer")
     */
    protected $originY;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer")
     */
    protected $height;

    /**
     * @var int
     *
     * @ORM\Column(name="width", type="integer")
     */
    protected $width;

    /**
     * @return int
     */
    public function getOriginX()
    {
        return $this->originX;
    }

    /**
     * @param int $originX
     *
     * @return $this
     */
    public function setOriginX($originX)
    {
        $this->originX = $originX;

        return $this;
    }

    /**
     * @return int
     */
    public function getOriginY()
    {
        return $this->originY;
    }

    /**
     * @param int $originY
     *
     * @return $this
     */
    public function setOriginY($originY)
    {
        $this->originY = $originY;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }
}
