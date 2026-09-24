<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // database/migrations/xxxx_create_gmb_module_tables.php
        Schema::create('gmb_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('gmb_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gmb_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('gmb_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('department_id')->constrained();
            $table->foreignId('created_by')->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('gmb_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained();
            $table->unsignedSmallInteger('month'); // 1-12
            $table->unsignedSmallInteger('year');
            $table->enum('status', ['open', 'completed'])->default('open');
            $table->foreignId('completed_by')->nullable()->constrained('users');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['department_id', 'month', 'year']);
        });

        Schema::create('gmb_checklist_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gmb_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gmb_client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gmb_subcategory_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_checked')->default(false);
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
            $table->unique(['gmb_period_id', 'gmb_client_id', 'gmb_subcategory_id'], 'gmb_entry_unique');
        });

        // Permission flag on users, same pattern as auto_approve_tasks
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('can_add_gmb_clients')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
