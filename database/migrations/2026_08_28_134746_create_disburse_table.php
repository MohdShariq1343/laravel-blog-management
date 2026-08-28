<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disburse', function (Blueprint $table) {
            $table->id('case_id');
            $table->string('disburse_id')->unique();
            $table->string('loan_acc');
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('contact');
            $table->string('vehicle_model');
            $table->string('bank_name');
            $table->string('services'); // New, Used, Top-Up, B.T. Top-Up, Purchase, Refinance
            $table->decimal('loan_amount', 12, 2);
            $table->decimal('total_commission', 10, 2);
            $table->decimal('agent_commission', 10, 2);
            $table->decimal('profit', 10, 2);
            $table->date('disburse_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disburse');
    }
};