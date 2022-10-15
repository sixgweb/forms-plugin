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
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sixgweb_forms_forms');
    }
}
