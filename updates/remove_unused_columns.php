<?php

namespace Sixgweb\Forms\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class RemoveUnusedColumns extends Migration
{
    public function up()
    {
        Schema::table('sixgweb_forms_forms', function (
            Blueprint $table
        ) {
            $table->dropColumn('save_entries');
            $table->dropColumn('throttle_entries');
            $table->dropColumn('throttle_timeout');
            $table->dropColumn('throttle_threshold');
            $table->dropColumn('purge_entries');
            $table->dropColumn('purge_days');
        });
    }

    public function down()
    {
    }
}
