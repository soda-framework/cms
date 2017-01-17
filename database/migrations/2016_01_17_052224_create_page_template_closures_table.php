<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageTemplateClosuresTable extends Migration
{
    public function up()
    {
        Schema::create('page_template_closure', function (Blueprint $table) {
            $table->increments('closure_id');

            $table->integer('ancestor', false, true);
            $table->integer('descendant', false, true);
            $table->integer('depth', false, true);

            $table->foreign('ancestor')->references('id')->on('page_templates')->onDelete('cascade');
            $table->foreign('descendant')->references('id')->on('page_templates')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('page_template_closure', function (Blueprint $table) {
            Schema::dropIfExists('page_template_closure');
        });
    }
}
