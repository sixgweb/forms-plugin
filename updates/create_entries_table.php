<?php

namespace Sixgweb\Forms\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateEntriesTable Migration
 */
class CreateEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('sixgweb_forms_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('form_id');
            $table->json('field_values')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sixgweb_forms_entries');
    }
}
