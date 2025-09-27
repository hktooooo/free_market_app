<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->bigInteger('price');
            $table->string('brand')->nullable();
            $table->text('detail');
            $table->string('img_url');
            $table->foreignId('condition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->string('buyer_zipcode')->nullable();
            $table->string('buyer_address')->nullable();
            $table->string('buyer_building')->nullable();
            $table->string('buyer_payment_method')->nullable();
            $table->string('buyer_payment_status')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
