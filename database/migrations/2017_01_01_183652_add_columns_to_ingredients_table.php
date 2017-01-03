<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('ingredients')) {
            Schema::table('ingredients', function ($table) {
                $table->increments('id');
                $table->string('title');
                $table->string('slug');
//                $table->tinyInteger('depth')->unsigned()->default(0);
                $table->tinyInteger('is_alcoholic')->unsigned()->default(0);
                $table->tinyInteger('is_active')->unsigned()->default(0);
                $table->integer('user_id')->unsigned()->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('ingredients')) {
            Schema::table('ingredients', function ($table) {
                $table->dropColumn('id');
                $table->dropColumn('title');
                $table->dropColumn('slug');
//                $table->dropColumn('depth');
                $table->dropColumn('is_alcoholic');
                $table->dropColumn('is_active');
                $table->dropColumn('user_id');
                $table->dropTimestamps();
                $table->dropSoftDeletes();
            });
        }
    }
}
