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

    protected function testList(ApiTester $I, string $table)
    {
        $rows = $this->db->table($table)->get()->toArray();

        $I->sendGET($this->getBaseUrl());

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $rows,
        ]);
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param int $id
     */
    protected function testGet(ApiTester $I, string $table, int $id)
    {
        $row = $I->grabRecord($table, ['id' => $id]);

        $I->sendGET("{$this->getBaseUrl()}/{$id}");

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $row,
        ]);
    }

    protected function testCreate(ApiTester $I, string $table, array $fields, array $defaultFields = [])
    {
        $numOfRecord = $I->grabNumRecords($table);

        $I->sendPOST($this->getBaseUrl(), $fields);

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => array_merge($fields, $defaultFields),
        ]);
        $I->seeNumRecords($numOfRecord + 1, $table);
        $response = $I->grabJsonResponse();
        $row = $I->grabRecord($table, ['id' => $response['data']['id']]);
        verify($response['data'])->equals($row);
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param array $fields
     */
    protected function testCreateWithTooLong(ApiTester $I, string $table, array $fields)
    {
        $numOfRecord = $I->grabNumRecords($table);
        $data = array_fill_keys(array_keys($fields), str_repeat('super long', max($fields)));

        $I->sendPOST($this->getBaseUrl(), $data);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach ($fields as $field => $max) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::MAX_STRING, $field, $max));
        }
        $I->seeNumRecords($numOfRecord, $table);
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param array $fields
     */
    protected function testCreateWithAlreadyExist(ApiTester $I, string $table, array $fields)
    {
        $numOfRecord = $I->grabNumRecords($table);

        $I->sendPOST($this->getBaseUrl(), $fields);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach ($fields as $field => $value) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::UNIQUE, str_replace('_', ' ', $field)));
        }
        $I->seeNumRecords($numOfRecord, $table);
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param array $fields
     */
    protected function testCreateWithMissingRequiredFields(ApiTester $I, string $table, array $fields)
    {
        $numOfRecord = $I->grabNumRecords($table);

        $I->sendPOST($this->getBaseUrl(), []);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach ($fields as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::REQUIRED, str_replace('_', ' ', $field)));
        }
        $I->seeNumRecords($numOfRecord, $table);
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param array $fields
     */
    protected function testCreateWithNonExistenceReference(ApiTester $I, string $table, array $fields)
    {
        $numOfRecord = $I->grabNumRecords($table);
        $data = array_fill_keys($fields, 0);

        $I->sendPOST($this->getBaseUrl(), $data);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach ($fields as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::EXIST, str_replace('_', ' ', $field)));
        }
        $I->seeNumRecords($numOfRecord, $table);
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param array $fields
     * @param $expected
     */
    protected function testCreateWithInvalidFieldTypes(ApiTester $I, string $table, array $fields, array $messages)
    {
        $numOfRecord = $I->grabNumRecords($table);

        $I->sendPOST($this->getBaseUrl(), $fields);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach ($messages as $field => $message) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf($message, str_replace('_', ' ', $field)));
        }
        $I->seeNumRecords($numOfRecord, $table);
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param array $fields
     * @param int $id
     */
    protected function testUpdate(ApiTester $I, string $table, array $fields, int $id)
    {
        $before = $I->grabRecord($table, ['id' => $id]);

        $I->sendPUT("{$this->getBaseUrl()}/{$id}", $fields);

        $I->seeResponseCodeIs(200);
        $after = $I->grabRecord($table, ['id' => $id]);
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $after,
        ]);
        verify($before)->notEquals($after);
        foreach ($fields as $field => $value) {
            verify($after[$field])->equals($value);
        }
    }

    /**
     * Common verification steps when updating resource with missing required fields data
     *
     * @param ApiTester $I
     * @param string $table
     * @param array $fields
     * @param int $id
     */
    protected function testUpdateWithMissingRequiredFields(ApiTester $I, string $table, array $fields, int $id)
    {
        $before = $I->grabRecord($table, ['id' => $id]);

        $I->sendPUT("{$this->getBaseUrl()}/{$id}", []);

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

    /**
     * @param ApiTester $I
     * @param string $table
     * @param array $updateData
     * @param array $fields
     * @param int $id
     */
    protected function testUpdateWithInvalidFieldTypes(ApiTester $I, string $table, array $updateData, array $fields, int $id)
    {
        $before = $I->grabRecord($table, ['id' => $id]);

        $I->sendPUT("{$this->getBaseUrl()}/{$id}", $updateData + $before);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach ($fields as $field => $message) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf($message, str_replace('_', ' ', $field)));
        }
        $after = $I->grabRecord($table, ['id' => $id]);
        verify($before)->equals($after);
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param array $fields
     * @param int $id
     */
    protected function testUpdateWithNonExistenceReference(ApiTester $I, string $table, array $fields, int $id)
    {
        $before = $I->grabRecord($table, ['id' => $id]);
        $updateData = array_fill_keys($fields, 0);

        $I->sendPUT("{$this->getBaseUrl()}/{$id}", $updateData + $before);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach ($fields as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::EXIST, str_replace('_', ' ', $field)));
        }
        $after = $I->grabRecord($table, ['id' => $id]);
        verify($before)->equals($after);
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param array $fields
     * @param int $id
     */
    protected function testUpdateWithTooLong(ApiTester $I, string $table, array $fields, int $id)
    {
        $before = $I->grabRecord($table, ['id' => $id]);
        $updateData = array_fill_keys(array_keys($fields), str_repeat('super long', max($fields)));

        $I->sendPUT("{$this->getBaseUrl()}/{$id}", $updateData + $before);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach ($fields as $field => $max) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::MAX_STRING, $field, $max));
        }
        $after = $I->grabRecord($table, ['id' => $id]);
        verify($before)->equals($after);
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param array $fields
     * @param int $id
     */
    protected function testUpdateWithNullableFields(ApiTester $I, string $table, array $fields, int $id)
    {
        $before = $I->grabRecord($table, ['id' => $id]);
        $updateData = array_fill_keys($fields, null);

        $I->sendPUT("{$this->getBaseUrl()}/{$id}", $updateData + $before);

        $I->seeResponseCodeIs(200);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('success');
        $after = $I->grabRecord($table, ['id' => $id]);
        verify($before)->notEquals($after);
        foreach ($fields as $field) {
            verify($response['data'][$field])->null();
            verify($after[$field])->null();
        }
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param int $id
     */
    protected function testUpdateWithoutChangingUniqueFields(ApiTester $I, string $table, int $id)
    {
        $before = $I->grabRecord($table, ['id' => $id]);

        $I->sendPUT("{$this->getBaseUrl()}/{$id}", $before);

        $I->seeResponseCodeIs(200);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('success');
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param array $fields
     * @param int $id
     */
    protected function testUpdateWithUnfillableFields(ApiTester $I, string $table, array $fields, int $id)
    {
        $before = $I->grabRecord($table, ['id' => $id]);

        $I->sendPUT("{$this->getBaseUrl()}/{$id}", $fields + $before);

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $before,
        ]);
        $after = $I->grabRecord($table, ['id' => $id]);
        verify($before)->equals($after);
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param array $fields
     * @param int $id
     */
    protected function testUpdateWithAlreadyExist(ApiTester $I, string $table, array $fields, int $id)
    {
        $before = $I->grabRecord($table, ['id' => $id]);

        $I->sendPUT("{$this->getBaseUrl()}/1", $fields + $before);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach ($fields as $field => $value) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::UNIQUE, str_replace('_', ' ', $field)));
        }
        $after = $I->grabRecord($table, ['id' => $id]);
        verify($before)->equals($after);
    }

    /**
     * @param ApiTester $I
     * @param string $table
     * @param int $id
     */
    protected function testDelete(ApiTester $I, string $table, int $id)
    {
        $before = $I->grabRecord($table, ['id' => $id]);

        $I->sendDELETE("{$this->getBaseUrl()}/{$id}");

        $I->seeResponseCodeIs(200);
        $I->dontSeeInDatabase($table, $before);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $before,
        ]);
    }
}
