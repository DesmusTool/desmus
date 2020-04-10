<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SharedProfileFileSearchController extends Controller
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
			$shared_profile_files = DB::table('shared_profile_file')->where('shared_profile_file.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('shared_profile_file.deleted_at', '=', null);})->orderBy('shared_profile_file.name', 'asc')->limit(50)->get();
 
			if($shared_profile_files)
 			{
				foreach ($shared_profile_files as $key => $shared_profile_file)
				{
					$output.='<tr>'.'<td> <a href = "'.route("sharedProfileFiles.show", [ $shared_profile_file -> id ]).'">'.$shared_profile_file->name.'</a></td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}