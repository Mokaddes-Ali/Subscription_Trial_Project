<?php

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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('stripe_price_id')->unique();
            $table->integer('trial_days')->nullable();
            $table->float('amount',10,2);
            $table->integer('type')->comment('0->Monthly, 1->Yearly, 2->Lifetime');
            $table->integer('status')->comment('0->Inactive, 1->Active');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
