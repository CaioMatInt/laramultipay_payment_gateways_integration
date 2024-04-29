<?php

use App\Models\PaymentGateway;
use App\Models\PaymentGenericStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_gateway_transaction_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(PaymentGateway::class);
            $table->foreignIdFor(PaymentGenericStatus::class);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_transaction_statuses');
    }
};
