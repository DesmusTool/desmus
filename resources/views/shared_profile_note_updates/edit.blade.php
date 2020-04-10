@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> Shared Profile Note Update </h1>
  
  </section>
   
  <div class="content">
       
    @include('adminlte-templates::common.errors')
       
    <div class="box box-primary">
           
      <div class="box-body">
               
        <div class="row">
                   
          {!! Form::model($sharedProfileNoteUpdate, ['route' => ['sharedProfileNoteUpdates.update', $sharedProfileNoteUpdate->id], 'method' => 'patch']) !!}

            @include('shared_profile_note_updates.fields')

          {!! Form::close() !!}
               
        </div>
          
      </div>
    
    </div>
  
  </div>

@endsection