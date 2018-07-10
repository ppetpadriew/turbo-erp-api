<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

/**
 * Class WarehouseOrder
 * @package App\Models
 *
 * @property int $id
 * @property string $order_origin
 * @property string $order
 * @property string $status
 * @property int $set
 * @property string $transaction_type
 * @property string $order_datetime
 * @property int $ship_from_business_partner_id
 * @property int $ship_from_warehouse_id
 * @property int $ship_from_work_center_id
 * @property int $ship_from_address_id
 * @property int $ship_to_business_partner_id
 * @property int $ship_to_warehouse_id
 * @property int $ship_to_work_center_id
 * @property int $ship_to_address_id
 * @property string $delivery_datetime
 * @property string $receipt_datetime
 * @property int $warehouse_order_type_id
 * @property boolean $blocked
 *
 * Relations
 *
 * @property BusinessPartner $ship_from_business_partner
 * @property Warehouse $ship_from_warehouse
 * @property WorkCenter $ship_from_work_center
 * @property Address $ship_from_address
 * @property BusinessPartner $ship_to_business_partner
 * @property Warehouse $ship_to_warehouse
 * @property WorkCenter $ship_to_work_center
 * @property Address $ship_to_address
 */
class WarehouseOrder extends Model
{
    const TABLE = 'warehouse_order';

    const ORDER_ORIGIN_SALES = 'Sales';
    const ORDER_ORIGIN_SALES_MANUAL = 'Sales (Manual)';
    const ORDER_ORIGIN_PURCHASE = 'Purchase';
    const ORDER_ORIGIN_PURCHASE_MANUAL = 'Purchase (Manual)';
    const ORDER_ORIGIN_PRODUCTION = 'Production';
    const ORDER_ORIGIN_PRODUCTION_MANUAL = 'Production (Manual)';
    const ORDER_ORIGIN_TRANSFER = 'Transfer';
    const ORDER_ORIGIN_TRANSFER_MANUAL = 'Transfer (Manual)';
    const ORDER_ORIGINS = [
        self::ORDER_ORIGIN_SALES,
        self::ORDER_ORIGIN_SALES_MANUAL,
        self::ORDER_ORIGIN_PURCHASE,
        self::ORDER_ORIGIN_PURCHASE_MANUAL,
        self::ORDER_ORIGIN_PRODUCTION,
        self::ORDER_ORIGIN_PRODUCTION_MANUAL,
        self::ORDER_ORIGIN_TRANSFER,
        self::ORDER_ORIGIN_TRANSFER_MANUAL,
    ];

    const TRANSACTION_TYPE_ISSUE = 'Issue';
    const TRANSACTION_TYPE_RECEIPT = 'Receipt';
    const TRANSACTION_TYPE_TRANSFER = 'Transfer';
    const TRANSACTION_TYPES = [
        self::TRANSACTION_TYPE_ISSUE,
        self::TRANSACTION_TYPE_RECEIPT,
        self::TRANSACTION_TYPE_TRANSFER,
    ];

