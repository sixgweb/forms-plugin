<?php

namespace Sixgweb\Forms\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddIpAddressToEntriesTable extends Migration
{
    public function up()
    {
        Schema::table('sixgweb_forms_entries', function (
            Blueprint $table
        ) {
            $table->string('ip_address')
                ->nullable()
                ->after('form_id');
        });
    }

    public function down()
    {
        Schema::table('sixgweb_forms_entries', function (
            Blueprint $table
        ) {
            if (Schema::hasColumn($table->getTable(), 'ip_address')) {
                $table->dropColumn('ip_address');
            }
        });
    }
}
