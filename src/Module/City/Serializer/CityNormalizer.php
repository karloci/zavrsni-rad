<?php

namespace App\Module\City\Serializer;

use App\Entity\City;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class CityNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: "serializer.normalizer.object")]
        private NormalizerInterface $normalizer,
    )
    {
    }

    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $result = $this->normalizer->normalize($data, $format, $context);

        /** @var City $city */
        $city = $data;
        $result["countryId"] = $city->getCountry()->getId();

        return $result;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof City && array_key_exists("withCountryId", $context) && $context["withCountryId"];
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            City::class => true
        ];
    }
}