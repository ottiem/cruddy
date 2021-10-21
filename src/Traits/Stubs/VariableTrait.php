<?php

namespace Cruddy\Traits\Stubs;

use Illuminate\Support\Str;

trait VariableTrait
{
    use StubTrait;

    /**
     * The acceptable value placeholders within a stub.
     *
     * @var array
     */
    protected $valuePlaceholders = [
        'DummyValue',
        '{{ value }}',
        '{{value}}'
    ];

    /**
     * The acceptable collection of variable placeholders within a stub.
     *
     * @var array
     */
    protected $variableCollectionPlaceholders = [
        'DummyVariableCollection',
        '{{ variableCollection }}',
        '{{variableCollection}}'
    ];

    /**
     * The acceptable variable placeholders within a stub.
     *
     * @var array
     */
    protected $variablePlaceholders = [
        'DummyVariable',
        '{{ variable }}',
        '{{variable}}'
    ];

    /*
     * The acceptable resource placeholders within a stub.
     *
     * @var array
     */
   protected $resourcePlaceholders = [
       'DummyResource',
       '{{ resource }}',
       '{{resource}}'
   ];

    /**
     * Get the studly singular version of the string with the first character lower-case.
     *
     * @param  string  $value
     * @return string
     */
    protected function getCamelCaseSingular(string $value) : string
    {
        return lcfirst(Str::studly(Str::singular(trim($value)))) ?? '';
    }

    /**
     * Get the studly plural version of the string with the first character lower-case.
     *
     * @param  string  $value
     * @return string
     */
    protected function getCamelCasePlural(string $value) : string
    {
        return lcfirst(Str::pluralStudly(trim($value))) ?? '';
    }
}