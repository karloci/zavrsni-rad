<?php

namespace App\Entity;

use App\Trait\CreatedLog;
use App\Trait\DeletedLog;
use App\Trait\UpdatedLog;
use App\Module\Season\Repository\SeasonRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SeasonRepository::class)]
#[ORM\UniqueConstraint(name: "UNIQUE_SEASON", fields: ["farm", "name"])]
#[ORM\HasLifecycleCallbacks]
class Season
{
    use CreatedLog, UpdatedLog, DeletedLog;

    #[ORM\Id]
    #[ORM\Column(name: "id", type: UuidType::NAME, unique: true, nullable: false)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[Groups(["season:default", "farm:seasons", "cropRotation:season"])]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: "seasons")]
    #[ORM\JoinColumn(name: "farm_id", referencedColumnName: "id", unique: false, nullable: false)]
    private ?Farm $farm = null;

    #[ORM\Column(name: "name", type: Types::STRING, length: 45, unique: false, nullable: false)]
    #[Groups(["season:default", "farm:seasons", "cropRotation:season"])]
    private ?string $name = null;

    #[ORM\Column(name: "start_date", type: Types::DATE_IMMUTABLE, unique: false, nullable: false)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => "Y-m-d"])]
    #[Groups(["season:default", "farm:seasons", "cropRotation:season"])]
    private ?DateTimeImmutable $startDate = null;

    #[ORM\Column(name: "end_date", type: Types::DATE_IMMUTABLE, unique: false, nullable: false)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => "Y-m-d"])]
    #[Groups(["season:default", "farm:seasons", "cropRotation:season"])]
    private ?DateTimeImmutable $endDate = null;

    /**
     * @var Collection<int, CropRotation>
     */
    #[ORM\OneToMany(targetEntity: CropRotation::class, mappedBy: "season", orphanRemoval: true)]
    private Collection $cropRotations;

    public function __construct()
    {
        $this->cropRotations = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFarm(): ?Farm
    {
        return $this->farm;
    }

    public function setFarm(?Farm $farm): static
    {
        $this->farm = $farm;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTimeImmutable $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeImmutable $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return Collection<int, CropRotation>
     */
    public function getCropRotations(): Collection
    {
        return $this->cropRotations;
    }

    public function addCropRotation(CropRotation $cropRotation): static
    {
        if (!$this->cropRotations->contains($cropRotation)) {
            $this->cropRotations->add($cropRotation);
            $cropRotation->setSeason($this);
        }

        return $this;
    }

    public function removeCropRotation(CropRotation $cropRotation): static
    {
        if ($this->cropRotations->removeElement($cropRotation)) {
            // set the owning side to null (unless already changed)
            if ($cropRotation->getSeason() === $this) {
                $cropRotation->setSeason(null);
            }
        }

        return $this;
    }
}