@extends('layouts.app')

@section('scripts')

  <script>
    
    $('#shared_profile_audio_comment').on('submit', function() {
      
      var shared_profile_audio_comment = document.getElementById("shared_profile_audio_comment_content").value;
      
      if(shared_profile_audio_comment.length >= 1000)
      {
        alert("Invalid character number for the file comment.");
        return false;
      }
      
      if(shared_profile_audio_comment == '')
      {
        alert("You need to complete the field to post a comment.");
        return false;
      }
      
      if(shared_profile_audio_comment != '')
      {
        return true;
      }
      
      return false;
      
    });
    
  </script>
  
  @foreach($sharedProfileAudioComments as $sharedProfileAudioComment)
              
    @if($sharedProfileAudio -> id == $sharedProfileAudioComment -> s_p_a_id)
        
      <script>
                                              
        $('#shared_profile_audio_comment_response_{!! $sharedProfileAudioComment -> id !!}').on('submit', function() {
                                                  
          var shared_profile_audio_comment_response = document.getElementById("shared_profile_audio_comment_response_content_{!! $sharedProfileAudioComment -> id !!}").value;
                                                  
          if(shared_profile_audio_comment_response.length >= 1000)
          {
            alert("Invalid character number for the audio comment response.");
            return false;
          }
                                                  
          if(shared_profile_audio_comment_response == '')
          {
            alert("You need to complete the field to post a comment response.");
            return false;
          }
                                                  
          if(shared_profile_audio_comment_response != '')
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
    
    <h1> {!! $sharedProfileAudio->name !!} </h1>
    
  </section>
    
  <div class="content">
    
    <div class="box box-primary">
      
      <div class="box-body">
        
        <div class="row">
          
          @include('shared_profile_audios.show_fields')
          <a href="{!! route('sharedProfile.index') !!}" class="btn btn-default" style="margin-left: 20px">Back</a>
          
        </div>
        
      </div>
      
    </div>
  
  </div>
  
  <aside class="control-sidebar control-sidebar-dark control-sidebar-close" style="background: rgba(0,0,0,0.8);">
  
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      
      <li class = "active">
        
        <a href="#shared_profile_audio_views" data-toggle="tab">
        
          <i class="fa fa-eye"></i>
        
        </a>
        
      </li>
      
      <li>
        
        <a href="#shared_profile_audio_updates" data-toggle="tab">
        
          <i class="fa fa-edit"></i>
        
        </a>
        
      </li>
    
    </ul>
    
    <div class="tab-content" style="padding: 20px;">
    
      <div class="tab-pane active" id="shared_profile_audio_views">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Audio Views </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($sharedProfileAudioViewsList as $sharedProfileAudioViewList)
            
             <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-eye bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $sharedProfileAudioViewList -> name !!} </h4>
                  <p> {!! $sharedProfileAudioViewList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
      <div class="tab-pane" id="shared_profile_audio_updates">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Audio Updates </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($sharedProfileAudioUpdatesList as $sharedProfileAudioUpdateList)
            
            <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-edit bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $sharedProfileAudioUpdateList -> name !!} </h4>
                  <p> {!! $sharedProfileAudioUpdateList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
    </div>
    
  </aside>
  
@endsection