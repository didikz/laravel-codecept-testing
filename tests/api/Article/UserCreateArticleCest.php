<?php
namespace Article;
use \ApiTester;

class UserCreateArticleCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    /**
     * @param ApiTester $I
     */
    public function authenticatedUserSuccessCreateArticle(ApiTester $I)
    {
        $I->wantToTest('authenticated user success create article');

        // init user
        $user = factory(\App\User::class)->create([
            'email' => 'didik@gmail.com',
            'password' => bcrypt('rahasia123')
        ]);

        // generate jwt token
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        // set header authorization
        $I->amBearerAuthenticated($token);

        // send request article data
        $I->sendPOST('api/user/articles', [
            'title' => 'this is title',
            'content' => 'This is content from user',
            'is_published' => 'y'
        ]);

        // check expected response code is 200 OK
        $I->seeResponseCodeIs(200);

        // see response json is containing our expected data
        $I->seeResponseContainsJson(['title' => 'this is title', 'content' => 'This is content from user', 'is_published' => 'y', 'user_id' => $user->id]);

        // see database row is containing our expected data
        $I->seeRecord('articles', ['user_id' => $user->id, 'title' => 'this is title', 'content' => 'This is content from user', 'is_published' => 'y']);
    }

    /**
     * @param ApiTester $I
     */
    public function authenticatedUserFailedCreateArticleUsingEmptyTitle(ApiTester $I)
    {
        $I->wantToTest('authenticated user failed create article using empty title');

        // init user data
        $user = factory(\App\User::class)->create([
            'email' => 'didik@gmail.com',
            'password' => bcrypt('rahasia123')
        ]);

        // generate authorization token
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        // set request header
        $I->amBearerAuthenticated($token);
        $I->haveHttpHeader('Accept', 'application/json');

        // send request article data
        $I->sendPOST('api/user/articles', [
            'title' => '',
            'content' => 'This is content from user',
            'is_published' => 'y'
        ]);

        // check response code is 422 unprocessable entity
        $I->seeResponseCodeIs(422);
    }

    /**
     * @param ApiTester $I
     */
    public function unauthenticatedUserFailedCreateArticle(ApiTester $I)
    {
        $I->wantToTest('unauthenticated user failed create article');

        // set request header
        $I->haveHttpHeader('Accept', 'application/json');

        // send request article data
        $I->sendPOST('api/user/articles', [
            'title' => 'lorem ipsum',
            'content' => 'This is content from user',
            'is_published' => 'y'
        ]);

        // see response code is 400 bad request since we didn't include authorization token
        $I->seeResponseCodeIs(400);
    }

    /**
     * @param ApiTester $I
     */
    public function unauthorizedUserFailedCreateArticleUsingInvalidToken(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed create article using invalid token');

        // set request header
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');

        // send request article header
        $I->sendPOST('api/user/articles', [
            'title' => 'lorem ipsum',
            'content' => 'This is content from user',
            'is_published' => 'y'
        ]);

        // see response code is 400 bad request since we didn't include valid authorization token
        $I->seeResponseCodeIs(400);
    }
}
