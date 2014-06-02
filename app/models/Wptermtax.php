<?php 
/*
 * @package		Loitd
 * @author		Tran Duc Loi
 * @copyright	Copyright (c) 2013 - 2014, Loitd, Inc.
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://loitd.com
 * @since		Version 1.0
*/
class Wptermtax extends Eloquent{
	protected $table 		= 'wp_term_taxonomy';
	protected $primaryKey	= 'term_taxonomy_id';
	public $incrementing	= false; //the very important key
	//public $timestamps 		= true; //will use updated_at and created_at automatically from laravel




	//class Mprovider
}