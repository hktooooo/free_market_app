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
            $table->foreignId('payments_id')->nullable()->constrained()->onDelete('set null');
            $table->string('zipcode_purchase')->nullable();
            $table->string('address_purchase')->nullable();
            $table->string('building_purchase')->nullable();
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
