<?php

namespace App\Entity;

use App\Trait\CreatedLog;
use App\Trait\DeletedLog;
use App\Trait\UpdatedLog;
use App\Module\Farm\Repository\FarmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FarmRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Farm
{
    use CreatedLog, UpdatedLog, DeletedLog;

    #[ORM\Id]
    #[ORM\Column(name: "id", type: UuidType::NAME, unique: true, nullable: false)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[Groups(["farm:default"])]
    private ?Uuid $id = null;

    #[ORM\Column(name: "name", type: Types::STRING, length: 45, unique: false, nullable: false)]
    #[Groups(["farm:default"])]
    private ?string $name = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "country_id", referencedColumnName: "id", unique: false, nullable: false)]
    #[Groups(["farm:country", "farm:default"])]
    private ?Country $country = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "city_id", referencedColumnName: "id", unique: false, nullable: false)]
    #[Groups(["farm:city", "farm:default"])]
    private ?City $city = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "timezone_id", referencedColumnName: "id", unique: false, nullable: false)]
    #[Groups(["farm:timezone", "farm:default"])]
    private ?Timezone $timezone = null;

    #[ORM\Column(name: "address", type: Types::STRING, length: 45, unique: false, nullable: true)]
    #[Groups(["farm:default"])]
    private ?string $address = null;

    #[ORM\Column(name: "phone", type: Types::STRING, length: 45, unique: false, nullable: true)]
    #[Groups(["farm:default"])]
    private ?string $phone = null;

    #[ORM\Column(name: "email", type: Types::STRING, length: 45, unique: false, nullable: true)]
    #[Groups(["farm:default"])]
    private ?string $email = null;

    #[ORM\Column(name: "website", type: Types::STRING, length: 45, unique: false, nullable: true)]
    #[Groups(["farm:default"])]
    private ?string $website = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: "farm")]
    private Collection $users;

    /**
     * @var Collection<int, Field>
     */
    #[ORM\OneToMany(targetEntity: Field::class, mappedBy: "farm", orphanRemoval: true)]
    #[Groups(["farm:fields"])]
    private Collection $fields;

    /**
     * @var Collection<int, Season>
     */
    #[ORM\OneToMany(targetEntity: Season::class, mappedBy: "farm", orphanRemoval: true)]
    #[Groups(["farm:seasons"])]
    private Collection $seasons;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->fields = new ArrayCollection();
        $this->seasons = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getTimezone(): ?Timezone
    {
        return $this->timezone;
    }

    public function setTimezone(?Timezone $timezone): static
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setFarm($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getFarm() === $this) {
                $user->setFarm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Field>
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(Field $field): static
    {
        if (!$this->fields->contains($field)) {
            $this->fields->add($field);
            $field->setFarm($this);
        }

        return $this;
    }

    public function removeField(Field $field): static
    {
        if ($this->fields->removeElement($field)) {
            // set the owning side to null (unless already changed)
            if ($field->getFarm() === $this) {
                $field->setFarm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): static
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons->add($season);
            $season->setFarm($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): static
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getFarm() === $this) {
                $season->setFarm(null);
            }
        }

        return $this;
    }
}