    const STATUS_OPEN = 'Open';
    const STATUS_IN_PROCESS = 'In Process';
    const STATUS_RECEIPT_OPEN = 'Receipt Open';
    const STATUS_RECEIVED = 'Received';
    const STATUS_SHIPMENT_OPEN = 'Shipment Open';
    const STATUS_SHIPPED = 'Shipped';
    const STATUS_TRANSFERRED = 'Transferred';
    const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_IN_PROCESS,
        self::STATUS_RECEIPT_OPEN,
        self::STATUS_RECEIVED,
        self::STATUS_SHIPMENT_OPEN,
        self::STATUS_SHIPPED,
        self::STATUS_TRANSFERRED,
    ];

    const CREATED_AT = 'order_datetime';

    // Non database fields. Use only in the code level for calculation and perform some logic.

    /** @var int */
    public $first_free_number_id;


    public function getRules(string $scenario): array
    {
        // @todo: review rules
        $rules = [
            self::SCENARIO_CREATE => [
                'order_origin'         => ['required', Rule::in(self::ORDER_ORIGINS)],
                'transaction_type'     => ['required', Rule::in(self::TRANSACTION_TYPES)],
                'blocked'              => ['required', 'boolean'],
                // Non database fields
                'first_free_number_id' => ['required', 'exists:' . FirstFreeNumber::TABLE . ',id'],
            ],
            self::SCENARIO_UPDATE => [
                // @todo: fill rules for this scenario
            ],
        ];

        if ($this->isTransferType()) {
            $rules[self::SCENARIO_CREATE]['ship_from_warehouse_id'] = ['required', 'exists:' . Warehouse::TABLE . ',id'];
            $rules[self::SCENARIO_CREATE]['ship_to_warehouse_id'] = ['required', 'exists:' . Warehouse::TABLE . ',id'];
            throw new \Exception('Not yet implemented');
        }

        if ($this->isProductionType()) {
            if ($this->transaction_type === self::TRANSACTION_TYPE_ISSUE) {
                $rules[self::SCENARIO_CREATE]['ship_from_warehouse_id'] = ['required', 'exists:' . Warehouse::TABLE . ',id'];
                $rules[self::SCENARIO_CREATE]['ship_to_work_center_id'] = ['required', 'exists:' . WorkCenter::TABLE . ',id'];
            } elseif ($this->transaction_type === self::TRANSACTION_TYPE_RECEIPT) {
                $rules[self::SCENARIO_CREATE]['ship_from_work_center_id'] = ['required', 'exists:' . WorkCenter::TABLE . ',id'];
                $rules[self::SCENARIO_CREATE]['ship_to_warehouse_id'] = ['required', 'exists:' . Warehouse::TABLE . ',id'];
            } elseif ($this->transaction_type === self::TRANSACTION_TYPE_TRANSFER) {
                throw new \Exception('Not yet implemented');
            }
        }

        if ($this->isSalesType()) {
            if ($this->transaction_type === self::TRANSACTION_TYPE_ISSUE) {
                $rules[self::SCENARIO_CREATE]['ship_from_warehouse_id'] = ['required', 'exists:' . Warehouse::TABLE . ',id'];
                $rules[self::SCENARIO_CREATE]['ship_to_business_partner_id'] = ['required', 'exists:' . BusinessPartner::TABLE . ',id'];
            } elseif ($this->transaction_type === self::TRANSACTION_TYPE_RECEIPT) {
                $rules[self::SCENARIO_CREATE]['ship_from_business_partner_id'] = ['required', 'exists:' . BusinessPartner::TABLE . ',id'];
                $rules[self::SCENARIO_CREATE]['ship_to_warehouse_id'] = ['required', 'exists:' . Warehouse::TABLE . ',id'];
            } elseif ($this->transaction_type === self::TRANSACTION_TYPE_TRANSFER) {
                throw new \Exception('Not yet implemented');
            }
        }

        if ($this->isPurchaseType()) {
            if ($this->transaction_type === self::TRANSACTION_TYPE_ISSUE) {
                $rules[self::SCENARIO_CREATE]['ship_from_warehouse_id'] = ['required', 'exists:' . Warehouse::TABLE . ',id'];
                $rules[self::SCENARIO_CREATE]['ship_to_business_partner_id'] = ['required', 'exists:' . BusinessPartner::TABLE . ',id'];
            } elseif ($this->transaction_type === self::TRANSACTION_TYPE_RECEIPT) {
                $rules[self::SCENARIO_CREATE]['ship_from_business_partner_id'] = ['required', 'exists:' . BusinessPartner::TABLE . ',id'];
                $rules[self::SCENARIO_CREATE]['ship_to_warehouse_id'] = ['required', 'exists:' . Warehouse::TABLE . ',id'];
            } elseif ($this->transaction_type === self::TRANSACTION_TYPE_TRANSFER) {
                throw new \Exception('Not yet implemented');
            }
        }

        return $scenario
            ? $rules[$scenario]
            : $rules;
    }

    public function getFillable()
    {
        $fillable = [
            self::SCENARIO_CREATE => [
                'order_origin',
                'transaction_type',
                'blocked',
            ],
            self::SCENARIO_UPDATE => [
                // @todo: fill fillable fields for this scenario
            ],
        ];

        return $fillable[$this->scenario];
    }

    public function getAttributeDefaultValues(): array
    {
        return [
            'blocked' => false,
        ];
    }

    /**
     * @return bool
     */
    public function isSalesType(): bool
    {
        return in_array($this->order_origin, [self::ORDER_ORIGIN_SALES, self::ORDER_ORIGIN_SALES_MANUAL]);
    }

    /**
     * @return bool
     */
    public function isPurchaseType(): bool
    {
        return in_array($this->order_origin, [self::ORDER_ORIGIN_PURCHASE, self::ORDER_ORIGIN_PURCHASE_MANUAL]);
    }

    /**
     * @return bool
     */
    public function isProductionType(): bool
    {
        return in_array($this->order_origin, [self::ORDER_ORIGIN_PRODUCTION, self::ORDER_ORIGIN_PRODUCTION_MANUAL]);
    }

    /**
     * @return bool
     */
    public function isTransferType(): bool
    {
        return in_array($this->order_origin, [self::ORDER_ORIGIN_TRANSFER, self::ORDER_ORIGIN_TRANSFER_MANUAL]);
    }

    // Relations

    public function shipFromBusinessPartner(): BelongsTo
    {
        return $this->belongsTo(BusinessPartner::class);
    }

    public function shipFromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function shipFromWorkCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class);
    }

    public function shipFromAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function shipToBusinessPartner(): BelongsTo
    {
        return $this->belongsTo(BusinessPartner::class);
    }

    public function shipToWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function shipToWorkCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class);
    }

    public function shipToAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
