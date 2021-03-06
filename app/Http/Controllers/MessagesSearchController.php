<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MessagesSearchController extends Controller
{
	public function index()
	{
		return view('search.search');
	}
 
	public function search(Request $request)
	{
        $user_id = Auth::user()->id;
 
		if($request->ajax())
		{
			$output="";
			$s_messages = DB::table('users')->join('messages', 'messages.s_user_id', '=', 'users.id')->where('s_user_id', '=', $user_id)->where('messages.subject','LIKE','%'.$request->search.'%')->where(function ($query) {$query->where('messages.deleted_at', '=', null);})->orderBy('messages.datetime', 'desc')->get();

			if($s_messages)
 			{
				foreach ($s_messages as $key => $s_message)
				{
					$output.='{!! Form::open(["route" => ["messages.destroy", $s_message->id], "method" => "delete"]) !!}

					    <tr>
                            <td><div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div></td>
                            <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                            <td class="mailbox-name"><a href="read-mail.html">'.$s_message -> name.'</a></td>
                            <td class="mailbox-subject"><b>'.$s_message -> subject.'</b></td>
                            <td class="mailbox-attachment">'.$s_message -> content.'</td>
                            <td class="mailbox-date">'.$s_message -> datetime.'</td>
                            
                        </tr>
                    
                    {!! Form::close() !!}';
				}
 
				return Response($output);
 			}
 		}
 	}
}