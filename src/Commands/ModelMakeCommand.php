<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConsoleCommandTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ModelMakeCommand as ConsoleModelMakeCommand;
use Symfony\Component\Console\Input\InputArgument;

class ModelMakeCommand extends ConsoleModelMakeCommand
{
    use CommandTrait, ConsoleCommandTrait;

    /**
     * The type of stub editor.
     *
     * @var string
     */
    protected $stubEditorType = 'model';

    /**
     * The accptable use statement placeholders.
     *
     * @var string[]
     */
    protected $useStatementPlaceholders = [
        'DummyUseStatement',
        '{{ useStatement }}',
        '{{useStatement}}',
    ];

    /**
     * The acceptable model placeholders within a stub.
     *
     * @var string[]
     */
    protected $modelRelationshipPlaceholders = [
        'DummyRelationships',
        '{{ relationships }}',
        '{{relationships}}'
    ];

        
    /**
     * The variable placeholder arrays.
     *
     * @var string[]
     */
    protected $placeholderArrays = [
        'useStatementPlaceholders',
        'modelRelationshipPlaceholders',
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cruddy:model';

    /**
     * The stub editor.
     *
     * @var \Cruddy\StubEditors\ModelStub|null
     */
    protected $stubEditor;

    /**
     * Create a new make command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->setInitialVariables();
        
        $this->stubEditor->setIsPivot($this->option('pivot'))
            ->setInputs($this->getInputs());
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        $arguments = parent::getArguments();
        $arguments[] = ['inputs', null, InputArgument::IS_ARRAY, 'The inputs for the resource'];
        $arguments[] = ['keys', null, InputArgument::IS_ARRAY, 'The keys for the resource'];

        return $arguments;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return parent::getOptions();
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        // $foreignKeys = $this->getKeys();

        // foreach ($foreignKeys as $foreignKey) {
        //     $this->updateStubWithForeignKeys($this->stub, $foreignKey);
        // }
        
        // $this->stubEditor->replaceInStub($this->stubEditor->inputPlaceholders, $this->getModelInputs(), $this->stub)
        //     ->replaceInStub($this->getAllPlaceholders(), '', $this->stub);

        // return $this->stub;

        return $this->stubEditor->getUpdatedStub();
    }

    /**
     * Get the name input.
     *
     * @return string
     */
    protected function getNameInput() : string
    {
        return $this->qualifyClass($this->getStudlySingular($this->getArgument('name')));
    }

    // /**
    //  * Update the stub with the foreign keys information.
    //  *
    //  * @param  string  &$stub
    //  * @param \Cruddy\ForeignKeyDefinition  $foreignKey
    //  * @return void
    //  */
    // protected function updateStubWithForeignKeys(string &$stub, ForeignKeyDefinition $foreignKey) : void
    // {
    //     $modelRelationshipClass = $this->getModelRelationship($foreignKey);
    //     $modelUseStatement = $this->getModelUseStatement($modelRelationshipClass);
    //     $modelRelationship = $this->getModelRelationshipStub($modelRelationshipClass);

    //     if (!$this->stubEditor->stubContains($modelRelationship, $stub)) {
    //         $this->stubEditor->replaceInStub($this->modelRelationshipPlaceholders, $modelRelationship, $stub);
    //     }

    //     if (!$this->stubEditor->stubContains($modelUseStatement, $stub)) {
    //         $this->stubEditor->replaceInStub($this->useStatementPlaceholders, $modelUseStatement, $stub);
    //     }
    // }

    /**
     * Get the stub.
     *
     * @return string
     */
    protected function getStub() : string
    {
        return $this->stubEditor->getStubFile();
    }

    // /**
    //  * Get the model inputs string.
    //  *
    //  * @return string
    //  */
    // protected function getModelInputs() : string
    // {
    //     $inputs = $this->getInputs();
    //     $output = '';

    //     foreach ($inputs as $input) {
    //         $output .= $this->getModelInputsString($input);
    //     }

    //     return $output;
    // }

    // /**
    //  * Get the model input as a string.
    //  *
    //  * @param  \Illuminate\Database\Schema\ColumnDefinition  $input
    //  * @return string
    //  */
    // protected function getModelInputsString(ColumnDefinition $input) : string
    // {
    //     return "'$input->name',\n\t\t";
    // }

    // /**
    //  * Get the model relationship.
    //  *
    //  * @param  \Cruddy\ForeignKeyDefinition  $foreignKey
    //  * @return \Cruddy\ModelRelationships\ModelRelationship
    //  *
    //  * @throws \Cruddy\Exceptions\UnknownRelationshipType
    //  */
    // protected function getModelRelationship(ForeignKeyDefinition $foreignKey) : ModelRelationship
    // {
    //     if (!ModelRelationship::isValidRelationshipType($this->getForeignKeyRelationshipType($foreignKey))) {
    //         throw new UnknownRelationshipType();
    //     }

    //     return App::make(ModelRelationship::class, [$foreignKey]);
    // }

    // /**
    //  * Get the relationship type from the foreign key.
    //  *
    //  * @param  \Cruddy\ForeignKeyDefinition  $foreignKey
    //  * @return string
    //  */
    // protected function getForeignKeyRelationshipType(ForeignKeyDefinition $foreignKey) : string
    // {
    //     return $foreignKey->relationship ?? '';
    // }

    // /**
    //  * Get the model relationship.
    //  *
    //  * @param  \Cruddy\ModelRelationships\ModelRelationship  $modelRelationship
    //  * @return string
    //  */
    // protected function getModelRelationshipStub(ModelRelationship $modelRelationship) : string
    // {
    //     return $modelRelationship->getModelRelationshipStub() . $this->modelRelationshipPlaceholders[0];
    // }

    // /**
    //  * Get the model relationship.
    //  *
    //  * @param  \Cruddy\ModelRelationships\ModelRelationship  $modelRelationship
    //  * @return string
    //  */
    // protected function getModelUseStatement(ModelRelationship $modelRelationship) : string
    // {
    //     return $modelRelationship->getUseStatement() . $this->useStatementPlaceholders[0];
    // }
}
