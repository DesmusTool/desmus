<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PublicImageSearchController extends Controller
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
			$public_images = DB::table('public_image')->where('public_image.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('public_image.deleted_at', '=', null);})->orderBy('public_image.name', 'asc')->limit(50)->get();
 
			if($public_images)
 			{
				foreach ($public_images as $key => $public_image)
				{
					$output.='<tr>'.'<td> <a href = "'.route("publicImages.show", [ $public_image -> id ]).'">'.$public_image->name.'</a></td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}