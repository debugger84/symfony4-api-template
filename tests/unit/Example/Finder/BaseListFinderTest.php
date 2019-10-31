<?php

namespace App\Tests\Unit\Example\Finder;

use App\Example\Finder\City\BaseListFinder;
use App\Example\Request\City\GetCitiesListRequest;
use App\Tests\UnitTester;
use Codeception\Test\Unit;

class CoinManagerServiceTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testFindWithOffset()
    {
        $request = new GetCitiesListRequest(2, 1);

        /** @var BaseListFinder $finder */
        $finder = $this->tester->grabService(BaseListFinder::class);
        $items = $finder->findItems($request);

        $this->tester->assertCount(1, $items);
        $this->tester->assertEquals(2, $items[0]['id']);
    }

    public function testFindWithFilteringByName()
    {
        $request = new GetCitiesListRequest(1, 2, 'yiv');

        /** @var BaseListFinder $finder */
        $finder = $this->tester->grabService(BaseListFinder::class);
        $items = $finder->findItems($request);

        $this->tester->assertCount(1, $items);
        $this->tester->assertEquals(1, $items[0]['id']);

        $request = new GetCitiesListRequest(1, 2, 'lv');

        /** @var BaseListFinder $finder */
        $finder = $this->tester->grabService(BaseListFinder::class);
        $items = $finder->findItems($request);

        $this->tester->assertCount(1, $items);
        $this->tester->assertEquals(2, $items[0]['id']);

        $request = new GetCitiesListRequest(1, 2, 'lviv');

        /** @var BaseListFinder $finder */
        $finder = $this->tester->grabService(BaseListFinder::class);
        $items = $finder->findItems($request);

        $this->tester->assertCount(1, $items);
        $this->tester->assertEquals(2, $items[0]['id']);
    }
}
