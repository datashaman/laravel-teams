<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsSchema extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('team_id')->unsigned();
            $table->string('slug');
            $table->string('name');
            $table->timestamps();

            $table->unique(['team_id', 'slug']);
            $table->unique(['team_id', 'name']);
            $table->foreign('team_id')->references('id')->on('teams');
        });

        Schema::create('team_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('team_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();

            $table->unique(['team_id', 'user_id']);

            $table->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('role');
            $table->bigInteger('team_id')->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'role', 'team_id']);
            $table->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('teams');
    }
}
