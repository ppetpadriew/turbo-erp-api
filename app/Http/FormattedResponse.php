<?php

namespace App\Http;


use App\Constants\ResponseStatus;
use Illuminate\Http\Response;

class FormattedResponse extends Response
{
    /**
     * FormattedResponse constructor.
     * @param string $status
     * @param $payload
     */
    public function __construct(string $status, $payload)
    {
        if (!in_array($status, ResponseStatus::ALL)) {
            throw new \InvalidArgumentException("Status '{$status}' is not yet implemented.");
        }

        $content = ['status' => $status];

        if ($status === ResponseStatus::ERROR) {
            $content['message'] = $payload;
        } else {
            $content['data'] = $payload;
        }

        parent::__construct($content);
    }
}
