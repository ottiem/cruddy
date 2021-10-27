<?php

namespace Cruddy\Traits;

use Cruddy\Traits\Stubs\InputTrait;

trait ModelMakeCommandTrait
{
    use CommandTrait, InputTrait;

    /**
     * Get the stub file.
     *
     * @return string
     */
    protected function getStub() : string
    {
        return $this->resolveStubPath($this->getStubsLocation() . '/model.stub');
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name) : string
    {
        $stub = $this->getStubFile();
        $modelInputs = $this->getModelInputs();

        return $this->replaceNamespace($stub, $name)
            ->replaceInStub($this->modelPlaceholders, $modelInputs, $stub)
            ->replaceClass($stub, $name);
    }

    /**
     * Get the model inputs string.
     *
     * @return string
     */
    protected function getModelInputs() : string
    {
        $inputs = $this->getInputs();

        return $this->getModelInputsString($inputs);
    }

    /**
     * Get the iput string from the inputs.
     *
     * @param  array  $inputs
     * @return string
     */
    protected function getModelInputsString(array $inputs) : string
    {
        $inputsString = '';

        foreach ($inputs as $input) {
            $inputsString .= $this->getInputString($input, $this->isVueEditOrShow());
        }

        $this->removeEndOfLineFormatting($inputsString);

        return $inputsString;
    }
}