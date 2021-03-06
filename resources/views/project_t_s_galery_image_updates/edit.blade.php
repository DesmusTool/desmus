@extends('layouts.app')

@section('content')

  <section class="content-header">
        
    <h1> Project T S Galery Image Update </h1>
  
  </section>
  
  <div class="content">
       
    @include('adminlte-templates::common.errors')
       
    <div class="box box-primary">
           
      <div class="box-body">
               
        <div class="row">
                   
          {!! Form::model($projectTSGaleryImageUpdate, ['route' => ['projectTSGaleryImageUpdates.update', $projectTSGaleryImageUpdate->id], 'method' => 'patch']) !!}

            @include('project_t_s_galery_image_updates.fields')

          {!! Form::close() !!}
               
        </div>
           
      </div>
       
    </div>
   
  </div>
  
@endsection