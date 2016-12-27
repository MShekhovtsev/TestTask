@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">

                    {!! Form::open(['route' => 'login', 'class' => 'form-horizontal']) !!}

                    {!! Form::group('text', 'login', 'Login') !!}
                    {!! Form::group('password', 'password', 'Password') !!}
                    {!! Form::group('submit', 'Login') !!}

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
