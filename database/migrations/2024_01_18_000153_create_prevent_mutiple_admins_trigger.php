<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER prevent_multiple_admins_when_insert
            BEFORE INSERT ON users
            FOR EACH ROW
            BEGIN
                IF NEW.role = "admin" THEN
                    IF (SELECT COUNT(*) FROM users WHERE role = "admin") > 0 THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Não é permitido ter mais de um administrador.";
                    END IF;
                END IF;
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER prevent_multiple_admins_when_update
            BEFORE UPDATE ON users
            FOR EACH ROW
            BEGIN
                IF NEW.role = "admin" THEN
                    IF (SELECT COUNT(*) FROM users WHERE role = "admin") > 0 THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Não é permitido ter mais de um administrador.";
                    END IF;
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS prevent_multiple_admins_when_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS prevent_multiple_admins_when_update');
    }
};
