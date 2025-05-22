<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('value');
            $table->morphs('contactable');
            $table->timestamps();
        });



        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('commercial_name');
            $table->string('ruc', 13)->unique();
            $table->string('legal_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->timestamps();
        });

        Schema::create('commercial_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        Schema::create('commercial_units', function (Blueprint $table) {
            $table->id();
            $table->string('zone');
            $table->string('name');
            $table->string('local_code');
            $table->string('status');
            $table->boolean('is_island')->default(false)->nullable();
            $table->foreignId('category_id')->nullable()->constrained('commercial_categories');
            $table->string('ruc', 13)->nullable();
            $table->string('property_code')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');
            $table->foreignId('co_tenant_id')->nullable()->constrained('tenants');

            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number');
            $table->string('contract_object');
            $table->foreignId('internal_admin_id')->constrained('users');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('provider_id')->nullable()->constrained('providers');
            $table->string('product_service')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->nullable();
            $table->string('addenda')->nullable();
            $table->boolean('auto_renewal')->nullable();
            $table->text('observation')->nullable();
            $table->string('account_code')->nullable();
            $table->text('payment_terms')->nullable();
            $table->unsignedInteger('contract_cost')->nullable();
            $table->unsignedInteger('approved_additional_costs')->nullable();
            $table->unsignedInteger('approved_budget')->nullable();
            $table->unsignedInteger('total_cost')->nullable();
            $table->unsignedInteger('cost_vs_budget')->nullable();
            $table->timestamps();
        });

        Schema::create('deliverables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts');
            $table->text('documentation')->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('evaluation_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('max_score');
            $table->timestamps();
        });

        Schema::create('unit_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercial_unit_id')->constrained('commercial_units');
            $table->foreignId('evaluation_criteria_id')->constrained('evaluation_criteria');
            $table->date('evaluation_date')->nullable();
            $table->integer('score');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("unit_scores");
        Schema::dropIfExists("evaluation_criteria");
        Schema::dropIfExists("deliverables");
        Schema::dropIfExists("contracts");
        Schema::dropIfExists("unit_tenancy");
        Schema::dropIfExists("tenants");
        Schema::dropIfExists("commercial_units");
        Schema::dropIfExists("commercial_categories");
        Schema::dropIfExists("providers");
    }
};
