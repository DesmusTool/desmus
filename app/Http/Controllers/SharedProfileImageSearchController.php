<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SharedProfileImageSearchController extends Controller
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
			$shared_profile_images = DB::table('shared_profile_image')->where('shared_profile_image.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('shared_profile_image.deleted_at', '=', null);})->orderBy('shared_profile_image.name', 'asc')->limit(50)->get();
 
			if($shared_profile_images)
 			{
				foreach ($shared_profile_images as $key => $shared_profile_image)
				{
					$output.='<tr>'.'<td> <a href = "'.route("sharedProfileImages.show", [ $shared_profile_image -> id ]).'">'.$shared_profile_image->name.'</a></td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}