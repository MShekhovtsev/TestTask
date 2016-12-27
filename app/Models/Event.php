<?php

namespace App\Models;

class Event extends MasterModel
{

    protected $dates = ['start', 'end'];

    protected $fillable = [
        'title', 'location', 'start', 'end', 'allDay', 'repeat'
    ];

    public $validationRules = [
        'title' => 'required',
        'location' => 'required',
        'start' => 'required',
        'end' => 'required_without:allDay',
    ];

    protected $casts = [
        'exclude' => 'array'
    ];

    public function setEndAttribute($end){
        if(!$end){
            $this->attributes['end'] = $this->start;
        } else {
            $this->attributes['end'] = $end;
        }
    }

    public function logs(){
        return $this->hasMany(EventLog::class);
    }
}
