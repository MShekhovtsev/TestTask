<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RestfulController extends Controller
{

    protected $model;
    protected $query;

    protected $filters = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->query = $model->newQuery();

        //Applying filters
        foreach(\Request::only(array_keys($this->filters)) as $key => $value){
            if($value !== '' && $value !== null){
                $method = $this->filters[$key];

                if($method == 'like'){
                    $this->query->where($key, 'like', '%'.$value.'%');
                } elseif ($method == 'null') {
                    if($value){
                        $this->query->whereNotNull($key);
                    } else {
                        $this->query->whereNull($key);
                    }
                } else {
                    $this->query->where($key, $method, $value);
                }
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        \Form::setRules($this->model->validationRules);

        $collection = $this->query->get();
        return $this->response(__FUNCTION__, $collection);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->form($this->model);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->request->add(['user_id' => \Auth::user()->id]);

        $this->validate($request, $this->model->validationRules);

        $object = $this->model->create($request->all());

        return $this->response(__FUNCTION__, $object);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($parsed = $this->parseMethod($id, true)){
            return $parsed;
        }

        $object = $this->model->find($id);

        return $this->response(__FUNCTION__, $object);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $object = $this->model->find($id);

        return $this->response(__FUNCTION__, $object);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if($parsed = $this->parseMethod($id, true)){
            return $parsed;
        }

        $object = $this->model->find($id);

        $object->update($request->all());

        return $this->response(__FUNCTION__, $object);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($parsed = $this->parseMethod($id, true)){
            return $parsed;
        }

        $object = $this->model->find($id);
        $object->delete();
        return $this->response(__FUNCTION__, $object);
    }

    public function response($method, $data){

        $child_method = '_' . $method;
        if(method_exists($this, $child_method) && !\Request::ajax()){
            return $this->$child_method($data);
        }

        return $data;
    }

    public function form($model){
        $view = 'forms.' . class_basename($model);
        if(\View::exists($view)){
            return \View::make($view);
        }
    }

    public function parseMethod($method, $local = false){

        $request_method = \Request::method();

        $method = $request_method . ucfirst($method);

        if(method_exists($this, $method)){
            return $this->$method();
        }
    }
}