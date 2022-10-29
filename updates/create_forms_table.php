<?php

namespace Sixgweb\Forms\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateFormsTable Migration
 */
class CreateFormsTable extends Migration
{
    public function up()
    {
        Schema::create('sixgweb_forms_forms', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(0);
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('confirmation')->nullable();
            $table->boolean('save_entries')->default(1);
            $table->boolean('throttle_entries')->default(0);
            $table->integer('throttle_timeout')->unsigned()->nullable();
            $table->integer('throttle_threshold')->unsigned()->nullable();
            $table->boolean('purge_entries')->default(0);
            $table->integer('purge_days')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sixgweb_forms_forms');
    }
}
