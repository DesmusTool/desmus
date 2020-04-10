<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SharedProfileVideoSearchController extends Controller
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
			$shared_profile_videos = DB::table('shared_profile_video')->where('shared_profile_video.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('shared_profile_video.deleted_at', '=', null);})->orderBy('shared_profile_video.name', 'asc')->limit(50)->get();
 
			if($shared_profile_videos)
 			{
				foreach ($shared_profile_videos as $key => $shared_profile_video)
				{
					$output.='<tr>'.'<td> <a href = "'.route("sharedProfileVideos.show", [ $shared_profile_video -> id ]).'">'.$shared_profile_video->name.'</a></td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}