<?php

namespace App\Tests\Unit\Models;


use App\Models\FirstFreeNumber;
use App\Tests\Unit\Unit;

class FirstFreeNumberTest extends Unit
{
    public function testIncreaseLastUsedNumber()
    {
        $this->specify('It should return itself.', function () {
            $firstFreeNumber = new FirstFreeNumber;
            $result = $firstFreeNumber->increaseLastUsedNumber();

            verify($result)->isInstanceOf(FirstFreeNumber::class);
        });

        $this->specify('It should increase last used number by given number.', function ($n) {
            $firstFreeNumber = new FirstFreeNumber;
            $initialValue = 2;
            $firstFreeNumber->last_used_number = $initialValue;
            $firstFreeNumber->increaseLastUsedNumber($n);

            verify($firstFreeNumber->last_used_number)->equals($initialValue + $n);
        }, [
            'examples' => [
                ['n' => 4],
                ['n' => 20],
            ],
        ]);

        $this->specify('It should increase last used number by 1 (default).', function () {
            $firstFreeNumber = new FirstFreeNumber;
            $firstFreeNumber->last_used_number = 4;
            $firstFreeNumber->increaseLastUsedNumber();

            verify($firstFreeNumber->last_used_number)->equals(5);
        });
    }

    public function testGetRunning()
    {
        $this->specify('It should combine series with last used number regarding length.',
            function ($series, $length, $lastUsedNumber, $expected) {
                $firstFreeNumber = new FirstFreeNumber;
                $firstFreeNumber->series = $series;
                $firstFreeNumber->length = $length;
                $firstFreeNumber->last_used_number = $lastUsedNumber;

                $result = $firstFreeNumber->getRunning();

                verify($result)->equals($expected);
            }, [
                'examples' => [
                    [
                        'series'           => 'ORD',
                        'length'           => 9,
                        'last_used_number' => 0,
                        'expected'         => 'ORD000001',
                    ],
                    [
                        'series'           => 'WHR',
                        'length'           => 5,
                        'last_used_number' => 0,
                        'expected'         => 'WHR01',
                    ],
                    [
                        'series'           => 'AD',
                        'length'           => 6,
                        'last_used_number' => 3333,
                        'expected'         => 'AD3334',
                    ],
                    [
                        'series'           => 'AD',
                        'length'           => 6,
                        'last_used_number' => 9999,
                        'expected'         => 'AD10000',
                    ],
                ],
            ]);
    }
}
