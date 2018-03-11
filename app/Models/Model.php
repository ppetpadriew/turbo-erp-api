<?php

namespace App\Models;


use ReflectionClass;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    const TABLE = '';
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $scenario = self::SCENARIO_CREATE;

    private $scenarios = [];

    const CREATED_AT = 'created_datetime';
    const UPDATED_AT = 'updated_datetime';

    public function __construct(array $attributes = [])
    {
        if (empty(static::TABLE)) {
            throw new \Exception('Please define table name in model class.');
        }

        $this->table = static::TABLE;
        parent::__construct($attributes);
    }

    public function setScenario(string $scenario): void
    {
        if (!$this->isValidScenario($scenario)) {
            throw new \InvalidArgumentException("Scenario '{$scenario}' is not implemented yet.");
        }

        $this->scenario = $scenario;
    }

    public function isValidScenario(string $scenario): bool
    {
        return in_array($scenario, $this->getScenarios());
    }

    public function getScenarios(): array
    {
        if ($this->scenarios) {
            return $this->scenarios;
        }

        $constants = (new ReflectionClass(get_class($this)))->getConstants();
        $this->scenarios = array_filter($constants, function ($constant) {
            return strpos($constant, 'SCENARIO_') !== false;
        }, ARRAY_FILTER_USE_KEY);

        return $this->scenarios;
    }

    public function fillable(array $fillable)
    {
        throw new \Exception('We do not allow to set fillable dynamically from the outside.');
    }

    public function getFillable()
    {
        throw new \Exception('Please define fillable fields in your subclasses.');
    }

    public function newInstance($attributes = [], $exists = false)
    {
        $model = parent::newInstance($attributes, $exists);
        $scenario = $model->exists
            ? self::SCENARIO_UPDATE
            : self::SCENARIO_CREATE;

        $model->setScenario($scenario);

        return $model;
    }

    abstract public function getRules(string $scenario): array;

    abstract public function getAttributeDefaultValues(): array;
}
