<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeasuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measures', function($table)
        {
            $table->increments('id');
            $table->string('title', 100);
            $table->string('title_abbr', 20)->unique();
            $table->string('slug')->unique();
            $table->decimal('measurement_ml', 6, 2)->default(0);
            $table->decimal('measurement_oz', 6, 3)->default(0);
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
        Schema::drop('measures');
    }
}
