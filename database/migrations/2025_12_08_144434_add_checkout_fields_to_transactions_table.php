<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {

            if (!Schema::hasColumn('transactions', 'recipient_name')) {
                $table->string('recipient_name')->nullable();
            }

            if (!Schema::hasColumn('transactions', 'address')) {
                $table->text('address')->nullable();
            }

            if (!Schema::hasColumn('transactions', 'city')) {
                $table->string('city')->nullable();
            }

            if (!Schema::hasColumn('transactions', 'postal_code')) {
                $table->string('postal_code')->nullable();
            }

            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }

            if (!Schema::hasColumn('transactions', 'va_number')) {
                $table->string('va_number')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $columns = [
                'recipient_name',
                'address',
                'city',
                'postal_code',
                'payment_method',
                'va_number',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
