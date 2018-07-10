<?php

namespace App\Tests;

/**
 * Class ValidationMessage
 * @package App\Tests
 *
 * For testing purpose, we keep the message in one place so that when we change
 * validation message we don't need to update all tests that use the messages.
 */
class ValidationMessage
{
    const UNIQUE = 'The %s has already been taken.';
    const MAX_STRING = 'The %s may not be greater than %s characters.';
    const MAX_NUMERIC = 'The %s may not be greater than %s.';
    const MIN_NUMERIC = 'The %s must be at least %s.';
    const REQUIRED = 'The %s field is required.';
    const EXIST = 'The selected %s is invalid.';
    const NUMERIC = 'The %s must be a number.';
    const BOOLEAN = 'The %s field must be true or false.';
    const IN = 'The selected %s is invalid.';
}
