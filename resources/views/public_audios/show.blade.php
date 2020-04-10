@extends('layouts.app')

@section('scripts')

  <script>
    
    $('#public_audio_comment').on('submit', function() {
      
      var public_audio_comment = document.getElementById("public_audio_comment_content").value;
      
      if(public_audio_comment.length >= 1000)
      {
        alert("Invalid character number for the file comment.");
        return false;
      }
      
      if(public_audio_comment == '')
      {
        alert("You need to complete the field to post a comment.");
        return false;
      }
      
      if(public_audio_comment != '')
      {
        return true;
      }
      
      return false;
      
    });
    
  </script>
  
  @foreach($publicAudioComments as $publicAudioComment)
              
    @if($publicAudio -> id == $publicAudioComment -> public_audio_id)
        
      <script>
                                              
        $('#public_audio_comment_response_{!! $publicAudioComment -> id !!}').on('submit', function() {
                                                  
          var public_audio_comment_response = document.getElementById("public_audio_comment_response_content_{!! $publicAudioComment -> id !!}").value;
                                                  
          if(public_audio_comment_response.length >= 1000)
          {
            alert("Invalid character number for the audio comment response.");
            return false;
          }
                                                  
          if(public_audio_comment_response == '')
          {
            alert("You need to complete the field to post a comment response.");
            return false;
          }
                                                  
          if(public_audio_comment_response != '')
          {
            return true;
          }
                                                  
          return false;
                                                  
        });
                                                
      </script>
          
    @endif
        
  @endforeach
    
@endsection

@section('content')
    
  <section class="content-header">
    
    <h1> {!! $publicAudio->name !!} </h1>
    
  </section>
    
  <div class="content">
    
    <div class="box box-primary">
      
      <div class="box-body">
        
        <div class="row">
          
          @include('public_audios.show_fields')
          <a href="{!! route('publicProfile.index') !!}" class="btn btn-default" style="margin-left: 20px">Back</a>
          
        </div>
        
      </div>
      
    </div>
  
  </div>
  
  <aside class="control-sidebar control-sidebar-dark control-sidebar-close" style="background: rgba(0,0,0,0.8);">
  
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      
      <li class = "active">
        
        <a href="#public_audio_views" data-toggle="tab">
        
          <i class="fa fa-eye"></i>
        
        </a>
        
      </li>
      
      <li>
        
        <a href="#public_audio_updates" data-toggle="tab">
        
          <i class="fa fa-edit"></i>
        
        </a>
        
      </li>
    
    </ul>
    
    <div class="tab-content" style="padding: 20px;">
    
      <div class="tab-pane active" id="public_audio_views">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Audio Views </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($publicAudioViewsList as $publicAudioViewList)
            
             <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-eye bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $publicAudioViewList -> name !!} </h4>
                  <p> {!! $publicAudioViewList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
      <div class="tab-pane" id="public_audio_updates">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Audio Updates </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($publicAudioUpdatesList as $publicAudioUpdateList)
            
            <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-edit bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $publicAudioUpdateList -> name !!} </h4>
                  <p> {!! $publicAudioUpdateList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
    </div>
    
  </aside>
  
@endsection