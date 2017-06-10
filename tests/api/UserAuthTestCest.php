<?php


class UserAuthTestCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function testLoginIsSuccess(ApiTester $I)
    {
        $I->wantToTest('login is success');
        // create user data using factory
        factory(\App\User::class)->create([
            'email' => 'didik@gmail.com',
            'password' => bcrypt('rahasia123')
        ]);

        // send credential data
        $I->sendPOST('api/auth', ['email' => 'didik@gmail.com', 'password' => 'rahasia123']);

        // login success
        $I->seeResponseCodeIs(200);

        // check if returned user data is contain expected email
        $I->seeResponseContainsJson(['email' => 'didik@gmail.com']);
    }

    /**
     * @param ApiTester $I
     */
    public function testLoginIsFailed(ApiTester $I)
    {
        $I->wantToTest('login is failed');
        // create user data
        factory(\App\User::class)->create([
            'email' => 'didik@gmail.com',
            'password' => bcrypt('rahasia123')
        ]);

        // send invalid credential data
        $I->sendPOST('api/auth', ['email' => 'didik@gmail.com', 'password' => 'rahasia12311']);

        // check expected response code
        $I->seeResponseCodeIs(401);
    }

    /**
     * @param ApiTester $I
     */
    public function authenticatedUserSuccessFetchProfile(ApiTester $I)
    {
        $I->wantToTest('authenticated user success fetch profile');

        // create user data
        $user = factory(\App\User::class)->create([
            'email' => 'didik@gmail.com',
            'password' => bcrypt('rahasia123')
        ]);
        // create valid token
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        // set header token Authorization: Bearer {token}
        $I->amBearerAuthenticated($token);

        // send request
        $I->sendGET('api/user');

        // check expected response code
        $I->seeResponseCodeIs(200);

        // check if response data is same with our init user data
        $I->seeResponseContainsJson(['email' => 'didik@gmail.com']);
    }
}
