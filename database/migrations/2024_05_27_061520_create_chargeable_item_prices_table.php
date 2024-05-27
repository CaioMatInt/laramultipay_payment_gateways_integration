<?php

use App\Enums\Payment\PaymentCurrencyEnum;
use App\Models\ChargeableItem;
use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chargeable_item_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->enum('currency', PaymentCurrencyEnum::values());
            $table->foreignIdFor(ChargeableItem::class);
            $table->foreignIdFor(Company::class);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chargeable_item_prices');
    }
};
