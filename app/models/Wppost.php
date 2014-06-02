<?php 
/*
 * @package		Loitd
 * @author		Tran Duc Loi
 * @copyright	Copyright (c) 2013 - 2014, Loitd, Inc.
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://loitd.com
 * @since		Version 1.0
*/

class Wppost extends Eloquent{
	protected $table 		= 'wp_posts';
	//protected $primaryKey	= 'productid';
	//public $incrementing	= false; //the very important key
	//public $timestamps 		= true; //will use updated_at and created_at automatically from laravel
	public static function get_post_id($limit = 5, $offset = 0, $slug = GUIDE_CAT_SLUG){
		//get the term id
		$term_id 		= 	Wpterm::select('term_id')
							->where('slug', '=', $slug)
							->take(1)
							->get()[0]->term_id;
		//get termtaxid
		$termtax_id 	= 	Wptermtax::select('term_taxonomy_id')
							->where('term_id', '=', $term_id)
							->take(1)
							->get()[0]->term_taxonomy_id;
		//get object id
		$obj_id			= 	Wptermrel::select('object_id')
							->where('term_taxonomy_id', '=', $termtax_id)
							->limit($limit)
							->offset($offset)
							->orderBy('object_id', 'desc')
							//->paginate($limit)
							->get();
		return $obj_id;
	}

	public static function getallpid($slug = INFO_CAT_SLUG)
	{
		//get the term id
		$term_id 		= 	Wpterm::select('term_id')
							->where('slug', '=', $slug)
							->take(1)
							->get()[0]->term_id;
		//get termtaxid
		$termtax_id 	= 	Wptermtax::select('term_taxonomy_id')
							->where('term_id', '=', $term_id)
							->take(1)
							->get()[0]->term_taxonomy_id;
		//get object id
		$obj_id			= 	Wptermrel::select('object_id')
							->where('term_taxonomy_id', '=', $termtax_id)
							->orderBy('object_id', 'desc')
							//->paginate(2);
							->get();
		
		//get array of object ID
		for ($i=0; $i < count($obj_id); $i++) { 
			$obj_arr[$i]=	$obj_id[$i]->object_id ;
		}
		
		//$post 			= Wppost::whereIn('id', $obj_arr)->paginate(2);

		return $obj_arr;
	}

	public static function get_post_thumb($id, $default = DEFAULT_POST_THUMB)
	{
		$x 				= 	Wppost::select('guid')
							->where('post_parent','=', $id)
							->where('post_type', '=', 'attachment')
							->orderBy('post_date', 'desc')
							->take(1)
							->get();
		
		if (count($x) < 1) {
			$b 			= new stdClass();
			$b->guid	= $default;
			$a 			= array($b);
			$x 			= $a;
		}

		return $x[0]->guid;
	}

	public static function get_footer_post($what=0)
	{
		$pid 		= Wppost::get_post_id(1, $what, INFO_CAT_SLUG);
		$post 	 	= Wppost::find($pid[0]->object_id);
		$thumb 		= Wppost::get_post_thumb($pid[0]->object_id);
		
		$data = array('post'=>$post, 'thumb'=>$thumb);
		return $data;
	}



	//class Mprovider
}