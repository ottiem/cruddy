<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

use Cruddy\Exceptions\StubEditors\Inputs\Input\UnknownStubInputEditorType;
use Cruddy\Factory;
use Cruddy\StubEditors\Inputs\Input\InputStubEditor;
use Illuminate\Database\Schema\ColumnDefinition;

class ColumnInputStubEditorFactory extends Factory
{
    /**
     * The constructor method.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @param  string  $inputStubEditor = 'controller'
     * @param  string  &$stub = ''
     * @return void
     */
    public function __construct(protected ColumnDefinition $column, protected string $inputStubEditor = 'controller', protected string &$stub = '')
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
            $this->stub
        ];
    }

    /**
     * Get the correct InputStubEditor
     *
     * @return \Cruddy\StubEditors\Inputs\Input\InputStubEditor
     *
     * @throws \Cruddy\Exceptions\StubEditors\Inputs\Input\UnknownStubInputEditorType
     */
    public function get() : InputStubEditor
    {
        switch ($this->inputStubEditor) {
            case 'controller':
                return $this->makeClass(ControllerStubInputEditor::class);
            case 'request':
                return $this->makeClass(RequestStubInputEditor::class);
            case 'view':
                return $this->makeClass(ViewStubInputEditor::class);
            default:
                throw new UnknownStubInputEditorType();
        }
    }
}