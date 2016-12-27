@extends('layouts.app')
@section('content')

    <div class="modal fade" id="eventForm" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                {!! Form::open(['route' => ['calendar.store'], 'class' => 'form-horizontal']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>New event</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        {!! Form::label('title', 'Title', ['class' => 'control-label col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('title', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('location', 'Location', ['class' => 'control-label col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('location', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('start', 'Starts at', ['class' => 'control-label col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('start', null, ['class' => 'form-control datetimepicker']) !!}
                        </div>
                    </div>

                    {!! Form::hidden('allDay', 0) !!}
                    <div class="form-group">
                        {!! Form::label('allDay', 'All day', ['class' => 'control-label col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::checkbox('allDay', 1, null, ['class' => 'switch']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('end', 'Ends at', ['class' => 'control-label col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('end', null, ['class' => 'form-control datetimepicker']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('repeat', 'Repeat', ['class' => 'control-label col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::select('repeat', ['Never', 'Daily', 'Weekly', 'Monthly', 'Annual'], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default btn-default"><span class="glyphicon glyphicon-save"></span> Save</button>
                    <button type="button" data-dismiss="modal" class="btn btn-default btn-default"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-heading">
                        <button data-toggle="modal" data-target="#eventForm" class="btn btn-primary">Add Event</button>
                    </div>
                    <div class="panel-content">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            var date = new Date();

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'agendaDay,agendaWeek,month,listYear',
                    defaultView: 'agendaDay',
                    editable: true
                },
                selectable: true,
                editable: true,
                navLinks: true,
                events: function (start, end, timezone, callback) {
                    $.ajax({
                        url: '/calendar',
                        data: {
                            start: start.format(),
                            end: end.format()
                        },
                        success: function(events) {
                            callback(events);
                        }
                    });
                },
                eventRender: function (event, element) {
                    if(event.excluded){
                        element.css('background-color', 'red');
                        element.css('border-color', 'red');
                        element.find(".fc-content").append('(canceled)');
                    } else if(event.start.valueOf() < date.getTime()){
                        element.css('background-color', '#ccc');
                        element.css('border-color', '#ccc');
                        element.find(".fc-content").append('(past)');
                    }
                    element.find(".fc-content").append('<a data-action="deleteEvent"  data-id="'+(event.id || event.parent_id)+'" data-start="'+event.start.format('YYYY-MM-DD HH:mm:ss')+'" class="btn btn-xs btn-danger glyphicon glyphicon-remove pull-right" data-toggle="tooltip" title="delete"></a>');
                    element.find(".fc-content").append('<a data-action="editEvent"  data-id="'+(event.id || event.parent_id)+'" class="btn btn-xs btn-success glyphicon glyphicon-edit pull-right" data-toggle="tooltip" title="edit"></a>');
                },
                viewRender: function (view) {
                    $(".fc-day-top").prepend('<a data-action="addEvent" class="btn btn-xs btn-success pull-left"><i class="glyphicon glyphicon-plus"></i> Add event</a>');
                },



            });


            $('#calendar').fullCalendar( 'addEventSource', function(start, end, timezone, callback) {
                $.ajax({
                    url: '/calendar/repeated',
                    data: {
                        start_time: start.format(),
                        end_time: end.format()
                    },
                    success: function(events) {
                        callback( events );
                    }
                });
            });

            $('[data-target="#eventForm"]').click(function (e) {
                $("#eventForm form").attr('action', 'calendar');
                $("#eventForm input[name=_method]").remove();
                $("#title, #location, #start, #end").val('');
                $("#repeat").val(0);
            });

            $(document).on('click', '[data-action=addEvent]', function (e) {
                e.preventDefault();

                $('[data-target="#eventForm"]').click();

                date = $(this).parent().data('date');

                time = new Date().toLocaleTimeString([], {hour12:false});

                start = date + ' ' + time;
                end = date + ' ' + '23:59:59';

                $("#start").val(start);
                $("#end").val(end);
            });

            $(document).on('click', '[data-action=deleteEvent]', function (e) {
                e.preventDefault();

                var event_id = $(this).data('id');
                var start = $(this).data('start');

                $.ajax({
                    url: '/calendar/' + event_id,
                    type: 'get',
                    success: function (event) {

                            swal({
                                    title: "Are you sure?",
                                    text: "You will not be able to recover this event!",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "Yes, delete it!",
                                    closeOnConfirm: false
                                }, function(result){
                                    if(result){
                                        swal("Deleting...");

                                        if(event.repeat && start == event.start){
                                            $.ajax({
                                                url: '/calendar/' + event_id,
                                                type: 'delete',
                                                success: function (data) {
                                                    $('#calendar').fullCalendar('refetchEvents');
                                                    swal('deleted');
                                                }
                                            });
                                        } else {
                                            swal({
                                                title: "Delete all events?",
                                                text: "You will not be able to recover this events!",
                                                type: "warning",
                                                showCancelButton: true,
                                                confirmButtonColor: "#DD6B55",
                                                cancelButtonText: "No, only this!",
                                                confirmButtonText: "Yes, delete all!",
                                                closeOnConfirm: false
                                            }, function(result){
                                                if(result){
                                                    $.ajax({
                                                        url: '/calendar/' + event_id,
                                                        type: 'delete',
                                                        success: function (data) {
                                                            $('#calendar').fullCalendar('refetchEvents');
                                                            swal('deleted');
                                                        }
                                                    });
                                                } else {
                                                    $.ajax({
                                                        url: '/calendar/exclude',
                                                        type: 'post',
                                                        data: {
                                                            event_id: event_id,
                                                            start: start
                                                        },
                                                        success: function (data) {
                                                            $('#calendar').fullCalendar('refetchEvents');
                                                            swal('deleted');
                                                        }
                                                    });
                                                }

                                            });

                                        }
                                    }
                                }
                            );


                    }
                });




            });


            $(document).on('click', '[data-action=editEvent]', function (e) {
                e.preventDefault();

                event_id = $(this).data('id');

                $.ajax({
                    url: '/calendar/' + event_id,
                    type: 'get',
                    success: function (event) {

                        $('[data-target="#eventForm"]').click();

                        $("#title")     .val(event.title);
                        $("#location")  .val(event.location);
                        $("#start")     .val(event.start);
                        $("#end")       .val(event.end);
                        $("#repeat")    .val(event.repeat);
                        $('#allDay').bootstrapSwitch('state', event.allDay || false);

                        $("#eventForm form").attr('action', 'calendar/' + event_id);
                        $("#eventForm form").prepend('<input type="hidden" name="_method" value="put">');


                    }
                });

            });

            $("#allDay").on('switchChange.bootstrapSwitch', function (e,state) {
                if(state === true){
                    $('#end').val($('#start').val().substr(0, $('#start').val().length - 8) + '23:59:59');
                }
            })
        });

    </script>
@endsection
