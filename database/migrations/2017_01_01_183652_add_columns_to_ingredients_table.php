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
                $table->integer('depth')->nullable();
                $table->tinyInteger('is_top')->unsigned()->default(0);
                $table->integer('user_id')->unsigned()->default(0);
                $table->tinyInteger('approved')->unsigned()->default(0);
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
                $table->dropColumn('depth');
                $table->dropColumn('is_top');
                $table->dropColumn('user_id');
                $table->dropColumn('approved');
                $table->dropTimestamps();
                $table->dropSoftDeletes();
            });
        }
    }
}
