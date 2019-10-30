<?php

namespace App\Tests\Functional\Example;

use App\Example\Controller\CityController;
use App\Tests\FunctionalTester;

class CityCest
{
    /**
     * @param FunctionalTester $I
     * @see CityController::getCities()
     */
    public function getListOfCities(FunctionalTester $I)
    {
        $I->wantToTest('Get list of cities');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amAuthenticatedAsUser1();

        $I->sendGET('/example/cities', [
            'page' => 1
        ]);

        $resp = \GuzzleHttp\json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => true,
            'data' => [
                0 => [
                    'id' => 1,
                    'name' => 'Kyiv',
                    'region' => [
                        'id' => 1,
                        'name' => 'Kyiv area',
                    ],
                ],
                1 => [
                    'id' => 2,
                    'name' => 'Lviv',
                    'region' => [
                        'id' => 2,
                        'name' => 'Lviv area',
                    ],
                ]
            ],
            'wholeCount' => 2
        ]);
    }

    /**
     * @param FunctionalTester $I
     * @see CityController::getCities()
     */
    public function getListOfCitiesFilteredByName(FunctionalTester $I)
    {
        $I->wantToTest('Get list of cities');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amAuthenticatedAsUser1();

        $I->sendGET('/example/cities', [
            'page' => 1,
            'name' => 's'
        ]);

        $resp = \GuzzleHttp\json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => true,
            'data' => [
            ],
            'wholeCount' => 0
        ]);
    }
}
