<?php

namespace Cruddy\Tests;

use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\File;
use Cruddy\ServiceProvider;

trait TestTrait {

    /**
     * The name for the string column.
     *
     * @var string
     */
    protected $nameString = 'name-string';

    /**
     * The name for the integer column.
     *
     * @var string
     */
    protected $nameInteger = 'name-integer';

    /**
     * The name for the bigInteger column.
     *
     * @var string
     */
    protected $nameBigInteger = 'name-bigInteger';

    /**
     * Whether to load the environment variables for the tests.
     *
     * @var boolean
     */
    protected $loadEnvironmentVariables = true;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * This method is needed on the class and should not actually do anything.
     * Must have the same number of parameters as original "option" method 
     * and must return a value for mocks.
     * 
     * @return mixed
     */
    public function replaceClass($x, $y) : mixed
    {
        // Need to return a value for mocking.
        return $this;
    }

    /**
     * This method is needed on the class and should not actually do anything.
     * Must have the same number of parameters as original "argument" method 
     * and must return a value for mocks.
     * 
     * @return mixed
     */
    public function argument($x) : mixed
    {
        // Need to return a value for mocking.
        return $x;
    }

    /**
     * This method is needed on the class and should not actually do anything.
     * Must have the same number of parameters as original "option" method 
     * and must return a value for mocks.
     * 
     * @return mixed
     */
    public function option($x) : mixed
    {
        if ($x == 'inputs') {
            // Return a valid array of inputs
            return $this->inputs;
        }

        if ($x == 'api') {
            // Return a valid boolean value
            return $this->isApi ?? false;
        }

        // Need to return a value for mocking.
        return $x;
    }

    /**
     * Get an array of all the acceptable rules in an acceptable format.
     *
     * @return array
     */
    public function getMockColumns() : array
    {
        return [
            new ColumnDefinition([
                'type' => 'string',
                'name' => $this->nameString,
                'length' => 200,
                'inputType' => 'text',
            ]),
            new ColumnDefinition([
                'type' => 'integer',
                'name' => $this->nameInteger,
                'min' => 1,
                'max' => 1000,
                'inputType' => 'number',
            ]),
            // new ColumnDefinition([
            //     'type' => 'bigInteger',
            //     'name' => $this->nameBigInteger,
            //     'min' => 1,
            //     'max' => 9999,
            // ]),
        ];
    }

    /**
     * Get the expected location for the stub.
     *
     * @param  string  $type
     * @return string
     */
    protected function getExpectedTypeStubLocation(string $type = 'index') : string
    {
        return base_path() . '/stubs/views/default/' . $type . '.stub';
    }

    /**
     * Get the location for the test stub.
     *
     * @param  string  $type
     * @return string
     */
    protected function getTypeStubLocation(string $type = 'index') : string
    {
        return dirname(__DIR__) . '/Commands/stubs/views/default/' . $type . '.stub';
    }

    /**
     * Get the expected location for the stub.
     *
     * @param  string  $type
     * @return string
     */
    protected function getExpectedStubLocation(string $type = 'controller') : string
    {
        return base_path() . '/stubs/' . $type . '.stub';
    }

    /**
     * Get the location for the test stub.
     *
     * @param  string  $type
     * @return string
     */
    protected function getStubLocation(string $type = 'controller') : string
    {
        return dirname(__DIR__) . '/Commands/stubs/' . $type . '.stub';
    }

    /**
     * Get the expected request file location.
     *
     * @param  string  $name
     * @return string
     */
    protected function getExpectedRequestFileLocation(string $name) : string
    {
        return app_path() . '/Http/Requests/' . $name . '.php';
    }

    /**
     * Get the request stub location.
     *
     * @param  string  $type
     * @return string
     */
    protected function getRequestLocation(string $type = 'update') : string
    {
        return __DIR__ . '/stubs/requests/expectedRequestFile' . ucfirst($type) . '.stub';
    }

    /**
     * Get the expected final request file with correct values.
     *
     * @param  string  $type
     * @return string
     */
    protected function getExpectedRequestFile(string $type = 'update') : string
    {
        return File::get($this->getRequestLocation($type));
    }
}