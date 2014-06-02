<?php 
/*
 * @package		Loitd
 * @author		Tran Duc Loi
 * @copyright	Copyright (c) 2013 - 2014, Loitd, Inc.
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://loitd.com
 * @since		Version 1.0
*/

class Tintuc extends BaseController{

	//when index form is get
	public function index()
	{
		
		$post 	= 	Wppost::whereIn('id', Wppost::getallpid(INFO_CAT_SLUG))
					->orderBy('id', 'desc')
					->paginate(3);

		$data = array('post'=>$post);
		return View::make('tintuc.index', $data);
	}

	//detail
	public function show($id){
		$title 		= Wppost::find($id)->post_title;
		$content 	= Wppost::find($id)->post_content;
		$data = array('title'=>$title, 'content'=>$content);
		return View::make('tintuc.detail', $data);
	}

	public function create()
	{
		return Wppost::getallpid();
	}

}
