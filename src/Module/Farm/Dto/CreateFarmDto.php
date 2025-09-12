<?php

namespace App\Module\Farm\Dto;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class CreateFarmDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Uuid]
    public Uuid $owner;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Uuid]
    public Uuid $country;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Uuid]
    public Uuid $city;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Uuid]
    public Uuid $timezone;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 45)]
    private string $name;

    #[Assert\Length(max: 45)]
    private ?string $address = null;

    #[Assert\Length(max: 45)]
    private ?string $phone = null;

    #[Assert\Length(max: 45)]
    private ?string $email = null;

    #[Assert\Length(max: 45)]
    private ?string $website = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getOwner(): Uuid
    {
        return $this->owner;
    }

    public function setOwner(Uuid $owner): void
    {
        $this->owner = $owner;
    }

    public function getCountry(): Uuid
    {
        return $this->country;
    }

    public function setCountry(Uuid $country): void
    {
        $this->country = $country;
    }

    public function getCity(): Uuid
    {
        return $this->city;
    }

    public function setCity(Uuid $city): void
    {
        $this->city = $city;
    }

    public function getTimezone(): Uuid
    {
        return $this->timezone;
    }

    public function setTimezone(Uuid $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }
}
