<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MasterModel extends Model
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    function route($type){
        return route($type . '.rest', $this->classname);
    }

}