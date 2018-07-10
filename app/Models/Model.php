<?php

namespace App\Models;


use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Facades\Validator;
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

    /** @var MessageBag */
    protected $errors;
    /** @var string */
    protected $scenario = self::SCENARIO_CREATE;

    /** @var array */
    private $scenarios = [];

    /**
     * Override timestamp fields of laravel
     * @see https://laravel.com/docs/5.6/eloquent
     */
    const CREATED_AT = 'created_datetime';
    const UPDATED_AT = 'updated_datetime';

    /**
     * Model constructor.
     * @param array $attributes
     * @throws \Exception
     */
    public function __construct(array $attributes = [])
    {
        if (empty(static::TABLE)) {
            throw new \Exception('Please define table name in model class.');
        }

        $this->table = static::TABLE;
        parent::__construct($attributes);
    }

    /**
     * Since our convention to name the relation is snake_case. But the method for the relation is camelCase.
     * So we have to convert snake_case to camelCase.
     *
     * @param string $key
     * @return mixed
     */
    public function getRelationValue($key)
    {
        $camelizedKey = lcfirst(str_replace('_', '', ucwords($key, '_')));

        return parent::getRelationValue($camelizedKey);
    }

    /**
     * @param string $scenario
     */
    public function setScenario(string $scenario): void
    {
        if (!$this->isValidScenario($scenario)) {
            throw new \InvalidArgumentException("Scenario '{$scenario}' is not implemented yet.");
        }

        $this->scenario = $scenario;
    }

    /**
     * @param string $scenario
     * @return bool
     */
    public function isValidScenario(string $scenario): bool
    {
        return in_array($scenario, $this->getScenarios());
    }

    /**
     * @return array
     */
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
        return array_keys($this->getParsedRules($this->scenario));
    }

    public function newInstance($attributes = [], $exists = false)
    {
        /** @var Model $model */
        $model = parent::newInstance($attributes, $exists);
        $scenario = $model->exists
            ? self::SCENARIO_UPDATE
            : self::SCENARIO_CREATE;

        $model->setScenario($scenario);

        return $model;
    }

    public function save(array $options = [])
    {
        return $this->validate() && parent::save($options);
    }

    public function fill(array $attributes)
    {
        return parent::fill($attributes + $this->getAttributeDefaultValues());
    }

    /**
     * @return MessageBag|null
     */
    public function getErrors(): ?MessageBag
    {
        return $this->errors;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function validate(): bool
    {
        $validator = Validator::make(
            $this->attributes,
            $this->getParsedRules($this->scenario)
        );

        if ($validator->fails()) {
            $this->errors = $validator->errors();
        }

        return empty($this->errors);
    }

    /**
     * Parse rules in the format that Laravel can understand
     * @see https://laravel.com/docs/5.6/validation
     *
     * @param string $scenario
     * @return array
     * @throws \Exception
     */
    protected function getParsedRules(string $scenario): array
    {
        $parsedRules = $this->parseRules($this->getRules());

        return $parsedRules
            ? $parsedRules[$scenario]
            : $parsedRules;
    }

    /**
     * @param array $rules
     * @return array
     * @throws \Exception
     */
    protected function parseRules(array $rules): array
    {
        $parsedRules = [];
        foreach ($rules as $rule) {
            $validator = $rule[0];
            $attributes = $rule[1] ?? [];
            $scenarios = $rule[2] ?? $this->getScenarios();

            if (!is_array($scenarios)) {
                throw new \Exception('Scenarios must be an array.');
            }

            foreach ($scenarios as $scenario) {
                foreach ($attributes as $attribute) {
                    $parsedRules[$scenario][$attribute][] = $validator;
                }
            }
        }

        return $parsedRules;
    }

    /**
     * @return array
     */
    abstract protected function getRules(): array;

    /**
     * @return array
     */
    abstract public function getAttributeDefaultValues(): array;
}
