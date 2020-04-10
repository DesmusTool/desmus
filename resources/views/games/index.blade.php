@extends('layouts.app')

@section('scripts')

  <script type="text/javascript">

    $('#gameSearch').on('keyup',function(){
 
      $value=$(this).val();
 
      $.ajax({
 
        type : 'get',
        url : '{{URL::to('GameSearch')}}',
        data:{'search':$value},
 
        success:function(data){
          $('tbody#t_game_search').html(data);
        }
 
      });

    })
 
  </script>

@endsection

@section('content')
    
  <section class="content-header">
        
    <h1 class="pull-left">Games</h1>
    
    <h1 class="pull-right">
      
      <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('games.create') !!}">Add New</a>
        
    </h1>
    
  </section>
    
  <div class="content" style = "margin-top: 20px">
    
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    
    <div class="box box-primary">
            
      <div class="box-body">

        <div class="row">
 
          <div class="col-md-12">
          
            <div class="nav-tabs-custom" style="margin-bottom: 0;">
            
              <ul class="nav nav-tabs">

                <li class = "active"><a href="#games" data-toggle="tab"> Games </a></li>
                <li><a href="#games_search" data-toggle="tab"> Games Search </a></li>

              </ul>
          
              <div class="tab-content clearfix">
          
                <div class = "tab-pane active" id = "games">

                  @include('games.table')
                  
                  <!--<div class="mailbox-controls" style="margin-top: 10px;">
                              
                    <div class="btn-group">
                                  
                    </div>
                                
                    <div class="pull-right">
                                
                      1-50
                                  
                      <div class="btn-group">
                        
                          
                      </div>
                                
                    </div>
                                
                  </div>-->

                </div>
                
                <div class = "tab-pane" id = "games_search">

                  <form action="#" method="get" class="sidebar-form">
                  
                    <input type="text" class="form-control" id = "gameSearch" name = "search" placeholder="Search..."/>
                        
                  </form>
              
                   <table class="table table-responsive" style="margin-bottom: 0;">
                      
                    <thead>
                          
                      <tr>
                             
                        <th>Name</th>
                          
                      </tr>
                      
                    </thead>
                      
                    <tbody id = "t_game_search">
                
                    </tbody>
                
                  </table>
              
                </div>
            
              </div>

            </div>
              
          </div>
            
        </div>

      </div>
      
    </div>

    <div class="text-center">
        
    </div>

  </div>
  
  <!--<aside class="control-sidebar control-sidebar-dark control-sidebar-close" style="background: rgba(0,0,0,0.8);">
  
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      
      <li class="active">
        
        <a href="#colleges" data-toggle="tab">
        
          <i class="fa fa-graduation-cap"></i>
        
        </a>
        
      </li>
    
    </ul>
    
    <div class="tab-content" style="padding: 20px;">
    
      <div class="tab-pane active" id="colleges">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Colleges </h3>
        
        <ul class="control-sidebar-menu">
          
          <li>
                
            <i class="menu-icon fa fa-graduation-cap bg-light-blue"></i>
            
            <div class="menu-info">
                    
            </div>
                
          </li>
        
        </ul>

      </div>
      
    </div>
    
  </aside>-->
  
@endsection