<?php

use App\Enums\Payment\PaymentCurrencyEnum;
use App\Models\ChargeableItemCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chargeable_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('price');
            $table->enum('currency', PaymentCurrencyEnum::values());
            $table->foreignIdFor(ChargeableItemCategory::class)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chargeable_items');
    }
};
