<?php

namespace App\Tests\Functional\Auth;

use App\Example\Controller\CityController;
use App\Tests\FunctionalTester;
use App\User\Controller\AuthController;

class AuthCest
{
    /**
     * @param FunctionalTester $I
     * @see AuthController::loginWithPassword()
     */
    public function authUser(FunctionalTester $I)
    {
        $I->wantToTest('Auth user');
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPOST('/user/auth/login', [
            'email' => 'test@test.com',
            'password' => 'test',
        ]);

        $resp = \GuzzleHttp\json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token');
    }
}
