<?php

namespace App\Documentation\Controller\V1;

use App\Documentation\Entity\Document;
use App\Documentation\Repository\DocumentRepository;
use App\Documentation\Repository\Specification\Document\Ordering\NewerFirst;
use App\Documentation\Request\Document\CreateDocumentRequest;
use App\Documentation\Request\Document\GetListRequest;
use App\Documentation\Request\Document\UpdateDocumentRequest;
use App\Documentation\Service\Document\DocumentCreator;
use App\Documentation\Service\Document\DocumentUpdater;
use App\Documentation\Service\Document\Exception\DocumentException;
use App\Documentation\Transformer\Document\BaseDataTransformer;
use App\Infra\Response\ErrorResponse;
use App\Infra\Response\FractalPaginator;
use Exception;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Nelmio\ApiDocBundle\Annotation\Model;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Exception\LogicException;
use Rb\Specification\Doctrine\Specification;
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
     * @throws Exception
     * @SWG\Response(
     *     response=200,
     *     description="Created document",
     *     @SWG\Schema(
     *          example={"document": {"id": "uuid", "status": "draft", "payload": {}, "createAt": "2018-09-01 20:00:00+07:00", "modifyAt": "2018-09-01 20:01:00+07:00"}}
     *     )
     * ),
     * @SWG\Parameter(
     *     type="object",
     *     parameter="payload",
     *     description="Payload to save in created document",
     *     name="payload",
     *     in="body",
     *     required=false,
     *     @SWG\Schema(
     *          example={"payload": {"a": "b"}}
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
     * Get list of documents
     * @Route("/", name="get_list_of_documents", methods={"GET"})
     * @param GetListRequest $request
     * @param DocumentRepository $repository
     * @param ContainerAwareManager $fractal
     * @param BaseDataTransformer $transformer
     * @return JsonResponse
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @SWG\Parameter(
     *     type="integer",
     *     parameter="page",
     *     description="The number of a page",
     *     name="page",
     *     in="query",
     *     required=false
     * )
     * @SWG\Parameter(
     *     type="integer",
     *     parameter="perPage",
     *     name="perPage",
     *     in="query",
     *     required=false,
     *     description="Count per page"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="A list of documents",
     *     @SWG\Schema(
     *          example={"document": {{"id": "uuid", "status": "draft", "payload": {}, "createAt": "2018-09-01 20:00:00+07:00", "modifyAt": "2018-09-01 20:01:00+07:00"}}}
     *     )
     * ),
     */
    public function getListOfDocuments(
        GetListRequest $request,
        DocumentRepository $repository,
        ContainerAwareManager $fractal,
        BaseDataTransformer $transformer
    ): JsonResponse {
        $spec = new Specification([
            new NewerFirst()
        ]);
        $documents = $repository->findAllBySpecification($spec, $request->getLimit(), $request->getOffset());
        $count = $repository->findCountBySpecification($spec);

        $paginator = new FractalPaginator($request->getPage(), $request->getPerPage(), $count);
        $collection = new Collection($documents, $transformer, self::DATA_ALIAS);
        $collection->setPaginator($paginator);
        $data = $fractal->createData($collection)->toArray();

        return new JsonResponse($data);
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
     * @SWG\Parameter(
     *     type="object",
     *     parameter="document",
     *     description="Document with changed payload",
     *     name="payload",
     *     in="body",
     *     required=false,
     *     @SWG\Schema(
     *          example={"document":{"payload": {"b": "c"}}}
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Updated document",
     *     @SWG\Schema(
     *          example={"document": {"id": "uuid", "status": "draft", "payload": {}, "createAt": "2018-09-01 20:00:00+07:00", "modifyAt": "2018-09-01 20:01:00+07:00"}}
     *     )
     * )
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
     * Update the document
     * @Route("/{id}", name="get_document", methods={"GET"})
     * @param Document|null $document
     * @param ContainerAwareManager $fractal
     * @param BaseDataTransformer $transformer
     * @return JsonResponse
     * @ParamConverter("document", class="App\Documentation\Entity\Document", isOptional=true,
     *     options={"mapping": {"id" : "id"}})
     * @SWG\Response(
     *     response=200,
     *     description="A document",
     *     @SWG\Schema(
     *          example={"document": {"id": "uuid", "status": "draft", "payload": {}, "createAt": "2018-09-01 20:00:00+07:00", "modifyAt": "2018-09-01 20:01:00+07:00"}}
     *     )
     * ),
     */
    public function getDocument(
        ?Document $document,
        ContainerAwareManager $fractal,
        BaseDataTransformer $transformer
    ): JsonResponse {
        if (!$document) {
            throw new NotFoundHttpException('The document was not found');
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
     * @throws Exception
     * @ParamConverter("document", class="App\Documentation\Entity\Document", isOptional=true,
     *     options={"mapping": {"id" : "id"}})
     * @SWG\Response(
     *     response=200,
     *     description="Published document",
     *     @SWG\Schema(
     *          example={"document": {"id": "uuid", "status": "draft", "payload": {}, "createAt": "2018-09-01 20:00:00+07:00", "modifyAt": "2018-09-01 20:01:00+07:00"}}
     *     )
     * ),
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
