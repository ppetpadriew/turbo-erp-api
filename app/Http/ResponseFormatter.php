<?php

namespace App\Http;


use App\Constants\ResponseStatus;
use Illuminate\Http\Response;

class ResponseFormatter
{

    public function format(Response $response): Response
    {
        $original = (array)$response->getOriginalContent();

        if (isset($original['status'], $original['data']) || isset($original['status'], $original['message'])) {
            return $response;
        }

        if ($response->status() === Response::HTTP_OK) {
            $response->setContent([
                'status' => ResponseStatus::SUCCESS,
                'data'   => $response->getOriginalContent(),
            ]);

            return $response;
        }

        if ($response->status() === Response::HTTP_BAD_REQUEST) {
            $response->setContent([
                'status' => ResponseStatus::FAIL,
                'data'   => $response->getOriginalContent(),
            ]);

            return $response;
        }

        $message = $response->exception->getMessage();

        if (empty($message) && env('DEBUG') == 'true') {
            $message = $response->original;
        }

        if (empty($message) && env('DEBUG') == 'false') {
            $message = 'Internal Server Error.';
        }

        $response->setContent([
            'status'  => ResponseStatus::ERROR,
            'message' => $message,
        ]);

        return $response;
    }
}
