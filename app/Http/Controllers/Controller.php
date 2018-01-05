<?php

namespace App\Http\Controllers;

use App\Models\Model;
use Illuminate\Http\Request;
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

        $record->fill($request->toArray());

        $record->save();

        return $record;
    }

    /**
     * @param int $id
     * @return Model
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
