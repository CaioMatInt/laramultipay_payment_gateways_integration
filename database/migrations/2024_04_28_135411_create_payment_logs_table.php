<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    //@@TODO: move to MongoDb
    public function up(): void
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            /*$table->foreignIdFor(Payment::class);
            $table->enum('event_type', ['status_change', 'error']);
            $table->unsignedInteger('previous_status_id');
            $table->foreign('previous_status_id')->references('id')->on('payment_generic_statuses');
            $table->unsignedInteger('new_status_id')->nullable();
            $table->foreign('new_status_id')->references('id')->on('payment_generic_statuses');
            $table->foreignIdFor(User::class);
            $table->text('details')->nullable();
            $table->date('date');
            $table->softDeletes();
            $table->timestamps();*/
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
