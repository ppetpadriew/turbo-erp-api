<?php

namespace App\Models;


/**
 * Class FirstFreeNumber
 * @package App\Models
 *
 * @property int $id
 * @property string $series
 * @property string $description
 * @property int $length
 * @property int $last_used_number
 */
class FirstFreeNumber extends Model
{
    const TABLE = 'first_free_number';

    public function getRules(): array
    {
        return [
            ['required', ['series']],
            ['max:30', ['description']],
            ['max:8', ['series']],
            ['max:9', ['length']],
            ['min:1', ['length']],
            ['numeric', ['first_free_number', 'length']],
        ];
    }

    public function getAttributeDefaultValues(): array
    {
        return [
            'length'           => 9,
            'last_used_number' => 0,
        ];
    }

    /**
     * @param int $n
     * @return FirstFreeNumber
     */
    public function increaseLastUsedNumber(int $n = 1): FirstFreeNumber
    {
        $this->last_used_number += $n;

        return $this;
    }

    /**
     * @return string
     */
    public function getRunning(): string
    {
        $newNum = $this->last_used_number + 1;
        $numOfZero = $this->length - (strlen($this->series) + strlen($newNum));

        $zero = $numOfZero > 0 ?
            str_repeat('0', $numOfZero)
            : '';

        return "{$this->series}{$zero}{$newNum}";
    }
}
