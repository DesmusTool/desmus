@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Game Deletes
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($gameDeletes, ['route' => ['gameDeletes.update', $gameDeletes->id], 'method' => 'patch']) !!}

                        @include('game_deletes.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection