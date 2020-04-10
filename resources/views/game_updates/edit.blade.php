@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Game Updates
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($gameUpdates, ['route' => ['gameUpdates.update', $gameUpdates->id], 'method' => 'patch']) !!}

                        @include('game_updates.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection