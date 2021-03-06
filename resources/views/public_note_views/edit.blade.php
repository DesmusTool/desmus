@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> Public Note View </h1>
   
  </section>
   
  <div class="content">
       
    @include('adminlte-templates::common.errors')
       
    <div class="box box-primary">
           
      <div class="box-body">
               
        <div class="row">
                   
          {!! Form::model($publicNoteView, ['route' => ['publicNoteViews.update', $publicNoteView->id], 'method' => 'patch']) !!}

            @include('public_note_views.fields')

          {!! Form::close() !!}
               
        </div>
           
      </div>
       
    </div>
   
  </div>

@endsection