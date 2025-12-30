<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Seed the database before each test run to provide default roles and admin user.
     */
    protected bool $seed = true;

    /**
     * Seeder class that will be executed when seeding is enabled.
     */
    protected string $seeder = \Database\Seeders\DatabaseSeeder::class;
}
