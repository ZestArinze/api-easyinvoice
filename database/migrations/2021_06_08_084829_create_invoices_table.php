<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->text('summary')->nullable();
            $table->double('subtotal')->default(0);
            $table->double('vat')->default(0);
            $table->double('total');
            $table->double('total_paid')->default(0);
            $table->string('status')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->foreignId('currency_id')->constrained();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('business_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
