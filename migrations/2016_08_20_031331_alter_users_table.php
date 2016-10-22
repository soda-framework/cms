<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'name')) {
                    $table->renameColumn('name', 'username');
                }
                $table->integer('application_id')->unsigned()->nullable()->after('remember_token');
                $table->timestamp('last_loggedin_at')->nullable();
            });
        } else {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username')->default('');
                $table->string('email')->unique();
                $table->string('password', 60);
                $table->rememberToken();
                $table->integer('application_id')->unsigned()->nullable();
                $table->timestamps();
                $table->timestamp('last_loggedin_at')->nullable();
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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('username', 'name');
            $table->dropColumn('application_id');
            $table->dropColumn('last_loggedin_at');
        });
    }

}
