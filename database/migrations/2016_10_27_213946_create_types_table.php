<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->index(['name']);
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')
                ->reference('id')
                ->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (\Schema::hasTable('types')) {
            \Schema::table('types', function (Blueprint $table) {
                $table->dropForeign('types_category_id_foreign');
            });
        }
        Schema::dropIfExists('types');
    }
}
