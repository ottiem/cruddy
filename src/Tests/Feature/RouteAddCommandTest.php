<?php

namespace Cruddy\Tests\Feature;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\RouteAddCommandTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class RouteAddCommandTest extends TestCase
{
    use DatabaseTransactions, RouteAddCommandTrait, TestTrait;

    /**
     * Determine if the resource is for an api.
     *
     * @var boolean
     */
    public $isApi;

    public function setUp() : void
    {
        parent::setUp();
        $this->name = 'name';
        $this->file = $this->getRouteFile();
        $this->resourceRoute = $this->getResourceRoute();
    }

    /**
     * A test for adding the new import statement to the default route file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_import_statement_added_to_default_route_file()
    {
        File::shouldReceive('exists')
            ->with($this->file)
            ->once()
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($this->file)
            ->once()
            ->andReturn('');

        File::shouldReceive('append')
            ->with($this->file, $this->resourceRoute)
            ->once();

        File::partialMock();

        $this->artisan('cruddy:route', [
            'name' => $this->name
        ])->expectsOutput($this->successMessage);
    }
    
    /**
     * A test for adding the new import statement to the api route file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_import_statement_added_to_api_route_files()
    {
        $this->isApi = true;
        $this->defaultRouteFileName = 'api';
        $this->defaultResourceType = 'apiResource';
        $file = $this->getRouteFile(); // Get the route file with the new defaults
        $resourceRoute = $this->getResourceRoute();

        File::shouldReceive('exists')
            ->with($file)
            ->once()
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($file)
            ->once()
            ->andReturn('');

        File::shouldReceive('append')
            ->with($file, $resourceRoute)
            ->once();

        $this->artisan('cruddy:route', [
            'name' => $this->name,
            '--api' => $this->isApi
        ])->expectsOutput($this->successMessage);
    }
    
    /**
     * A test for the route statement not being duplicated within the route file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_import_statement_not_duplicated_in_route_file()
    {
        File::shouldReceive('exists')
            ->with($this->file)
            ->once()
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($this->file)
            ->once()
            ->andReturn($this->resourceRoute);

        File::shouldReceive('append')
            ->with($this->file, $this->resourceRoute)
            ->never();

        $this->artisan('cruddy:route', [
            'name' => $this->name,
        ])->expectsOutput($this->noRoutesAddedMessage);
    }
    
    /**
     * A test for when the route file does not exist.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_route_file_does_not_exist()
    {
        File::shouldReceive('exists')
            ->with($this->file)
            ->once()
            ->andReturn(false);

        File::shouldReceive('get')
            ->with($this->file)
            ->never()
            ->andReturn($this->resourceRoute);

        File::shouldReceive('append')
            ->with($this->file, $this->resourceRoute)
            ->never();

        $this->artisan('cruddy:route', [
            'name' => $this->name,
        ])->expectsOutput($this->noRoutesAddedMessage);
    }
}