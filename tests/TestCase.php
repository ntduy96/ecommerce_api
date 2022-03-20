<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Login to get access token.
     *
     * @return string
     */
    protected function login(): string
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@mail.com',
            'password' => 'password'
        ]);
        $content = json_decode($response->getContent());

        $token = $content->token->plainTextToken;

        return $token;
    }
}
