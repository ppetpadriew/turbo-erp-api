<?php

namespace App\Constants;

class ResponseStatus
{
    const SUCCESS = 'success';
    const ERROR = 'error';
    const FAIL = 'fail';

    const ALL = [
        self::SUCCESS,
        self::ERROR,
        self::FAIL,
    ];
}
