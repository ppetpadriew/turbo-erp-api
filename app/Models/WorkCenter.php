<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

/**
 * Class WorkCenter
 * @package App\Models
 *
 * @property int $id
 * @property string $code
 * @property string $description
 * @property string $type
 * @property boolean $blocked
 * @property double $wait_time
 * @property double $move_time
 * @property double $queue_time
 * @property int $time_unit_id
 * @property int $shop_floor_warehouse_id
 * @property int $backflush_employee_id
 * @property int $parent_work_center_id
 * @property int $costing_work_center_id
 * @property int $operation_rate_id
 * @property int $production_department_id
 *
 * Relations
 *
 * @property Unit $time_unit
 * @property Warehouse $shop_floor_warehouse
 * @property WorkCenter $parent_work_center
 * @property WorkCenter $costing_work_center
 */
class WorkCenter extends Model
{
    const TABLE = 'work_center';

    const TYPE_WORK_CENTER = 'Work Center';
    const TYPE_SUB_WORK_CENTER = 'Sub Work Center';
    const TYPE_SUBCONTRACTING = 'SubContracting Work Center';
    const TYPE_COST = 'Costing Work Center';
    const TYPES = [
        self::TYPE_WORK_CENTER,
        self::TYPE_SUB_WORK_CENTER,
        self::TYPE_SUBCONTRACTING,
        self::TYPE_COST,
    ];

    public function getAttributeDefaultValues(): array
    {
        return [
            'blocked'    => false,
            'wait_time'  => 0,
            'move_time'  => 0,
            'queue_time' => 0,
        ];
    }

    public function getRules(string $scenario): array
    {
        $create = [
            'code'                     => ['required', 'max:6', "unique:{$this->table}"],
            'description'              => ['nullable', 'max:30'],
            'type'                     => ['required', Rule::in(self::TYPES)],
            'blocked'                  => ['required', 'boolean'],
            // @todo: use double validator
            'wait_time'                => ['required'],
            'move_time'                => ['required'],
            'queue_time'               => ['required'],
            'time_unit_id'             => ['required', 'exists' . Unit::TABLE . ',id'],
            'shop_floor_warehouse_id'  => ['nullable', 'exists' . Warehouse::TABLE . ',id'],
            // @todo: validate reference to employee
            'backflush_employee_id'    => ['nullable'],
            'parent_work_center_id'    => ['nullable', 'exists' . $this->table . ',id'],
            'costing_work_center_id'   => ['nullable', 'exists' . $this->table . ',id'],
            // @todo: validate reference to operation rate
            'operation_rate_id'        => ['nullable'],
            // @todo: validate reference to department
            'production_department_id' => ['nullable'],
        ];
        $rules = [
            self::SCENARIO_CREATE => $create,
            self::SCENARIO_UPDATE => [
                    'code' => [],
                ] + $create,
        ];

        return $scenario
            ? $rules[$scenario]
            : $rules;
    }

    public function getFillable()
    {
        $fillable = [
            self::SCENARIO_CREATE => [
                'code',
                'description',
                'type',
                'blocked',
                'wait_time',
                'move_time',
                'queue_time',
                'time_unit_id',
                'shop_floor_warehouse_id',
                'backflush_employee_id',
                'parent_work_center_id',
                'costing_work_center_id',
                'operation_rate_id',
                'production_department_id',
            ],
            self::SCENARIO_UPDATE => [
                'description',
                'type',
                'blocked',
                'wait_time',
                'move_time',
                'queue_time',
                'time_unit_id',
                'shop_floor_warehouse_id',
                'backflush_employee_id',
                'parent_work_center_id',
                'costing_work_center_id',
                'operation_rate_id',
                'production_department_id',
            ],
        ];

        return $fillable[$this->scenario];
    }

    /**
     * @return BelongsTo
     */
    public function timeUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * @return BelongsTo
     */
    public function shopFloorWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    // @todo: define backflush_employee_id relation

    /**
     * @return BelongsTo
     */
    public function parentWorkCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class);
    }

    /**
     * @return BelongsTo
     */
    public function costingWorkCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class);
    }

    // @todo: define operation_rate_id relation

    // @todo: define production_department_id relation
}
