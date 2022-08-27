<?php

namespace Tests\Feature;

use Illuminate\Foundation\Application;
use Tests\TestCase;

/**
 * This is just an example test class to make sure the test environment works as expected.
 */
class ExampleTest extends TestCase
{
    /**
     * @test
     */
    public function it_works(): void
    {
        $this->assertTrue(true);

        $this->assertInstanceOf(Application::class, $this->app);
    }
}
