<?php

use App\Enums\Payment\PaymentCurrencyEnum;
use App\Models\Company;
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
            //@@TODO: migrate to UUID
            $table->id();
            $table->integer('amount');
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Company::class);
            $table->enum('currency', PaymentCurrencyEnum::values());
            $table->foreignIdFor(PaymentGenericStatus::class);
            $table->foreignIdFor(PaymentMethod::class);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
