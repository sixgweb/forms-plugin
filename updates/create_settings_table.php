<?php namespace Sixgweb\Forms\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateSettingsTable Migration
 */
class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('sixgweb_forms_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sixgweb_forms_settings');
    }
}
