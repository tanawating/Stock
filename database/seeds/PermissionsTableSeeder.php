<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      	$permissions = 	array 
	      					(
	                            array('name'=>'read-stock','display_name'=>'Read Stock','page'=>'stock'),
	                            array('name'=>'add-stock','display_name'=>'Add Stock','page'=>'add_stock'),
	                            array('name'=>'cut-stock','display_name'=>'Cut Stock','page'=>'cut_stock'),
	                            array('name'=>'search-product','display_name'=>'Search Product','page'=>'search_product'),
	                            array('name'=>'read-user','display_name'=>'Read User Management','page'=>'user'),
	                            array('name'=>'write-user','display_name'=>'Write User Management','page'=>'user'),
	                            array('name'=>'update-user','display_name'=>'Update User Management','page'=>'user'),
	                            array('name'=>'read-master-data','display_name'=>'Read Master Data','page'=>'master_data'),
	                            array('name'=>'write-master-data','display_name'=>'Write Master Data','page'=>'master_data'),
	                            array('name'=>'update-maste-data','display_name'=>'Update Master Data','page'=>'master_data'),
	                        );

		foreach ($permissions as $key => $value) 
		{
			DB::table('permissions')->insert([
	        	'name'				=>	$value['name'],
	        	'display_name'		=>	$value['display_name'],
                'created_at'        => 	date('Y-m-d H:i:s'),
                'updated_at'        => 	date('Y-m-d H:i:s'),
                'page'				=>	$value['page']
	        ]);
		}
    }
}
