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

        Schema::create('artifacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('request_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('approval_request_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_type_id')->constrained('request_types');
            $table->foreignId('user_id')->constrained('users');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->boolean('require_approval')->default(false);
            $table->boolean('require_artifacts')->default(false);
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('restrictions', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->string('type');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('activity_restriction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities');
            $table->foreignId('restriction_id')->constrained('restrictions');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('access_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_type_id')->constrained('request_types');
            $table->foreignId('activity_id')->constrained('activities');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('commercial_unit_id')->constrained('commercial_units');
            $table->foreignId('provider_id')->nullable()->constrained('providers');
            $table->date('request_date');
            $table->string('applicant')->nullable();
            $table->string('responsible')->nullable();
            $table->string('responsible_ci')->nullable();
            $table->string('responsible_phone')->nullable();
            $table->text('details')->nullable();
            $table->text('supervisor_comments')->nullable();
            $table->boolean('is_done')->default(false);
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('access_request_artifact', function (Blueprint $table) {
            $table->id();
            $table->foreignId('access_request_id')->constrained('access_requests');
            $table->foreignId('artifact_id')->constrained('artifacts');
            $table->string('type');
            $table->integer('quantity');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('access_request_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('access_request_id')->constrained('access_requests');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('number_persons')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists("access_request_schedules");
        Schema::dropIfExists("access_request_artifact");
        Schema::dropIfExists("access_requests");
        Schema::dropIfExists("activity_restriction");
        Schema::dropIfExists("restrictions");
        Schema::dropIfExists("activities");
        Schema::dropIfExists("approval_request_types");
        Schema::dropIfExists("request_types");
        Schema::dropIfExists("artifacts");
        Schema::dropIfExists("contacts");
    }
};
