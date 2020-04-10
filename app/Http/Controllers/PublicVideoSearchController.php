<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PublicVideoSearchController extends Controller
{
    public function index()
	{
		return view('search.search');
	}
 
	public function search(Request $request)
	{
		if($request->ajax()) 
		{
			$output="";
			$public_videos = DB::table('public_video')->where('public_video.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('public_video.deleted_at', '=', null);})->orderBy('public_video.name', 'asc')->limit(50)->get();
 
			if($public_videos)
 			{
				foreach ($public_videos as $key => $public_video)
				{
					$output.='<tr>'.'<td> <a href = "'.route("publicVideos.show", [ $public_video -> id ]).'">'.$public_video->name.'</a></td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}