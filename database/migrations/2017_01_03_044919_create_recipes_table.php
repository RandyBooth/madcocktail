<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function($table)
        {
            $table->increments('id');
            $table->string('token', 6)->unique();
            $table->string('title');
            $table->string('title_first_letter', 1);
            $table->string('slug')->unique();
            $table->string('description');
            $table->text('directions');
            $table->tinyInteger('glass_id')->unsigned()->default(0);
            $table->tinyInteger('rate_avg')->unsigned()->default(0);
            $table->smallInteger('liked')->unsigned()->default(0);
            $table->integer('user_id')->unsigned()->default(0);
//            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('is_active')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recipes');
    }
}
