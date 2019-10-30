<?php

namespace App\Example\Controller;

use App\Example\Request\City\GetCitiesListRequest;
use App\Infra\Response\DataResponse;
use Doctrine\ORM\NonUniqueResultException;
use App\Example\Finder\City\BaseListFinder;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Exception\LogicException;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CityController
 * @package App\Example\Controller
 * @Route("/cities", name="cities_")
 * @SWG\Tag(name="example")
 */
class CityController
{
    /**
     * Finds and displays all cities.
     * @Route("/", name="get_all", methods={"GET"})
     * @param GetCitiesListRequest $request
     * @param BaseListFinder $finder
     * @return Response
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws NonUniqueResultException
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns a list of cities",
     *     @SWG\Schema(
     *        type="object"
     *     )
     * )
     */
    public function getCities(GetCitiesListRequest $request, BaseListFinder $finder)
    {
        $cities = $finder->findItems($request);
        $wholeCount = $finder->findCount($request);

        return new DataResponse($cities, $wholeCount);
    }
}
