<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftdeletesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function ($table) {
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('oauth')) {
            Schema::table('oauth', function ($table) {
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
        if (Schema::hasTable('users')) {
            Schema::table('users', function ($table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('oauth')) {
            Schema::table('oauth', function ($table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
