<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SharedProfileAudioSearchController extends Controller
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
			$shared_profile_audios = DB::table('shared_profile_audio')->where('shared_profile_audio.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('shared_profile_audio.deleted_at', '=', null);})->orderBy('shared_profile_audio.name', 'asc')->limit(50)->get();
 
			if($shared_profile_audios)
 			{
				foreach ($shared_profile_audios as $key => $shared_profile_audio)
				{
					$output.='<tr>'.'<td> <a href = "'.route("sharedProfileAudios.show", [ $shared_profile_audio -> id ]).'">'.$shared_profile_audio->name.'</a></td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}