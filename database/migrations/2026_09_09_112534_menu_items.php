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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->string('key')->unique(); // matches menu_permissions.module_key
            $table->string('label');
            $table->string('icon')->nullable(); // phosphor icon class, e.g. 'ph-check-square'
            $table->string('route_name')->nullable();
            $table->string('section')->nullable(); // sidebar group heading, e.g. 'GMB / SEO'
            // If set, ONLY users in this department (or Super Admin/Manager) ever see this item —
            // replaces the hand-written `$user->department?->code === 'GMB'` checks.
            $table->foreignId('restricted_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
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
