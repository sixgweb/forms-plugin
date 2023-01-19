<?php

namespace Sixgweb\Forms\Updates;

use Schema;
use Sixgweb\Forms\Models\Form;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddSettingsToFormsTable extends Migration
{
    public function up()
    {
        Schema::table('sixgweb_forms_forms', function (
            Blueprint $table
        ) {
            $table->json('settings')
                ->nullable()
                ->after('confirmation');
        });
    }

    public function down()
    {
        Schema::table('sixgweb_forms_forms', function (
            Blueprint $table
        ) {
            if (Schema::hasColumn($table->getTable(), 'settings')) {
                $table->dropColumn('settings');
            }
        });
    }
}
