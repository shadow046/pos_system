<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_type')->index();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->dateTime('sold_at')->index();
            $table->unsignedBigInteger('total_order')->index();
            $table->decimal('sales', 20, 2)->index();
            $table->decimal('discount', 20, 2)->index();
            $table->decimal('amount', 20, 2)->index();
            $table->string('status')->index();
            $table->timestamps();
            $table->uuid('uuid')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
