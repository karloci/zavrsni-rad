<?php

namespace App\Entity;

use App\Trait\CreatedLog;
use App\Trait\DeletedLog;
use App\Trait\UpdatedLog;
use App\Module\Field\Repository\FieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FieldRepository::class)]
#[ORM\UniqueConstraint(name: "UNIQUE_FIELD", fields: ["farm", "name"])]
#[ORM\HasLifecycleCallbacks]
class Field
{
    use CreatedLog, UpdatedLog, DeletedLog;

    #[ORM\Id]
    #[ORM\Column(name: "id", type: UuidType::NAME, unique: true, nullable: false)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[Groups(["field:default", "farm:fields", "cropRotation:field"])]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: "fields")]
    #[ORM\JoinColumn(name: "farm_id", referencedColumnName: "id", unique: false, nullable: false)]
    private ?Farm $farm = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "field_type_id", referencedColumnName: "id", unique: false, nullable: false)]
    #[Groups(["field:fieldType", "fieldType:default"])]
    private ?FieldType $fieldType = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "soil_type_id", referencedColumnName: "id", unique: false, nullable: false)]
    #[Groups(["field:soilType", "soilType:default"])]
    private ?SoilType $soilType = null;

    #[ORM\Column(name: "name", type: Types::STRING, length: 45, unique: false, nullable: false)]
    #[Groups(["field:default", "farm:fields", "cropRotation:field"])]
    private ?string $name = null;

    #[ORM\Column(name: "area", type: Types::FLOAT, unique: false, nullable: false)]
    #[Groups(["field:default", "farm:fields", "cropRotation:field"])]
    private ?float $area = null;

    /**
     * @var Collection<int, CropRotation>
     */
    #[ORM\OneToMany(targetEntity: CropRotation::class, mappedBy: "field", orphanRemoval: true)]
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

    public function getFieldType(): ?FieldType
    {
        return $this->fieldType;
    }

    public function setFieldType(?FieldType $fieldType): static
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    public function getSoilType(): ?SoilType
    {
        return $this->soilType;
    }

    public function setSoilType(?SoilType $soilType): static
    {
        $this->soilType = $soilType;

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

    public function getArea(): ?float
    {
        return $this->area;
    }

    public function setArea(float $area): static
    {
        $this->area = $area;

        return $this;
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
            $cropRotation->setField($this);
        }

        return $this;
    }

    public function removeCropRotation(CropRotation $cropRotation): static
    {
        if ($this->cropRotations->removeElement($cropRotation)) {
            // set the owning side to null (unless already changed)
            if ($cropRotation->getField() === $this) {
                $cropRotation->setField(null);
            }
        }

        return $this;
    }
}
