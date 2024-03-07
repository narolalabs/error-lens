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
        if (!Schema::hasTable('error_logs')) {
            Schema::create('error_logs', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('url', 191);
                $table->integer('status')->default('0');
                $table->json('request_data')->nullable();
                $table->json('headers')->nullable();
                $table->text('message');
                $table->json('error')->nullable();
                $table->json('trace')->nullable();
                $table->string('email', 100)->nullable();
                $table->string('ip_address', 30)->nullable();
                $table->string('guard', 30)->nullable();
                $table->string('browser', 30)->nullable();
                $table->string('previous_url', 191)->nullable();
                $table->integer('repeated')->default('1');
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('error_log_configs')) {
            Schema::create('error_log_configs', function (Blueprint $table) {
                $table->id();
                $table->string('key', 191)->unique();
                $table->text('value');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('error_logs_archived')) {
            Schema::create('error_logs_archived', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('url', 191);
                $table->integer('status')->default('0');
                $table->json('request_data')->nullable();
                $table->json('headers')->nullable();
                $table->text('message');
                $table->json('error')->nullable();
                $table->json('trace')->nullable();
                $table->string('email', 100)->nullable();
                $table->string('ip_address', 30)->nullable();
                $table->string('guard', 30)->nullable();
                $table->string('browser', 30)->nullable();
                $table->string('previous_url', 191)->nullable();
                $table->integer('repeated')->default('1');
                $table->timestamps();
            });
        }

        if (Schema::hasTable('error_logs')) {
            Schema::table('error_logs', function (Blueprint $table) {
                if ( ! Schema::hasColumn('error_logs', 'status')) {
                    $table->integer('status')->default('0')->after('browser');
                }
                if ( ! Schema::hasColumn('error_logs', 'request_data')) {
                    $table->json('request_data')->nullable()->after('status');
                }
                if ( ! Schema::hasColumn('error_logs', 'guard')) {
                    $table->string('guard', 30)->nullable()->after('request_data');
                }
                if ( ! Schema::hasColumn('error_logs', 'repeated')) {
                    $table->integer('repeated')->default('1')->after('guard');
                }
                if ( ! Schema::hasColumn('error_logs', 'headers')) {
                    $table->json('headers')->nullable()->after('repeated');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
