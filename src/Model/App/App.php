<?php

declare(strict_types=1);

namespace App\Model\App;

use App\Component\Api\ModelInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * Class App
 *
 * @package App\Model\App
 *
 * @ORM\Entity()
 * @ORM\Table(name="app")
 */
class App implements ModelInterface
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
     * @var string
     *
     * @ORM\Column(name="technical_name", type="string", nullable=false, unique=true)
     */
    protected $technicalName;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", nullable=false)
     */
    protected $label;

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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Version", mappedBy="app")
     */
    protected $versions;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
    }

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
     * @return string
     */
    public function getTechnicalName(): string
    {
        return $this->technicalName;
    }

    /**
     * @param string $technicalName
     */
    public function setTechnicalName(string $technicalName): void
    {
        $this->technicalName = $technicalName;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
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
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    /**
     * @param Collection $versions
     */
    public function setVersions(Collection $versions): void
    {
        $this->versions = $versions;
    }
}