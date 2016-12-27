@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">

                    {!! Form::open(['route' => 'register', 'class' => 'form-horizontal']) !!}

                    {!! Form::group('text', 'name', 'Name') !!}
                    {!! Form::group('text', 'login', 'Login') !!}
                    {!! Form::group('password', 'password', 'Password') !!}
                    {!! Form::group('password', 'password_confirmation', 'Confirm password') !!}
                    {!! Form::group('submit', 'Submit') !!}

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
