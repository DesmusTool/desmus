@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> Job T S P Audio Create </h1>
  
  </section>
   
  <div class="content">
       
    @include('adminlte-templates::common.errors')
       
    <div class="box box-primary">
           
      <div class="box-body">
               
        <div class="row">
                   
          {!! Form::model($jobTSPAudioCreate, ['route' => ['jobTSPAudioCreates.update', $jobTSPAudioCreate->id], 'method' => 'patch']) !!}

            @include('job_t_s_p_audio_creates.fields')

          {!! Form::close() !!}
               
        </div>
           
      </div>
       
    </div>
  
  </div>

@endsection