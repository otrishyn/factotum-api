<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCategoriesTable
 */
class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->timestamps();
            $table->string('name');
            $table->integer('queue');
            $table->index(['name', 'queue']);
        });

        \App\Models\Categories\Category::create(['name'=>'Коммунальные', 'queue'=>1]);
        \App\Models\Categories\Category::create(['name'=>'Покупки', 'queue'=>2]);
        \App\Models\Categories\Category::create(['name'=>'Еда', 'queue'=>3]);
        \App\Models\Categories\Category::create(['name'=>'Ремонт', 'queue'=>4]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
