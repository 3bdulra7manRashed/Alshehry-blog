<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Migration: Convert campaigns.status from ENUM to VARCHAR
 * 
 * Problem: ENUM('draft', 'sent') doesn't support new 'sending' status
 * Solution: Change to VARCHAR(255) for flexibility
 * 
 * This uses raw SQL to avoid doctrine/dbal dependency issues.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert ENUM to VARCHAR(255)
        // This preserves existing data while allowing new status values
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, ensure all values are valid ENUM values before converting back
        // Update any 'sending' status to 'draft' to prevent data loss
        DB::statement("UPDATE campaigns SET status = 'draft' WHERE status NOT IN ('draft', 'sent')");
        
        // Convert back to ENUM
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('draft', 'sent') NOT NULL DEFAULT 'draft'");
    }
};
