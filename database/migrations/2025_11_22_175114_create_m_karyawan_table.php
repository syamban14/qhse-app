<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     *
     * @var string
     */
    public $connection = 'pgsql_master';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_id')->nullable();
            $table->string('nama_karyawan');
            $table->string('title')->nullable();
            $table->string('dept_id')->nullable();
            $table->string('div_id')->nullable();
            $table->string('dir_id')->nullable();
            $table->string('grade')->nullable();
            $table->string('level')->nullable();
            $table->string('cost_sales_id')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('point_of_hire')->nullable();
            $table->string('status')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->date('tgl_masuk')->nullable();
            $table->string('reason_in')->nullable();
            $table->date('tgl_keluar')->nullable();
            $table->string('reason_out')->nullable();
            $table->dateTime('reactive_date')->nullable();
            $table->string('reactive_by')->nullable();
            $table->text('reactive_reason')->nullable();
            $table->date('tgl_finish_contract')->nullable();
            $table->string('nama_customer')->nullable();
            $table->string('id_customer')->nullable();
            $table->date('id_customer_expiredate')->nullable();
            $table->string('id_driver')->nullable();
            $table->string('no_kk')->nullable();
            $table->string('no_ktp')->nullable();
            $table->date('no_ktp_expiredate')->nullable();
            $table->string('no_sim_a')->nullable();
            $table->date('no_sim_a_expiredate')->nullable();
            $table->string('no_sim_b1')->nullable();
            $table->date('no_sim_b1_expiredate')->nullable();
            $table->string('no_sim_b2_umum')->nullable();
            $table->date('no_sim_b2_umum_expiredate')->nullable();
            $table->string('no_sim_c')->nullable();
            $table->date('no_sim_c_expiredate')->nullable();
            $table->string('agama')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('ptkp')->nullable();
            $table->date('married_date')->nullable();
            $table->string('gol_darah')->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kota_ktp')->nullable();
            $table->string('id_kecamatan')->nullable();
            $table->string('nama_kecamatan')->nullable();
            $table->string('id_kelurahan')->nullable();
            $table->string('nama_kelurahan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->text('alamat_ktp2')->nullable();
            $table->string('kota_ktp2')->nullable();
            $table->string('kode_pos2')->nullable();
            $table->string('telp1')->nullable();
            $table->string('telp2')->nullable();
            $table->string('no_npwp')->nullable();
            $table->date('npwp_effectivedate')->nullable();
            $table->text('alamat_npwp')->nullable();
            $table->string('npp_bpjs_ketenagakerjaan')->nullable();
            $table->string('no_bpjs_ketenagakerjaan')->nullable();
            $table->string('bulan_bpjs_ketenagakerjaan')->nullable();
            $table->string('tahun_bpjs_ketenagakerjaan')->nullable();
            $table->string('nama_ibu_kandung')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('no_account_bank')->nullable();
            $table->string('va_bpjs_kesehatan')->nullable();
            $table->string('no_bpjs_kesehatan')->nullable();
            $table->string('bulan_bpjs_kesehatan')->nullable();
            $table->string('tahun_bpjs_kesehatan')->nullable();
            $table->string('kode_faskes')->nullable();
            $table->string('nama_faskes')->nullable();
            $table->string('kode_faskes_dokter_gigi')->nullable();
            $table->string('nama_faskes_dokter_gigi')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('nama_sekolah_terakhir')->nullable();
            $table->string('tahun_lulus', 4)->nullable();
            $table->string('foto')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('pic_hr')->nullable();
            $table->boolean('aktif')->default(true);
            $table->string('active_period')->nullable();
            $table->string('created_by')->nullable();
            $table->dateTime('date_created')->nullable();
            $table->string('updated_by')->nullable();
            $table->dateTime('last_updated')->nullable();
            $table->integer('group_id')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('attachment')->nullable();
            $table->date('agreement_expire')->nullable();
            $table->string('scan_ktp')->nullable();
            $table->string('scan_kk')->nullable();
            $table->string('scan_sim_a')->nullable();
            $table->string('scan_sim_b1')->nullable();
            $table->string('scan_sim_b2')->nullable();
            $table->string('scan_npwp')->nullable();
            $table->string('scan_bpjs_kes')->nullable();
            $table->string('scan_bpjs_tk')->nullable();
            $table->string('flag')->nullable();
            $table->string('pic')->nullable();
            $table->string('photo')->nullable();
            $table->string('photo_ktp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_karyawan');
    }
};