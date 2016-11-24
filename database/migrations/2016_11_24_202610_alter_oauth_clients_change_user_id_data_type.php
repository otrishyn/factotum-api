<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOauthClientsChangeUserIdDataType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('alter table oauth_clients modify user_id char(36)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('oauth_clients', function (Blueprint $table) {
            $table->integer('user_id')->change();
        });
    }
}
