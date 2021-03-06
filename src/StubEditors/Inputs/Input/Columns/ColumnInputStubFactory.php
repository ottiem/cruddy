<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

use Cruddy\Exceptions\StubEditors\Inputs\Input\UnknownStubInputEditorType;
use Cruddy\Factory;
use Cruddy\StubEditors\Inputs\Input\InputStub;
use Illuminate\Database\Schema\ColumnDefinition;

class ColumnInputStubFactory extends Factory
{
    /**
     * The constructor method.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @param  string  $inputStubEditor = 'controller'
     * @param  array  $foreignKeys = []
     * @return void
     */
    public function __construct(protected ColumnDefinition $column, protected string $inputStubEditor = 'controller', protected array $foreignKeys = [])
    {
        parent::__construct();
    }

    /**
     * Set the parameters.
     *
     * @return void
     */
    protected function setParameters() : void
    {
        $this->parameters = [
            $this->column,
            $this->foreignKeys,
        ];
    }

    /**
     * Get the correct InputStub
     *
     * @return \Cruddy\StubEditors\Inputs\Input\InputStub
     *
     * @throws \Cruddy\Exceptions\StubEditors\Inputs\Input\UnknownStubInputEditorType
     */
    public function get() : InputStub
    {
        switch ($this->inputStubEditor) {
            case 'controller':
                return $this->makeClass(ControllerColumnInputStub::class);
            case 'request':
                return $this->makeClass(RequestColumnInputStub::class);
            case 'view':
                return $this->makeClass(ViewColumnInputStub::class);
            default:
                throw new UnknownStubInputEditorType();
        }
    }
}