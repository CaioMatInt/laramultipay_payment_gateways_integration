<?php

use App\Enums\Payment\PaymentCurrencyEnum;
use App\Models\Company;
use App\Models\PaymentGateway;
use App\Models\PaymentGenericStatus;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid();
            $table->string('name');
            $table->integer('amount');
            $table->enum('currency', PaymentCurrencyEnum::values());
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Company::class);
            $table->foreignIdFor(PaymentGenericStatus::class);
            $table->foreignIdFor(PaymentGateway::class)->nullable();
            $table->foreignIdFor(PaymentMethod::class)->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
