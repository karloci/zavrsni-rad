<?php

namespace App\Module\Field\Controller;

use App\Controller\ApiController;
use App\Entity\Farm;
use App\Module\Field\Dto\FieldDto;
use App\Module\Field\Service\FieldService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class FieldController extends ApiController
{
    private FieldService $fieldService;

    public function __construct(DataSerializer $dataSerializer, FieldService $fieldService)
    {
        parent::__construct($dataSerializer);
        $this->fieldService = $fieldService;
    }

    #[Route("/farms/{farm}/fields", name: "farm_fields_list", methods: ["GET"])]
    public function findAllFields(Farm $farm): JsonResponse
    {
        $fields = $this->fieldService->findAllFieldsAction($farm);

        return $this->getHttpOkResponse($fields, ["field:default", "field:fieldType", "field:soilType"]);
    }

    #[Route("/farms/{farm}/fields", name: "farm_fields_create", methods: ["POST"])]
    public function createField(Farm $farm, #[MapRequestPayload] FieldDto $fieldDto): JsonResponse
    {
        $field = $this->fieldService->createFieldAction($farm, $fieldDto);

        return $this->getHttpCreatedResponse($field, ["field:default", "field:fieldType", "field:soilType"]);
    }

    #[Route("/farms/{farm}/fields/{fieldId}", name: "farm_fields_show", methods: ["GET"])]
    public function findOneField(Farm $farm, string $fieldId): JsonResponse
    {
        $field = $this->fieldService->findOneFieldAction($farm, $fieldId);

        return $this->getHttpOkResponse($field, ["field:default", "field:fieldType", "field:soilType"]);
    }

    #[Route("/farms/{farm}/fields/{fieldId}", name: "farm_fields_update", methods: ["PUT"])]
    public function updateField(Farm $farm, string $fieldId, #[MapRequestPayload] FieldDto $fieldDto): JsonResponse
    {
        $field = $this->fieldService->updateFieldAction($farm, $fieldId, $fieldDto);

        return $this->getHttpOkResponse($field, ["field:default", "field:fieldType", "field:soilType"]);
    }

    #[Route("/farms/{farm}/fields/{fieldId}", name: "farm_fields_delete", methods: ["DELETE"])]
    public function deleteField(Farm $farm, string $fieldId): JsonResponse
    {
        $this->fieldService->deleteFieldAction($farm, $fieldId);

        return $this->getHttpNoContentResponse();
    }
}
