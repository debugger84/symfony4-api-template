<?php


namespace App\Example\Finder\City;


use App\Example\Repository\CityRepository;
use App\Example\Repository\Specification\City\AdditionalData\WithRegion;
use App\Example\Repository\Specification\City\Condition\NameHasSubstring;
use App\Example\Repository\Specification\City\Ordering\OrderByName;
use App\Example\Repository\Specification\City\Selection\SelectBaseData;
use App\Example\Repository\Specification\Region\Selection\SelectBaseData as SelectRegionBaseData;
use App\Example\Request\City\GetCitiesListRequest;
use Doctrine\ORM\NonUniqueResultException;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Exception\LogicException;
use Rb\Specification\Doctrine\Specification;

class BaseListFinder
{
    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * BaseListFinder constructor.
     * @param CityRepository $cityRepository
     */
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * @param GetCitiesListRequest $request
     * @psalm-type RegionBaseData=array{id:int,name:string}
     * @psalm-type CityBaseDataWithRegion=array{id:int,name:string,region:RegionBaseData}
     * @psalm-return CityBaseDataWithRegion
     * @return array[[
     *    "id" => 1,
     *    "name"=> "string"
     *    "region"=> [
     *        "id"=> 1,
     *        "name"=> "string"
     *    ]
     * ]]
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public function findItems(GetCitiesListRequest $request): array
    {
        $spec = $this->getSpecification($request->getName());
        return $this->cityRepository->findArrayResultBySpecification(
            $spec,
            $request->getLimit(),
            $request->getOffset()
        );
    }

    /**
     * @param GetCitiesListRequest $request
     * @return int
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws NonUniqueResultException
     */
    public function findCount(GetCitiesListRequest $request): int
    {
        $spec = $this->getSpecification($request->getName());
        return $this->cityRepository->findCountBySpecification(
            $spec
        );
    }

    /**
     * @param string|null $name
     * @return Specification
     * @throws InvalidArgumentException
     */
    private function getSpecification(?string $name): Specification
    {
        $spec = new Specification([
            new WithRegion(),
            new SelectBaseData(false),
            new SelectRegionBaseData(),
            new OrderByName(),
        ]);
        if ($name) {
            $spec->add(new NameHasSubstring($name));
        }

        return $spec;
    }
}
