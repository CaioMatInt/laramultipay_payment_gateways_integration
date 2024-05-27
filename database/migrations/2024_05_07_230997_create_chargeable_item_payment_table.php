<?php

use App\Models\ChargeableItem;
use App\Models\Payment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chargeable_item_payment', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ChargeableItem::class);
            $table->foreignIdFor(Payment::class);
            $table->integer('quantity')->default(1);
            $table->integer('price');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chargeable_item_payment');
    }
};
