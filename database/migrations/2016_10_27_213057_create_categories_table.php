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
            $table->unsignedInteger('user_id');
            
            $table->foreign('user_id')->references('id')->on('users');
        });
    
        $userRepository = app(Factotum\User\UserRepository::class);
        $user = $userRepository->create('alexey', 'secret', 'demo@factotum.app');
        $userRepository->activate($user);
        
        $categoryRepository = app(\Factotum\Category\CategoryRepository::class);
        $categoryRepository->create(['name'=>'Коммунальные', 'queue'=>1], $user);
        $categoryRepository->create(['name'=>'Покупки', 'queue'=>2], $user);
        $categoryRepository->create(['name'=>'Еда', 'queue'=>3], $user);
        $categoryRepository->create(['name'=>'Ремонт', 'queue'=>4], $user);
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
