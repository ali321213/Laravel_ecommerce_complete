<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $table = 'products';
    public function up(): void
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function (Blueprint $table) {
                $table->id();
                // $table->string('name');
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('summary')->nullable();
                $table->text('short_description')->nullable();
                $table->longText('description')->nullable();
                $table->text('long_description')->nullable();
                $table->text('photo')->nullable();
                $table->string('featured_image')->nullable();

                $table->integer('stock')->default(1);
                $table->string('size')->nullable()->default('M');
                $table->enum('condition', ['default', 'new', 'hot'])->default('default');
                $table->enum('status', ['active', 'inactive'])->default('inactive');

                $table->decimal('price', 10, 2);
                $table->decimal('discount', 8, 2)->nullable()->default(0);

                $table->boolean('is_variable')->default(false);
                $table->boolean('is_grouped')->default(false);
                $table->boolean('is_simple')->default(true);
                $table->boolean('is_featured')->default(false);
                // Foreign keys
                $table->foreignId('category_id')->nullable()->constrained('product_categories')->onUpdate('cascade')->onDelete('cascade');
                $table->unsignedBigInteger('cat_id')->nullable();
                $table->unsignedBigInteger('child_cat_id')->nullable();
                $table->unsignedBigInteger('brand_id')->nullable();
                $table->foreign('brand_id')->references('id')->on('brands')->onDelete('SET NULL');
                $table->foreign('cat_id')->references('id')->on('categories')->onDelete('SET NULL');
                $table->foreign('child_cat_id')->references('id')->on('categories')->onDelete('SET NULL');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
