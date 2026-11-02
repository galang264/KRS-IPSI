
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Membuat function untuk memeriksa batas maksimum SKS.
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE FUNCTION public.fn_cek_maks_sks()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $function$
DECLARE
    v_maks INTEGER := 24;
    v_total INTEGER := 0;
    v_sks_baru INTEGER := 0;
BEGIN
    -- Mengambil batas maksimum SKS dari aturan akademik.
    SELECT COALESCE(
        (
            SELECT nilai
            FROM public.aturan_akademik
            WHERE kode = 'maks_sks'
            LIMIT 1
        ),
        24
    )
    INTO v_maks;

    -- Menghitung total SKS yang sudah diambil.
    IF TG_OP = 'UPDATE' THEN

        SELECT COALESCE(SUM(mk.sks), 0)::INTEGER
        INTO v_total
        FROM public.krs_detail kd
        JOIN public.kelas kl
            ON kl.id_kelas = kd.id_kelas
        JOIN public.mata_kuliah mk
            ON mk.kode_mk = kl.kode_mk
        WHERE kd.id_krs = NEW.id_krs
          AND NOT (
              kd.id_krs = OLD.id_krs
              AND kd.id_kelas = OLD.id_kelas
          );

    ELSE

        SELECT COALESCE(SUM(mk.sks), 0)::INTEGER
        INTO v_total
        FROM public.krs_detail kd
        JOIN public.kelas kl
            ON kl.id_kelas = kd.id_kelas
        JOIN public.mata_kuliah mk
            ON mk.kode_mk = kl.kode_mk
        WHERE kd.id_krs = NEW.id_krs;

    END IF;

    -- Mengambil jumlah SKS dari kelas yang akan ditambahkan.
    SELECT mk.sks::INTEGER
    INTO v_sks_baru
    FROM public.kelas kl
    JOIN public.mata_kuliah mk
        ON mk.kode_mk = kl.kode_mk
    WHERE kl.id_kelas = NEW.id_kelas;

    -- Menolak jika total SKS melebihi batas.
    IF v_total + COALESCE(v_sks_baru, 0) > v_maks THEN

        RAISE EXCEPTION
            'Total SKS % melebihi batas maksimum %',
            v_total + COALESCE(v_sks_baru, 0),
            v_maks
        USING ERRCODE = '23514';

    END IF;

    RETURN NEW;
END;
$function$;
SQL);

        // Trigger ketika data KRS detail ditambahkan.
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_krs_detail_maks_sks_insert
BEFORE INSERT ON public.krs_detail
FOR EACH ROW
EXECUTE FUNCTION public.fn_cek_maks_sks();
SQL);

        // Trigger ketika data KRS detail diperbarui.
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_krs_detail_maks_sks_update
BEFORE UPDATE ON public.krs_detail
FOR EACH ROW
EXECUTE FUNCTION public.fn_cek_maks_sks();
SQL);
    }

    public function down(): void
    {
        // Menghapus trigger terlebih dahulu.
        DB::unprepared(
            'DROP TRIGGER IF EXISTS trg_krs_detail_maks_sks_update ON public.krs_detail'
        );

        DB::unprepared(
            'DROP TRIGGER IF EXISTS trg_krs_detail_maks_sks_insert ON public.krs_detail'
        );

        // Menghapus function.
        DB::unprepared(
            'DROP FUNCTION IF EXISTS public.fn_cek_maks_sks()'
        );
    }
};
