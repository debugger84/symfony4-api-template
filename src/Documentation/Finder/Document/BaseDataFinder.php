<?php


namespace App\Documentation\Finder\Document;


use App\Documentation\Repository\DocumentRepository;
use App\Example\Repository\Specification\City\AdditionalData\WithRegion;
use App\Example\Repository\Specification\City\Condition\NameHasSubstring;
use App\Example\Repository\Specification\City\Ordering\OrderByName;
use App\Example\Repository\Specification\City\Selection\SelectBaseData;
use App\Example\Repository\Specification\Region\Selection\SelectBaseData as SelectRegionBaseData;
use App\Example\Request\City\GetCitiesListRequest;
use App\Infra\Repository\Specification\IdInList;
use Doctrine\ORM\NonUniqueResultException;
use Ramsey\Uuid\UuidInterface;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Exception\LogicException;
use Rb\Specification\Doctrine\Specification;

class BaseDataFinder
{
    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * BaseDataFinder constructor.
     * @param DocumentRepository $documentRepository
     */
    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * @param UuidInterface $id
     * @return array[
     *    "id" => "uuid4",
     *    "status"=> "draft|published",
     *    "payload"=> [
     *    ],
     *    "createAt" => '2018-09-01 20:00:00+07:00'
     *    "modifyAt" => '2018-09-01 20:00:00+07:00'
     * ]|null
     * @psalm-type DocumentBaseData=array{id:string,status:string,payload:array,createAt:string,modifyAt:string}
     * @psalm-return DocumentBaseData
     */
    public function findOneItem(UuidInterface $id): ?array
    {
        $spec = new Specification([
            new IdInList([$id], DocumentRepository::DQL_ALIAS)
        ]);

        return $this->documentRepository->findArrayResultBySpecification($spec);
    }
}
