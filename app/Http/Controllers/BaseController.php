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
        /** @var Model $instance */
        $instance = new $this->modelClass($request->all());

        if (!$instance->save()) {
            return new Response($instance->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        return $instance->fresh();
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

        $record->fill($request->all());
        if (!$record->save()) {
            return new Response($record->getErrors(), Response::HTTP_BAD_REQUEST);
        }

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
