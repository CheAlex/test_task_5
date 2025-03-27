<?php

declare(strict_types=1);

use App\Models\Wallet;
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
        Schema::create('wallets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('currency', 15);
            $table->string('address', 250);
            $table->datetime('created_at');
            $table->unique(['address', 'currency'], 'address_currency_UNQ');
        });

        Schema::create('wallet_balances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Wallet::class)->constrained();
            $table->decimal('balance', 33, 18);
            $table->datetime('created_at');
            $table->index(['created_at', 'wallet_id'], 'created_at_wallet_id_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_balances');
        Schema::dropIfExists('wallets');
    }
};
