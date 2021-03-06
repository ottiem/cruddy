<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\RuleCollectionStub;
use Illuminate\Support\Facades\App;

class RequestColumnInputStub extends ColumnStub
{
    /**
     * The string needed between values.
     *
     * @var string
     */
    protected $spacer = "',";

    /**
     * The stub containing a collection of rule stubs.
     *
     * @var \Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\RuleCollectionStub
     */
    protected $ruleCollectionStub;

    /**
     * Get the value for the rule.
     *
     * @return string
     */
    protected function getInitialStubValue() : string
    {
        $this->setRuleCollectionStub();

        return $this->addValidationStart() .
            $this->addValidationDefault() .
            $this->addRuleCollectionStub();
    }

    /**
     * Set the rule collection stub.
     *
     * @return self
     */
    protected function setRuleCollectionStub() : self
    {
        $this->ruleCollectionStub = App::make(RuleCollectionStub::class);

        return $this;
    }

    /**
     * Add the rule collection
     *
     * @return string
     */
    protected function addRuleCollectionStub() : string
    {
        return $this->ruleCollectionStub->getUpdatedStub();
    }

    /**
     * Get the column name in the validation string.
     *
     * @return string
     */
    protected function addValidationStart() : string
    {
        return "'" . $this->column->name . "' => '";
    }

    /**
     * Add the default column validation rules.
     *
     * @return string
     */
    protected function addValidationDefault() : string
    {
        return $this->getValidationDefault($this->column->type);
    }
}