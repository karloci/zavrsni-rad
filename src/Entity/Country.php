<?php

namespace App\Entity;

use App\Trait\SoftDelete;
use App\Module\Country\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    use SoftDelete;

    #[ORM\Id]
    #[ORM\Column(name: "id", type: UuidType::NAME, unique: true, nullable: false)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[Groups(["country:default", "city:country", "timezone:country", "farm:default"])]
    private ?Uuid $id = null;

    #[ORM\Column(name: "name", type: Types::STRING, length: 45, unique: true, nullable: false)]
    #[Groups(["country:default", "city:country", "timezone:country", "farm:default"])]
    private ?string $name = null;

    #[ORM\Column(name: "code", type: Types::STRING, length: 3, unique: true, nullable: false)]
    #[Groups(["country:default", "city:country", "timezone:country", "farm:default"])]
    private ?string $code = null;

    /**
     * @var Collection<int, City>
     */
    #[ORM\OneToMany(targetEntity: City::class, mappedBy: "country")]
    #[Groups(["country:cities"])]
    private Collection $cities;

    /**
     * @var Collection<int, Timezone>
     */
    #[ORM\OneToMany(targetEntity: Timezone::class, mappedBy: "country")]
    #[Groups(["country:timezones"])]
    private Collection $timezones;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
        $this->timezones = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, City>
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): static
    {
        if (!$this->cities->contains($city)) {
            $this->cities->add($city);
            $city->setCountry($this);
        }

        return $this;
    }

    public function removeCity(City $city): static
    {
        if ($this->cities->removeElement($city)) {
            // set the owning side to null (unless already changed)
            if ($city->getCountry() === $this) {
                $city->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Timezone>
     */
    public function getTimezones(): Collection
    {
        return $this->timezones;
    }

    public function addTimezone(Timezone $timezone): static
    {
        if (!$this->timezones->contains($timezone)) {
            $this->timezones->add($timezone);
            $timezone->setCountry($this);
        }

        return $this;
    }

    public function removeTimezone(Timezone $timezone): static
    {
        if ($this->timezones->removeElement($timezone)) {
            // set the owning side to null (unless already changed)
            if ($timezone->getCountry() === $this) {
                $timezone->setCountry(null);
            }
        }

        return $this;
    }
}
