<?php

declare(strict_types=1);

namespace App\Model\Platform;

use App\Component\Api\ModelInterface;
use App\Model\App\File;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * Class Platform
 *
 * @package App\Model\Platform
 *
 * @ORM\Entity()
 * @ORM\Table(name="platform")
 */
class Platform implements ModelInterface
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
     * @ORM\Column(name="technical_name", type="string", nullable=false)
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
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Model\App\File", mappedBy="platform")
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
}