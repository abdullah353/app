<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('keys', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('devname');
			$table->string('appname');
			$table->string('cert');
			$table->string('gtoken');
			$table->integer('complvl');
			$table->integer('siteID');
			$table->string('serverUrl');
			$table->string('runame');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('keys');
	}

}
