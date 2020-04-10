@extends('layouts.app')

@section('scripts')

  <script>
    
    $('#public_video_comment').on('submit', function() {
      
      var public_video_comment = document.getElementById("public_video_comment_content").value;
      
      if(public_video_comment.length >= 1000)
      {
        alert("Invalid character number for the file comment.");
        return false;
      }
      
      if(public_video_comment == '')
      {
        alert("You need to complete the field to post a comment.");
        return false;
      }
      
      if(public_video_comment != '')
      {
        return true;
      }
      
      return false;
      
    });
    
  </script>
  
  @foreach($publicVideoComments as $publicVideoComment)
              
    @if($publicVideo -> id == $publicVideoComment -> public_video_id)
        
      <script>
                                              
        $('#public_video_comment_response_{!! $publicVideoComment -> id !!}').on('submit', function() {
                                                  
          var public_video_comment_response = document.getElementById("public_video_comment_response_content_{!! $publicVideoComment -> id !!}").value;
                                                  
          if(public_video_comment_response.length >= 1000)
          {
            alert("Invalid character number for the video comment response.");
            return false;
          }
                                                  
          if(public_video_comment_response == '')
          {
            alert("You need to complete the field to post a comment response.");
            return false;
          }
                                                  
          if(public_video_comment_response != '')
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
    
    <h1> {!! $publicVideo->name !!} </h1>
    
  </section>
    
  <div class="content">
    
    <div class="box box-primary">
      
      <div class="box-body">
        
        <div class="row">
          
          @include('public_videos.show_fields')
          <a href="{!! route('publicProfile.index') !!}" class="btn btn-default" style="margin-left: 20px">Back</a>
          
        </div>
        
      </div>
      
    </div>
  
  </div>
  
  <aside class="control-sidebar control-sidebar-dark control-sidebar-close" style="background: rgba(0,0,0,0.8);">
  
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      
      <li class = "active">
        
        <a href="#public_video_views" data-toggle="tab">
        
          <i class="fa fa-eye"></i>
        
        </a>
        
      </li>
      
      <li>
        
        <a href="#public_video_updates" data-toggle="tab">
        
          <i class="fa fa-edit"></i>
        
        </a>
        
      </li>
    
    </ul>
    
    <div class="tab-content" style="padding: 20px;">
    
      <div class="tab-pane active" id="public_video_views">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Video Views </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($publicVideoViewsList as $publicVideoViewList)
            
             <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-eye bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $publicVideoViewList -> name !!} </h4>
                  <p> {!! $publicVideoViewList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
      <div class="tab-pane" id="public_video_updates">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Video Updates </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($publicVideoUpdatesList as $publicVideoUpdateList)
            
            <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-edit bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $publicVideoUpdateList -> name !!} </h4>
                  <p> {!! $publicVideoUpdateList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
    </div>
    
  </aside>
  
@endsection