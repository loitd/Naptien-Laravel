<?php 
/*
 * @package		Loitd
 * @author		Tran Duc Loi
 * @copyright	Copyright (c) 2013 - 2014, Loitd, Inc.
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://loitd.com
 * @since		Version 1.0
*/

class Huongdan extends BaseController{

	//when index form is get
	public function index()
	{
		$title 		= Wppost::find(Wppost::get_post_id()[0]->object_id)->post_title;
		$content 	= Wppost::find(Wppost::get_post_id()[0]->object_id)->post_content;
		$data = array('title'=>$title, 'content'=>$content);
		return View::make('huongdan.index', $data);
	}
}