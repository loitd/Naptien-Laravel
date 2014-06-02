<?php 
class Muser extends Eloquent{
	/*
		specify a custome table or Eloquent will use lower-case, plural name of the class as the table. 
		Eg: User -> users table
	*/
	protected $table = 'topup_users';

	/*
		specify a primary key for the table. Eloquent will assume each table has a primary key named 'id'
	*/
	protected $primaryKey = 'email';
	/*
		specify the name of the db connection
	*/
	//protected $connection = 'muser_db_connection';

}