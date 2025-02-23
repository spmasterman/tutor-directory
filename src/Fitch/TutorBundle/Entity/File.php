<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\CommonBundle\Entity\NamedEntityTrait;
use Fitch\CommonBundle\Entity\NamedEntityInterface;
use Fitch\CommonBundle\Entity\TimestampableEntityTrait;
use Fitch\CommonBundle\Entity\TimestampableEntityInterface;
use Fitch\UserBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;

/**
 * File.
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\FileRepository")
 */
class File implements
    IdentityEntityInterface,
    TimestampableEntityInterface,
    NamedEntityInterface
{
    use IdentityEntityTrait, TimestampableEntityTrait, NamedEntityTrait, ProvenanceBuilderTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Tutor", inversedBy="files")
     * @ORM\JoinColumn(name="tutor_id", referencedColumnName="id")
     *
     * @var Tutor
     */
    protected $tutor;

    /**
     * @ORM\ManyToOne(targetEntity="FileType")
     * @ORM\JoinColumn(name="file_type_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var FileType
     */
    protected $fileType;

    /**
     * @ORM\OneToOne(targetEntity="CropInfo")
     * @ORM\JoinColumn(name="crop_info_id", referencedColumnName="id")
     *
     * @var CropInfo
     */
    protected $cropInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="fs_key", type="string", length=32)
     */
    protected $fileSystemKey;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=32)
     */
    protected $mimeType;

    /**
     * @ORM\ManyToOne(targetEntity="Fitch\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="uploader_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var User
     */
    protected $uploader;

    /**
     * @var string
     *
     * @ORM\Column(name="text_content", type="text")
     */
    protected $textContent;

    /**
     * @return Tutor
     */
    public function getTutor()
    {
        return $this->tutor;
    }

    /**
     * @param Tutor $tutor
     *
     * @return $this
     */
    public function setTutor($tutor)
    {
        $this->tutor = $tutor;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileSystemKey()
    {
        return $this->fileSystemKey;
    }

    /**
     * @param string $fileSystemKey
     *
     * @return $this
     */
    public function setFileSystemKey($fileSystemKey)
    {
        $this->fileSystemKey = $fileSystemKey;

        return $this;
    }

    /**
     * @return FileType
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * @param FileType $fileType
     *
     * @return $this
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param mixed $mimeType
     *
     * @return $this
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return CropInfo
     */
    public function getCropInfo()
    {
        return $this->cropInfo;
    }

    /**
     * @param CropInfo $cropInfo
     *
     * @return $this
     */
    public function setCropInfo($cropInfo)
    {
        $this->cropInfo = $cropInfo;

        return $this;
    }

    /**
     * @return bool
     */
    public function isImage()
    {
        return explode('/', $this->getMimeType(), 2)[0] == 'image';
    }

    /**
     * @return array
     */
    public function getMetaData()
    {
        return [];
        // Not yet implemented
//        return [
//            "Dimensions" => "200 x 300",
//            "Size" => "23.4k"
//        ];
    }

    /**
     * @return bool
     */
    public function hasCropInfo()
    {
        return (bool) $this->getCropInfo();
    }

    /**
     * @return int
     */
    public function getCropX()
    {
        if ($this->hasCropInfo()) {
            return $this->getCropInfo()->getOriginX();
        } else {
            return 0;
        }
    }

    /**
     * @return int
     */
    public function getCropY()
    {
        if ($this->hasCropInfo()) {
            return $this->getCropInfo()->getOriginY();
        } else {
            return 0;
        }
    }

    /**
     * @return int
     */
    public function getCropWidth()
    {
        if ($this->hasCropInfo()) {
            return $this->getCropInfo()->getWidth();
        } else {
            return Avatar::AVATAR_WIDTH;
        }
    }

    /**
     * @return int
     */
    public function getCropHeight()
    {
        if ($this->hasCropInfo()) {
            return $this->getCropInfo()->getHeight();
        } else {
            return Avatar::AVATAR_HEIGHT;
        }
    }

    /**
     * @return User
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * @param User $uploader
     *
     * @return $this
     */
    public function setUploader($uploader)
    {
        $this->uploader = $uploader;

        return $this;
    }

    /**
     * Get the uploader string
     *
     * @return string
     */
    public function getProvenance()
    {
        return $this->generateProvenanceString($this->getUploader());
    }

    /**
     * @return string
     */
    public function getTextContent()
    {
        return $this->textContent;
    }

    /**
     * @param string $textContent
     *
     * @return $this
     */
    public function setTextContent($textContent)
    {
        $this->textContent = $textContent;

        return $this;
    }
}
