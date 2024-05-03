<?php

use App\Models\Company;
use App\Models\Payment;
use App\Models\PaymentGatewayTransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid();
            $table->foreignIdFor(Payment::class);
            $table->foreignIdFor(PaymentGatewayTransactionStatus::class);
            $table->foreignIdFor(Company::class);
            $table->string('gateway_transaction_id')->nullable();
            $table->string('gateway_status')->nullable();
            $table->string('response_code')->nullable();
            $table->date('date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
