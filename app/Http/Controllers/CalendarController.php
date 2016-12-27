<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CalendarController extends RestfulController
{

    protected $filters = [
        'start' => '>=',
        'end'   => '<='
    ];

    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    public function _index($data)
    {
        return view('calendar', ['events' => $data]);
    }

    public function _store($data){
        return redirect('calendar');
    }

    public function _update($data){
        return back();
    }

    public function getRepeated(){

        $start = new Carbon(\Request::get('start_time'));
        $end = new Carbon(\Request::get('end_time'));

        $events = [];

        $this->query->where('repeat', '!=', 0);
        $this->query->where('start', '>=', $start);

        $repeated = $this->query->get();

        foreach ($repeated as $event) {
            $rules = $event->repeat;

            $new_event = $event->replicate();
            $new_event->parent_id = $event->id;

            while ($new_event->start->diff($end)->invert !== 1){
                switch ($rules){
                    case 1:
                        $new_event->start = Carbon::parse($new_event->start)->addDay();
                        $new_event->end = Carbon::parse($new_event->end)->addDay();
                        break;
                    case 2:
                        $new_event->start = Carbon::parse($new_event->start)->addWeek();
                        $new_event->end = Carbon::parse($new_event->end)->addWeek();
                        break;
                    case 3:
                        $new_event->start = Carbon::parse($new_event->start)->addMonth();
                        $new_event->end = Carbon::parse($new_event->end)->addMonth();
                        break;
                    case 4:
                        $new_event->start = Carbon::parse($new_event->start)->addYear();
                        $new_event->end = Carbon::parse($new_event->end)->addYear();
                        break;

                }

                foreach ($event->exclude ?: [] as $item) {
                    $new_event->excluded = false;
                    if(Carbon::parse($item)->isSameDay($new_event->start)){
                        $new_event->excluded = true;
                    }
                }

                $events[] = $new_event;

                $new_event = $new_event->replicate();
            }
        }

        return $events;


    }

    public function postExclude(){
        $id = \Request::get('event_id');
        $start = \Request::get('start');

        $event = $this->model->find($id);

        if($event){
            $exclude = $event->exclude ?: [];
            $exclude[] = Carbon::parse($start)->format('Y-m-d');
            $event->exclude = $exclude;
            $event->save();
        }

        return $event;
    }




}