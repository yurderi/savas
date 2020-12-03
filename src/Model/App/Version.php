<?php

declare(strict_types=1);

namespace App\Model\App;

use App\Component\Api\ModelInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * Class Version
 *
 * @package App\Model\App
 *
 * @ORM\Entity()
 * @ORM\Table(name="app_version")
 */
class Version implements ModelInterface
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
     * @ORM\Column(name="app_id", type="uuid_binary")
     */
    protected $appId;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    protected $active;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", nullable=false)
     */
    protected $value;

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
     * @var App
     *
     * @ORM\ManyToOne(targetEntity="App", inversedBy="versions")
     * @ORM\JoinColumn(name="app_id", referencedColumnName="id")
     */
    protected $app;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="File", mappedBy="version")
     */
    protected $files;

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
    public function getAppId(): UuidInterface
    {
        return $this->appId;
    }

    /**
     * @param UuidInterface $appId
     */
    public function setAppId(UuidInterface $appId): void
    {
        $this->appId = $appId;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
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
     * @return Collection
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    /**
     * @param Collection $files
     */
    public function setFiles(Collection $files): void
    {
        $this->files = $files;
    }
}