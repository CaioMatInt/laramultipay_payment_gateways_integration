<?php

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
          /*  $table->foreignIdFor(Transaction::class);
            //@@TODO: use Enums
            $table->enum('event_type', ['status_change', 'error']);
            $table->foreignId('previous_status_id');
            $table->foreign('previous_status_id')->references('id')->on('payment_gateway_transaction_statuses');
            $table->foreignId('new_status_id')->nullable();
            $table->foreign('new_status_id')->references('id')->on('payment_gateway_transaction_statuses');
            $table->foreignIdFor(User::class);
            $table->text('details');
            $table->date('date');
            $table->softDeletes();
            $table->timestamps();*/
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_logs');
    }
};
