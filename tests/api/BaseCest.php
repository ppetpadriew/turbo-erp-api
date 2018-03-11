<?php

namespace App\Tests\Api;


use ApiTester;
use App\Constants\Service;
use App\Tests\ValidationMessage;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Facade;

abstract class BaseCest
{
    /** @var DatabaseManager */
    protected $db;

    abstract protected function getBaseUrl(): string;

    public function __construct()
    {
        $this->db = Facade::getFacadeApplication()->make(Service::DB);
    }

    /**
     * Common verification steps when updating resource with missing required fields data
     *
     * @param ApiTester $I
     * @param string $table
     * @param string $url
     * @param array $fields
     * @param int $id
     */
    protected function testUpdateMissingRequiredFields(ApiTester $I, string $table, string $url, array $fields, int $id)
    {
        $before = $I->grabRecord($table, ['id' => $id]);

        $I->sendPUT($url, []);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach ($fields as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::REQUIRED, str_replace('_', ' ', $field)));
        }
        $after = $I->grabRecord($table, ['id' => $id]);
        verify($before)->equals($after);
    }

    protected function testDelete(ApiTester $I, string $table, string $url, int $id)
    {
        $before = $I->grabRecord($table, ['id' => $id]);

        $I->sendDELETE($url);

        $I->seeResponseCodeIs(200);
        $I->dontSeeInDatabase($table, $before);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $before,
        ]);
    }
}
