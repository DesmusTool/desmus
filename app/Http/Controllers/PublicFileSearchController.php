<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PublicFileSearchController extends Controller
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
			$public_files = DB::table('public_file')->where('public_file.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('public_file.deleted_at', '=', null);})->orderBy('public_file.name', 'asc')->limit(50)->get();
 
			if($public_files)
 			{
				foreach ($public_files as $key => $public_file)
				{
					$output.='<tr>'.'<td> <a href = "'.route("publicFiles.show", [ $public_file -> id ]).'">'.$public_file->name.'</a></td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}