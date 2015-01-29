<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		Schema::create('xmpp', function ($table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('username');
            $table->string('password');
        });

        Schema::create('voip', function ($table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('extension');
            $table->string('password');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('xmpp');
		Schema::drop('voip');
	}

}

