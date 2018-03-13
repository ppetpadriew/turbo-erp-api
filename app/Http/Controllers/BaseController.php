<?php

namespace App\Http\Controllers;

use App\Models\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class BaseController extends Controller
{
    /** @var string */
    protected $modelClass;

    public function __construct()
    {
        if (empty($this->getModelClass())) {
            throw new \Exception('Please define your model class in controller.');
        }

        $this->modelClass = $this->getModelClass();
    }

    public function index()
    {
        return $this->modelClass::all();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function get(int $id)
    {
        /** @var Model $record */
        $record = $this->modelClass::find($id);

        if (empty($record)) {
            throw new HttpException(404, 'Record not found.');
        }

        return $record;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $instance = new $this->modelClass;
        $validator = Validator::make(
            $request->all() + $instance->getAttributeDefaultValues(), $instance->getRules($this->modelClass::SCENARIO_CREATE)
        );

        if ($validator->fails()) {
            return new Response($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        return $this->modelClass::create($request->toArray())->fresh();
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

        $validator = Validator::make($request->all(), $record->getRules($this->modelClass::SCENARIO_UPDATE));

        if ($validator->fails()) {
            return new Response($validator->errors(), Response::HTTP_BAD_REQUEST);
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

    abstract public function getModelClass(): string;
}
