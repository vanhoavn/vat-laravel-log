<?php

namespace Tests;

use Faker\Generator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $faker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = app(Generator::class);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array  $data
     * @return array
     */
    public function getJsonContent($method = 'GET', $uri, $data = [])
    {
        return json_decode($this->json($method, $uri, $data)->getContent(), true);
    }
}
