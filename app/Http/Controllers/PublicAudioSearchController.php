<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PublicAudioSearchController extends Controller
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
			$public_audios = DB::table('public_audio')->where('public_audio.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('public_audio.deleted_at', '=', null);})->orderBy('public_audio.name', 'asc')->limit(50)->get();
 
			if($public_audios)
 			{
				foreach ($public_audios as $key => $public_audio)
				{
					$output.='<tr>'.'<td> <a href = "'.route("publicAudios.show", [ $public_audio -> id ]).'">'.$public_audio->name.'</a></td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}