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
        // Packages / Service Pricing (Owner can edit prices shown on public dashboard)
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // reguler, vip, vvip, prioritas
            $table->string('name');
            $table->decimal('price_per_kg', 12, 2);
            $table->string('unit')->default('/kg');
            $table->integer('estimated_hours')->default(48); // completion estimation
            $table->integer('priority_level')->default(1); // 1: Reguler, 2: VIP, 3: VVIP, 4: Prioritas
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->boolean('popular')->default(false);
            $table->string('bg_gradient')->default('from-green-50 to-green-100');
            $table->string('badge_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // e.g. ORD-20261023-XXXX
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->string('package_code')->default('reguler');
            $table->string('package_name')->default('Reguler');
            $table->decimal('price_per_kg', 12, 2)->default(0);
            $table->integer('estimated_hours')->default(48);
            $table->integer('priority_level')->default(1); // for priority queue: 4: Prioritas, 3: VVIP, 2: VIP, 1: Reguler

            $table->decimal('weight', 8, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);

            // Supir (Driver) input & tracking
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('driver_item_count')->nullable(); // total pieces input by driver
            $table->text('driver_notes')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('dropped_at')->nullable();

            // Operator input & tracking
            $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('operator_item_count')->nullable(); // re-count pieces input by operator
            $table->integer('item_discrepancy')->default(0); // difference: operator - driver
            $table->text('discrepancy_note')->nullable();

            // Status Workflow:
            // menunggu_penjemputan -> telah_diambil -> telah_drop -> siap_dikerjakan -> proses -> pending -> siap_diantar -> dalam_pengantaran -> telah_diantar
            $table->string('status')->default('menunggu_penjemputan');

            // Operator delay / penundaan
            $table->text('delay_reason')->nullable();
            $table->integer('delay_minutes')->default(0);
            $table->timestamp('delayed_at')->nullable();

            // Time Predictions
            $table->timestamp('estimated_completion_at')->nullable();
            $table->timestamp('actual_completion_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->text('customer_notes')->nullable();
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            $table->timestamps();
        });

        // Activity Logs (Full monitoring of all 4 roles for Owner & Admin)
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name');
            $table->string('role'); // owner, admin, supir, oprator, customer, system
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('order_code')->nullable();
            $table->string('action'); // e.g. PRICE_UPDATE, ORDER_CREATED, DRIVER_PICKUP, DRIVER_DROP, OPERATOR_START, OPERATOR_DELAY, OPERATOR_RECOUNT, OPERATOR_FINISH, DRIVER_DELIVERY
            $table->string('title');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('packages');
    }
};

