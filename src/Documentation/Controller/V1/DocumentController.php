<?php

namespace App\Documentation\Controller\V1;

use App\Documentation\Entity\Document;
use App\Documentation\Request\Document\CreateDocumentRequest;
use App\Documentation\Request\Document\UpdateDocumentRequest;
use App\Documentation\Service\Document\DocumentCreator;
use App\Documentation\Service\Document\DocumentUpdater;
use App\Documentation\Service\Document\Exception\DocumentException;
use App\Documentation\Transformer\Document\BaseDataTransformer;
use App\Infra\Response\ErrorResponse;
use League\Fractal\Resource\Item;
use SamJ\FractalBundle\ContainerAwareManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/", name="document_")
 * @SWG\Tag(name="document")
 */
class DocumentController
{
    private const DATA_ALIAS = 'document';

    /**
     * Create new document
     * @Route("/", name="create_document", methods={"POST"})
     * @param CreateDocumentRequest $request
     * @param DocumentCreator $creator
     * @param ContainerAwareManager $fractal
     * @param BaseDataTransformer $transformer
     * @return JsonResponse
     * @throws \Exception
     * @SWG\Response(
     *     response=201,
     *     description="Returns a list of cities",
     *     @SWG\Schema(
     *        type="object"
     *     )
     * )
     */
    public function createDocument(
        CreateDocumentRequest $request,
        DocumentCreator $creator,
        ContainerAwareManager $fractal,
        BaseDataTransformer $transformer
    ): JsonResponse {
        $document = $creator->createDocument($request->getPayload());

        $item = new Item($document, $transformer, self::DATA_ALIAS);
        $data = $fractal->createData($item)->toArray();

        return new JsonResponse($data, 201);
    }

    /**
     * Update the document
     * @Route("/{id}", name="update_document", methods={"PATCH"})
     * @param UpdateDocumentRequest $request
     * @param Document|null $document
     * @param DocumentUpdater $updater
     * @param ContainerAwareManager $fractal
     * @param BaseDataTransformer $transformer
     * @return JsonResponse
     * @ParamConverter("document", class="App\Documentation\Entity\Document", isOptional=true,
     *     options={"mapping": {"id" : "id"}})
     */
    public function updateDocument(
        UpdateDocumentRequest $request,
        ?Document $document,
        DocumentUpdater $updater,
        ContainerAwareManager $fractal,
        BaseDataTransformer $transformer
    ): JsonResponse {
        if (!$document) {
            throw new NotFoundHttpException('The document was not found');
        }
        try {
            $document = $updater->updatePayload($request->getPayload(), $document);
        } catch (DocumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $item = new Item($document, $transformer, self::DATA_ALIAS);
        $data = $fractal->createData($item)->toArray();

        return new JsonResponse($data, 200);
    }

    /**
     * Publish the document
     * @Route("/{id}/publish", name="publish_document", methods={"POST"})
     * @param Document|null $document
     * @param DocumentUpdater $updater
     * @param ContainerAwareManager $fractal
     * @param BaseDataTransformer $transformer
     * @return JsonResponse
     * @throws \Exception
     * @ParamConverter("document", class="App\Documentation\Entity\Document", isOptional=true,
     *     options={"mapping": {"id" : "id"}})
     */
    public function publishDocument(
        ?Document $document,
        DocumentUpdater $updater,
        ContainerAwareManager $fractal,
        BaseDataTransformer $transformer
    ): JsonResponse {
        if (!$document) {
            throw new NotFoundHttpException('The document was not found');
        }

        $document = $updater->publish($document);

        $item = new Item($document, $transformer, self::DATA_ALIAS);
        $data = $fractal->createData($item)->toArray();

        return new JsonResponse($data, 200);
    }
}
