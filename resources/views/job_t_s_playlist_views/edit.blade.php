@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> Job T S Playlist View </h1>
   
  </section>
   
  <div class="content">
       
    @include('adminlte-templates::common.errors')
      
    <div class="box box-primary">
           
      <div class="box-body">
               
        <div class="row">
                   
          {!! Form::model($jobTSPlaylistView, ['route' => ['jobTSPlaylistViews.update', $jobTSPlaylistView->id], 'method' => 'patch']) !!}

            @include('job_t_s_playlist_views.fields')

          {!! Form::close() !!}
               
        </div>
           
      </div>
       
    </div>
  
  </div>

@endsection