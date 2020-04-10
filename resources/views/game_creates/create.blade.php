@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Game Creates
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'gameCreates.store']) !!}

                        @include('game_creates.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
