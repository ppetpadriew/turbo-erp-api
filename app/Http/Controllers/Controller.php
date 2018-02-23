<?php

namespace App\Http\Controllers;

use App\Constants\ResponseStatus;
use App\Http\FormattedResponse;
use App\Models\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseController extends Controller
{
    /** @var string */
    protected $modelClass;

    public function index()
    {
        return $this->modelClass::all();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), $this->modelClass::$rules);

        if ($validator->fails()) {
            return new FormattedResponse(ResponseStatus::FAIL, $validator->errors());
        }

        return $this->modelClass::create($request->toArray());
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Model
     */
    public function update(int $id, Request $request)
    {
        /** @var Model $record */
        $record = $this->modelClass::find($id);

        if (empty($record)) {
            throw new HttpException(404, 'Record not found.');
        }

        $validator = Validator::make($request->all(), $this->modelClass::$rules);

        if ($validator->fails()) {
            return new FormattedResponse(ResponseStatus::FAIL, $validator->errors());
        }

        $record->fill($request->toArray());

        $record->save();

        return $record;
    }

    /**
     * @param int $id
     * @return Model
     * @throws \Exception
     */
    public function delete(int $id)
    {
        /** @var Model $record */
        $record = $this->modelClass::find($id);

        if (empty($record)) {
            throw new HttpException(404, 'Record not found.');
        }

        $record->delete();

        return $record;
    }
}
