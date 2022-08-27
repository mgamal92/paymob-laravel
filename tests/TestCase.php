<?php

namespace Tests;

use Illuminate\Foundation\Application;
use MG\Paymob\PaymobServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * Base test case class.
 * All other test cases should extend this class.
 */
class TestCase extends OrchestraTestCase
{
    /**
     * Get package providers.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            PaymobServiceProvider::class,
        ];
    }
}
