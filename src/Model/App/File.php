<?php

declare(strict_types=1);

namespace App\Model\App;

use App\Component\Api\ModelInterface;
use App\Model\Platform\Platform;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * Class File
 *
 * @package App\Model\App
 *
 * @ORM\Entity()
 * @ORM\Table(name="app_version_file")
 */
class File implements ModelInterface
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="uuid_binary", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected $id;

    /**
     * @var UuidInterface
     *
     * @ORM\Column(name="version_id", type="uuid_binary")
     */
    protected $versionId;

    /**
     * @var UuidInterface
     *
     * @ORM\Column(name="file_id", type="uuid_binary")
     */
    protected $fileId;

    /**
     * @var UuidInterface
     *
     * @ORM\Column(name="platform_id", type="uuid_binary")
     */
    protected $platformId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="changed", type="datetime", nullable=true)
     */
    protected $changed;

    /**
     * @var Version
     *
     * @ORM\ManyToOne(targetEntity="Version", inversedBy="files")
     * @ORM\JoinColumn(name="version_id", referencedColumnName="id")
     */
    protected $version;

    /**
     * @var \App\Model\File\File
     *
     * @ORM\OneToOne(targetEntity="App\Model\File\File", mappedBy="file")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id")
     */
    protected $file;

    /**
     * @var Platform
     *
     * @ORM\ManyToOne(targetEntity="App\Model\Platform\Platform", inversedBy="files")
     * @ORM\JoinColumn(name="platform_id", referencedColumnName="id")
     */
    protected $platform;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     */
    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    /**
     * @return UuidInterface
     */
    public function getVersionId(): UuidInterface
    {
        return $this->versionId;
    }

    /**
     * @return UuidInterface
     */
    public function getFileId(): UuidInterface
    {
        return $this->fileId;
    }

    /**
     * @return UuidInterface
     */
    public function getPlatformId(): UuidInterface
    {
        return $this->platformId;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime|null
     */
    public function getChanged(): ?\DateTime
    {
        return $this->changed;
    }

    /**
     * @param \DateTime|null $changed
     */
    public function setChanged(?\DateTime $changed): void
    {
        $this->changed = $changed;
    }

    /**
     * @return \App\Model\File\File
     */
    public function getFile(): \App\Model\File\File
    {
        return $this->file;
    }

    /**
     * @param \App\Model\File\File $file
     */
    public function setFile(\App\Model\File\File $file): void
    {
        $this->file = $file;
    }

    /**
     * @return Platform
     */
    public function getPlatform(): Platform
    {
        return $this->platform;
    }

    /**
     * @param Platform $platform
     */
    public function setPlatform(Platform $platform): void
    {
        $this->platform = $platform;
    }
}