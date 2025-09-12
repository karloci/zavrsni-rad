<?php

namespace App\Module\FieldType\Controller;

use App\Controller\ApiController;
use App\Module\FieldType\Dto\FieldTypeDto;
use App\Module\FieldType\Service\FieldTypeService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class FieldTypeController extends ApiController
{
    private FieldTypeService $fieldTypeService;

    public function __construct(DataSerializer $dataSerializer, FieldTypeService $fieldTypeService)
    {
        parent::__construct($dataSerializer);
        $this->fieldTypeService = $fieldTypeService;
    }

    #[Route("/field-types", name: "field_types_list", methods: ["GET"])]
    public function findAllFieldTypes(): JsonResponse
    {
        $fieldTypes = $this->fieldTypeService->findAllFieldTypesAction();

        return $this->getHttpOkResponse($fieldTypes, ["fieldType:default"]);
    }

    #[Route("/field-types", name: "field_types_create", methods: ["POST"])]
    public function createFieldType(#[MapRequestPayload] FieldTypeDto $fieldTypeDto): JsonResponse
    {
        $fieldType = $this->fieldTypeService->createFieldTypeAction($fieldTypeDto);

        return $this->getHttpCreatedResponse($fieldType, ["fieldType:default"]);
    }

    #[Route("/field-types/{fieldTypeId}", name: "field_types_show", methods: ["GET"])]
    public function findOneFieldType(string $fieldTypeId): JsonResponse
    {
        $fieldType = $this->fieldTypeService->findOneFieldTypeAction($fieldTypeId);

        return $this->getHttpOkResponse($fieldType, ["fieldType:default"]);
    }

    #[Route("/field-types/{fieldTypeId}", name: "field_types_update", methods: ["PUT"])]
    public function updateFieldType(string $fieldTypeId, #[MapRequestPayload] FieldTypeDto $fieldTypeDto): JsonResponse
    {
        $fieldType = $this->fieldTypeService->updateFieldTypeAction($fieldTypeId, $fieldTypeDto);

        return $this->getHttpOkResponse($fieldType, ["fieldType:default"]);
    }

    #[Route("/field-types/{fieldTypeId}", name: "field_types_delete", methods: ["DELETE"])]
    public function deleteFieldType(string $fieldTypeId): JsonResponse
    {
        $this->fieldTypeService->deleteFieldTypeAction($fieldTypeId);

        return $this->getHttpNoContentResponse();
    }
}
