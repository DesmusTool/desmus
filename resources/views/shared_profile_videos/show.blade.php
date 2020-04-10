@extends('layouts.app')

@section('scripts')

  <script>
    
    $('#shared_profile_video_comment').on('submit', function() {
      
      var shared_profile_video_comment = document.getElementById("shared_profile_video_comment_content").value;
      
      if(shared_profile_video_comment.length >= 1000)
      {
        alert("Invalid character number for the file comment.");
        return false;
      }
      
      if(shared_profile_video_comment == '')
      {
        alert("You need to complete the field to post a comment.");
        return false;
      }
      
      if(shared_profile_video_comment != '')
      {
        return true;
      }
      
      return false;
      
    });
    
  </script>
  
  @foreach($sharedProfileVideoComments as $sharedProfileVideoComment)
              
    @if($sharedProfileVideo -> id == $sharedProfileVideoComment -> s_p_v_id)
        
      <script>
                                              
        $('#shared_profile_video_comment_response_{!! $sharedProfileVideoComment -> id !!}').on('submit', function() {
                                                  
          var shared_profile_video_comment_response = document.getElementById("shared_profile_video_comment_response_content_{!! $sharedProfileVideoComment -> id !!}").value;
                                                  
          if(shared_profile_video_comment_response.length >= 1000)
          {
            alert("Invalid character number for the video comment response.");
            return false;
          }
                                                  
          if(shared_profile_video_comment_response == '')
          {
            alert("You need to complete the field to post a comment response.");
            return false;
          }
                                                  
          if(shared_profile_video_comment_response != '')
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
    
    <h1> {!! $sharedProfileVideo->name !!} </h1>
    
  </section>
    
  <div class="content">
    
    <div class="box box-primary">
      
      <div class="box-body">
        
        <div class="row">
          
          @include('shared_profile_videos.show_fields')
          <a href="{!! route('sharedProfile.index') !!}" class="btn btn-default" style="margin-left: 20px">Back</a>
          
        </div>
        
      </div>
      
    </div>
  
  </div>
  
  <aside class="control-sidebar control-sidebar-dark control-sidebar-close" style="background: rgba(0,0,0,0.8);">
  
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      
      <li class = "active">
        
        <a href="#shared_profile_video_views" data-toggle="tab">
        
          <i class="fa fa-eye"></i>
        
        </a>
        
      </li>
      
      <li>
        
        <a href="#shared_profile_video_updates" data-toggle="tab">
        
          <i class="fa fa-edit"></i>
        
        </a>
        
      </li>
    
    </ul>
    
    <div class="tab-content" style="padding: 20px;">
    
      <div class="tab-pane active" id="shared_profile_video_views">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Video Views </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($sharedProfileVideoViewsList as $sharedProfileVideoViewList)
            
             <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-eye bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $sharedProfileVideoViewList -> name !!} </h4>
                  <p> {!! $sharedProfileVideoViewList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
      <div class="tab-pane" id="shared_profile_video_updates">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Video Updates </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($sharedProfileVideoUpdatesList as $sharedProfileVideoUpdateList)
            
            <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-edit bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $sharedProfileVideoUpdateList -> name !!} </h4>
                  <p> {!! $sharedProfileVideoUpdateList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
    </div>
    
  </aside>
  
@endsection