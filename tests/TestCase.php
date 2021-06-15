<?php

namespace Tests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp() :void {
        parent::setUp();

        $this->artisan('migrate');
        $this->artisan('db:seed');

    }

    protected function signIn($user = null){
        $user = $user ?: User::factory()->create();
        $this->actingAs($user);

        return $user;
    }
}
