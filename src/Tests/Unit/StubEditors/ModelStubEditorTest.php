<?php

namespace Cruddy\Tests\Unit\StubEditors;

use Cruddy\StubEditors\ModelStub;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ModelStubTest extends TestCase
{
    /**
     * A test to get the default model stub file.
     *
     * @return void
     */
    public function test_get_default_stub_file()
    {
        $stubsLocation = 'stubs';
        $expectedResult = File::get(dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/model.stub');

        $mock = $this->partialMock(ModelStub::class, function (MockInterface $mock) use ($stubsLocation) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getStubsLocation')
                ->once()
                ->andReturn($stubsLocation);
        });

        $result = $mock->getStubFile();

        $this->assertSame($expectedResult, $result);
    }
}