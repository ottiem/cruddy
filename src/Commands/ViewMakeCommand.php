<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\FormTrait;
use Cruddy\Traits\Stubs\ModelTrait;
use Cruddy\Traits\Stubs\VariableTrait;
use Cruddy\Traits\Stubs\VueTrait;
use Cruddy\Traits\ViewMakeCommandTrait;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class ViewMakeCommand extends GeneratorCommand
{
    use ViewMakeCommandTrait, ModelTrait, VariableTrait, CommandTrait, FormTrait, VueTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cruddy:view
                            { name : The name of the resource that needs new views. }
                            { table : The name of the table within the migration. }
                            { type=index : The type of file being created. }
                            { inputs?* : The inputs needed within the file. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Cruddy view';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Cruddy view';

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
        $stub = $this->files->get($this->getStub());
        $name = $this->getNameInput();
        $model = $this->getClassBasename($name);

        if ($this->needsVueFrontend()) {
            $editUrl = "'/$name/' + item.id + '/edit'";
        } else {
            $editUrl = '/' . $name . '/{{ $' . $this->getCamelCaseSingular($name) . '->id }}/edit';
        }

        $cancelUrl = '/' . $name;


        $route = '';

        if ($this->getType() === 'create' || ($this->getType() === 'index' && $this->needsVueFrontend())) {
            $route = '/' . $name;
        } else if ($this->getType() === 'edit') {
            if ($this->needsVueFrontend()) {
                $route = "'/$name/' + this.item.id";
            } else {
                $route = '/' . $name . '/{{ $' . $this->getCamelCaseSingular($name) . '->id }}';
            }
        }


        $vueDataString = '';
        $vuePostDataString = '';
        $componentName = '';
        if ($this->needsVueFrontend()) {
            $inputs = $this->argument('inputs');

            foreach ($inputs as $input) {
                $vueDataString .= $this->getVueDataString($input);
                $vuePostDataString .= $this->getVuePostDataString($input);
            }

            $studylyTableName = $this->getStudlySingular($this->getTableName());
            $ucFirstType = Str::ucfirst($this->getType());
            $componentName = $studylyTableName . $ucFirstType;
        }


        return $this->replaceNamespace($stub, $name)
            ->replaceInStub($this->inputPlaceholders, $this->getInputsString(), $stub)
            ->replaceInStub($this->actionPlaceholders, $route, $stub)
            ->replaceInStub($this->editUrlPlaceholders, $editUrl, $stub)
            ->replaceInStub($this->variableCollectionPlaceholders, $this->getCamelCasePlural($name), $stub)
            ->replaceInStub($this->variablePlaceholders, $name, $stub)
            ->replaceInStub($this->cancelUrlPlaceholders, $cancelUrl, $stub)
            ->replaceInStub($this->modelPlaceholders, $model, $stub)
            ->replaceInStub($this->vueComponentPlaceholders, $componentName, $stub)
            ->replaceInStub($this->vueDataPlaceholders, $vueDataString, $stub)
            ->replaceInStub($this->vuePostDataPlaceholders, $vuePostDataString, $stub)
            ->replaceClass($stub, $name);
    }

    /**
     * Get the class name from the table argument.
     *
     * @return string
     */
    protected function getClassName() : string
    {
        return $this->getStudlySingular($this->getTableName()) ?? '';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name) : string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $name = strtolower($name);

        if ($this->needsVueFrontend(true)) {
            return $this->getVueFolder() . '/' . $this->getClassName() . '/' . $this->getType() . '.vue';
        }

        return str_replace('\\', '/', $name) . '/' . $this->getType() . '.blade.php';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() : array
    {
        return [
            ['type', InputArgument::OPTIONAL, 'The type of view'],
            ['inputs', InputArgument::OPTIONAL, 'The inputs for the view'],
            ['name', InputArgument::OPTIONAL, 'The name of the resource'],
            ['table', InputArgument::OPTIONAL, 'The name of the table'],
        ];
    }
}
