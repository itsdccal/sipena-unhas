<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\ActivityDetail;
use App\Models\StudyProgram;
use App\Models\Semester;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang sudah ada
        $user = User::where('role', 'staff')->first(); // Ambil user staff pertama

        $studyProgram = StudyProgram::find($user->study_program_id);
        // Ambil unit IDs
        $unitKEG = Unit::where('name', 'KEG')->first();
        $unitSKSTM = Unit::where('name', 'SKS TM')->first();
        $unitOK = Unit::where('name', 'OK')->first();
        $unitDU = Unit::where('name', 'DU')->first();

        if (!$studyProgram || !$user || !$unitKEG || !$unitSKSTM || !$unitOK || !$unitDU) {
            $this->command->error('Data StudyProgram, User, atau Unit tidak ditemukan. Pastikan data sudah ada di database.');
            return;
        }

        // SEMESTER 1
        $this->seedSemester1($studyProgram, $user, $unitKEG, $unitSKSTM, $unitDU, $unitOK);

        // SEMESTER 2
        $this->seedSemester2($studyProgram, $user, $unitSKSTM, $unitDU, $unitOK);

        // SEMESTER 3
        $this->seedSemester3($studyProgram, $user, $unitSKSTM, $unitDU, $unitOK);

        // SEMESTER 4
        $this->seedSemester4($studyProgram, $user, $unitSKSTM, $unitDU, $unitOK);

        // SEMESTER 5
        $this->seedSemester5($studyProgram, $user, $unitSKSTM, $unitDU, $unitOK);

        // SEMESTER 6
        $this->seedSemester6($studyProgram, $user, $unitSKSTM, $unitDU, $unitOK);

        // SEMESTER 7
        $this->seedSemester7($studyProgram, $user, $unitSKSTM, $unitDU, $unitOK, $unitKEG);

    }

    private function seedSemester1($studyProgram, $user, $unitKEG, $unitSKSTM, $unitDU, $unitOK)
    {
        $semester = Semester::where('semester_code', 'SEM1')->first();
        if (!$semester) {
            $this->command->warn('Semester 1 tidak ditemukan, dilewati.');
            return;
        }

        $report = Report::create([
            'study_program_id' => $studyProgram->id,
            'semester_id' => $semester->id,
            'user_id' => $user->id,
            'grand_total' => 395670000,
        ]);

        $activities = [
            ['unit_id' => $unitKEG->id, 'activity_name' => 'Orientasi Fakultas/RSWS Kuliah MDUP/KIK', 'volume' => 10, 'unit_price' => 1800000, 'total' => 1800000, 'allocation' => 1, 'unit_cost' => 1800000],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Etika medik/kolegal (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Metode Penelitian (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Biostatistik & Komputer Statistik (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Epidemiologi Klinik & Kedokteran Berbasis Bukti (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Biologi Molekuler (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Dasar-dasar Fisika Radiasi (Fisika Medik,Radiologi Poteksi Padasi) (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Dasar-2 radiologi (rad.anatomi, teknik pemeriksaan radiologi [konvensional, CT, MRI, USG, nuklir]) (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Respirasi (I) (3 SKS x 16 TM)', 'volume' => 48.0, 'unit_price' => 350000, 'total' => 16800000, 'allocation' => 15, 'unit_cost' => 1120000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Kardiovaskuler (I) ( 3 SKS x 16 TM)', 'volume' => 48.0, 'unit_price' => 350000, 'total' => 16800000, 'allocation' => 15, 'unit_cost' => 1120000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Muskuloskeletal (I) ( 2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Urogenital (I) ( 2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Pembimbing Jurnal Reading ( Jurnal, Laporan Kasus, Referat ) ( 1 jam x 2 pembimbing x 3 divisi x 4 keg )', 'volume' => 24.0, 'unit_price' => 350000, 'total' => 8400000, 'allocation' => 1, 'unit_cost' => 8400000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Peer Slide Assessment (+ FK) (+ Rad Terapi )', 'volume' => 480.0, 'unit_price' => 350000, 'total' => 168000000, 'allocation' => 15, 'unit_cost' => 11200000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Morning report ( 1 jam x 2 hari x 24 minggu x 2 pembimbing )', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Small Group Discussion (1 jam x 2 hari x 24 minggu x 2 Pemb.)', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Anatomi Radiologi ( 1 jam x 2 dosen)', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitOK->id, 'activity_name' => 'Biaya Kontribusi Pendidikan', 'volume' => 24.0, 'unit_price' => 180000, 'total' => 4320000, 'allocation' => 1, 'unit_cost' => 4320000],
        ];

        $this->insertActivities($report->id, $activities);
    }

    private function seedSemester2($studyProgram, $user, $unitSKSTM, $unitDU, $unitOK)
    {
        $semester = Semester::where('semester_code', 'SEM2')->first();
        if (!$semester) {
            $this->command->warn('Semester 2 tidak ditemukan, dilewati.');
            return;
        }

        $report = Report::create([
            'study_program_id' => $studyProgram->id,
            'semester_id' => $semester->id,
            'user_id' => $user->id,
            'grand_total' => 353626667,
        ]);

        $activities = [
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Emergency (1) (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Respirasi (2) ( 2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Kardiovaskuler (2) ( 3 SKS x 16 TM)', 'volume' => 48.0, 'unit_price' => 350000, 'total' => 16800000, 'allocation' => 15, 'unit_cost' => 1120000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Muskuloskeletal (2) ( 3 SKS x 16 TM)', 'volume' => 48.0, 'unit_price' => 350000, 'total' => 16800000, 'allocation' => 15, 'unit_cost' => 1120000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Urogenital (2) ( 3 SKS x 16 TM)', 'volume' => 48.0, 'unit_price' => 350000, 'total' => 16800000, 'allocation' => 15, 'unit_cost' => 1120000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Gastrointestinal (1) ( 2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Pembimbingan dan pembacaan tugas ilmiah ( Jurnal, Laporan kasus, Referat) ( 1 jam x 2 pembimbing x 3 divisi x 4 keg )', 'volume' => 24.0, 'unit_price' => 350000, 'total' => 8400000, 'allocation' => 1, 'unit_cost' => 8400000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Foto side Teaching (2 jam x 2 pembimbing x 5 hari x 24 minggu)', 'volume' => 480.0, 'unit_price' => 350000, 'total' => 168000000, 'allocation' => 15, 'unit_cost' => 11200000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Morning report ( 1 jam x 2 hari x 24 minggu x 2 pembimbing )', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Small Group Discussion (1 jam x 2 hari x 24 minggu x 2 Pemb.)', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Pelatihan USG Dasar (abdomen, Small part(breast, thyroid) (2 kegiatan x 4 jam x 2 pembimbing)', 'volume' => 16.0, 'unit_price' => 350000, 'total' => 5600000, 'allocation' => 15, 'unit_cost' => 373333],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Workshop up date knowledge dan skill PPDS (2 x 4 jam x 2 dosen)', 'volume' => 16.0, 'unit_price' => 350000, 'total' => 5600000, 'allocation' => 15, 'unit_cost' => 373333],
            ['unit_id' => $unitOK->id, 'activity_name' => 'Biaya Kontribusi Pendidikan', 'volume' => 24.0, 'unit_price' => 180000, 'total' => 4320000, 'allocation' => 1, 'unit_cost' => 4320000],
        ];

        $this->insertActivities($report->id, $activities);
    }

    private function seedSemester3($studyProgram, $user, $unitSKSTM, $unitDU, $unitOK)
    {
        $semester = Semester::where('semester_code', 'SEM3')->first();
        if (!$semester) {
            $this->command->warn('Semester 3 tidak ditemukan, dilewati.');
            return;
        }

        $report = Report::create([
            'study_program_id' => $studyProgram->id,
            'semester_id' => $semester->id,
            'user_id' => $user->id,
            'grand_total' => 370770000,
        ]);

        $activities = [
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Emergency (2) (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Gastrointestinal (2) (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Respirasi (3) ( 3 SKS x 16 TM)', 'volume' => 48.0, 'unit_price' => 350000, 'total' => 16800000, 'allocation' => 15, 'unit_cost' => 1120000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Kardiovaskuler (3) ( 3 SKS x 16 TM)', 'volume' => 48.0, 'unit_price' => 350000, 'total' => 16800000, 'allocation' => 15, 'unit_cost' => 1120000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Muskuloskeletal (3) ( 2 SKS x 16 TM)', 'volume' => 48.0, 'unit_price' => 350000, 'total' => 16800000, 'allocation' => 15, 'unit_cost' => 1120000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Urogenital (3) ( 2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Pembimbingan dan pembacaan tugas ilmiah ( Jurnal, Laporan kasus, Referat) ( 4 jam x 2 pembimbing x 3 divisi )', 'volume' => 24.0, 'unit_price' => 350000, 'total' => 8400000, 'allocation' => 1, 'unit_cost' => 8400000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Morning report ( 1 jam x 2 hari x 24 minggu x 2 pembimbing )', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Small Group Discussion (1 jam x 2 hari x 24 minggu x 2 Pemb.)', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Foto side Teaching (2 jam x 2 pembimbing x 5 hari x 24 minggu)', 'volume' => 480.0, 'unit_price' => 350000, 'total' => 168000000, 'allocation' => 15, 'unit_cost' => 11200000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Naik Tingkat ke Madya ( 1 jam x 5 dosen penguji)', 'volume' => 10.0, 'unit_price' => 350000, 'total' => 3500000, 'allocation' => 1, 'unit_cost' => 3500000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Bimbingan Penelitian (9 Jam x 5 Pemb.)', 'volume' => 45.0, 'unit_price' => 350000, 'total' => 15750000, 'allocation' => 1, 'unit_cost' => 15750000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Seminar Usulan Penelitian (2 Jam x 5 Dosen Pembimbing)', 'volume' => 10.0, 'unit_price' => 350000, 'total' => 3500000, 'allocation' => 1, 'unit_cost' => 3500000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Workshop up date knowledge dan skill PPDS (2 x 4 jam x 2 dosen)', 'volume' => 16.0, 'unit_price' => 350000, 'total' => 5600000, 'allocation' => 15, 'unit_cost' => 373333],
            ['unit_id' => $unitOK->id, 'activity_name' => 'Biaya Kontribusi Pendidikan', 'volume' => 24.0, 'unit_price' => 180000, 'total' => 4320000, 'allocation' => 1, 'unit_cost' => 4320000],
        ];

        $this->insertActivities($report->id, $activities);
    }

    private function seedSemester4($studyProgram, $user, $unitSKSTM, $unitDU, $unitOK)
    {
        $semester = Semester::where('semester_code', 'SEM4')->first();
        if (!$semester) {
            $this->command->warn('Semester 4 tidak ditemukan, dilewati.');
            return;
        }

        $report = Report::create([
            'study_program_id' => $studyProgram->id,
            'semester_id' => $semester->id,
            'user_id' => $user->id,
            'grand_total' => 335420000,
        ]);

        $activities = [
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Gastrointestinal (3) ( 2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Neuroradiology (1) ( 3 SKS x 16 TM)', 'volume' => 48.0, 'unit_price' => 350000, 'total' => 16800000, 'allocation' => 15, 'unit_cost' => 1120000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Respirasi (4) ( 2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Muskuloskeletal (4) ( 2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Sistem Urogenital (4) ( 2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Tugas Mandiri', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Pembimbingan dan pembacaan tugas ilmiah ( Jurnal, Laporan kasus, Referat) ( 4 jam x 2 pembimbing x 3 divisi )', 'volume' => 24.0, 'unit_price' => 350000, 'total' => 8400000, 'allocation' => 1, 'unit_cost' => 8400000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Foto side Teaching (2 jam x 2 pembimbing x 5 hari x 24 minggu)', 'volume' => 480.0, 'unit_price' => 350000, 'total' => 168000000, 'allocation' => 15, 'unit_cost' => 11200000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Morning report ( 1 jam x 2 hari x 24 minggu x 2 pembimbing )', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Small Group Discussion (1 jam x 2 hari x 24 minggu x 2 Pemb.)', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Anatomi Radiologi II ( 2 x 3 dosen)', 'volume' => 6.0, 'unit_price' => 350000, 'total' => 2100000, 'allocation' => 15, 'unit_cost' => 140000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Bimbingan dan Presentasi Makalah Publikasi ilmiah Nasional/ International ( 3 jam x 3 pembimbing)', 'volume' => 9.0, 'unit_price' => 350000, 'total' => 3150000, 'allocation' => 1, 'unit_cost' => 3150000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Pelatihan USG Advanced (MSK dan pediatrik) (2 x 4 jam x 2 pembimbing)', 'volume' => 16.0, 'unit_price' => 350000, 'total' => 5600000, 'allocation' => 15, 'unit_cost' => 373333],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Workshop penulisan karya ilmiah (2 x 4 jam x 2 dosen)', 'volume' => 16.0, 'unit_price' => 350000, 'total' => 5600000, 'allocation' => 15, 'unit_cost' => 373333],
            ['unit_id' => $unitOK->id, 'activity_name' => 'Biaya Kontribusi Pendidikan', 'volume' => 24.0, 'unit_price' => 180000, 'total' => 4320000, 'allocation' => 1, 'unit_cost' => 4320000],
        ];

        $this->insertActivities($report->id, $activities);
    }

    private function seedSemester5($studyProgram, $user, $unitSKSTM, $unitDU, $unitOK)
    {
        $semester = Semester::where('semester_code', 'SEM5')->first();
        if (!$semester) {
            $this->command->warn('Semester 5 tidak ditemukan, dilewati.');
            return;
        }

        $report = Report::create([
            'study_program_id' => $studyProgram->id,
            'semester_id' => $semester->id,
            'user_id' => $user->id,
            'grand_total' => 340070000,
        ]);

        $activities = [
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Neuroradiology (2) (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Head & Neck (1) (3 SKS X 16 TM)', 'volume' => 48.0, 'unit_price' => 350000, 'total' => 16800000, 'allocation' => 15, 'unit_cost' => 1120000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Angiografi & Radiologi Intervensi (1) (2 SKS X 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi Anak (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radiologi forensik (1 SKS x 16 TM)', 'volume' => 16.0, 'unit_price' => 350000, 'total' => 5600000, 'allocation' => 15, 'unit_cost' => 373333],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Tugas Mandiri (2 SKS x 16 TM) Stase RS Rujukan', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitOK->id, 'activity_name' => 'Biaya Transportasi Stase Mandiri', 'volume' => 1.0, 'unit_price' => 5000000, 'total' => 5000000, 'allocation' => 1, 'unit_cost' => 5000000],
            ['unit_id' => $unitOK->id, 'activity_name' => 'Biaya Akomodasi Stase Mandiri', 'volume' => 1.0, 'unit_price' => 7000000, 'total' => 7000000, 'allocation' => 1, 'unit_cost' => 7000000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Pembimbingan dan pembacaan tugas ilmiah ( Jurnal, Laporan kasus, Referat) ( 4 jam x 2 pembimbing x 3 divisi )', 'volume' => 24.0, 'unit_price' => 350000, 'total' => 8400000, 'allocation' => 1, 'unit_cost' => 8400000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Foto side Teaching (2 jam x 2 pembimbing x 5 hari x 24 minggu)', 'volume' => 480.0, 'unit_price' => 350000, 'total' => 168000000, 'allocation' => 15, 'unit_cost' => 11200000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Morning report ( 1 jam x 2 hari x 24 minggu x 2 pembimbing )', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Small Group Discussion (1 jam x 2 hari x 24 minggu x 2 Pemb.)', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Naik Tingkat ke Madya ke senior ( 2 x 5 Dosen penguji)', 'volume' => 10.0, 'unit_price' => 350000, 'total' => 3500000, 'allocation' => 15, 'unit_cost' => 233333],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Pelatihan USG Advanced (Intervensi dan Doppler)USG (2 x 4 jam x 2 pembimbing)', 'volume' => 16.0, 'unit_price' => 350000, 'total' => 5600000, 'allocation' => 15, 'unit_cost' => 373333],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Workshop up date knowledge dan skill PPDS (2 x 4 jam x 2 dosen)', 'volume' => 16.0, 'unit_price' => 350000, 'total' => 5600000, 'allocation' => 15, 'unit_cost' => 373333],
            ['unit_id' => $unitOK->id, 'activity_name' => 'Biaya Kontribusi Pendidikan', 'volume' => 24.0, 'unit_price' => 180000, 'total' => 4320000, 'allocation' => 1, 'unit_cost' => 4320000],
        ];

        $this->insertActivities($report->id, $activities);
    }

    private function seedSemester6($studyProgram, $user, $unitSKSTM, $unitDU, $unitOK)
    {
        $semester = Semester::where('semester_code', 'SEM6')->first();
        if (!$semester) {
            $this->command->warn('Semester 6 tidak ditemukan, dilewati.');
            return;
        }

        $report = Report::create([
            'study_program_id' => $studyProgram->id,
            'semester_id' => $semester->id,
            'user_id' => $user->id,
            'grand_total' => 344703335,
        ]);

        $activities = [
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Neuroradiology (3) (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Obstetri & Gynecology (1)(2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Breast Imaging (3 SKS X 16 TM)', 'volume' => 48.0, 'unit_price' => 350000, 'total' => 16800000, 'allocation' => 15, 'unit_cost' => 1120000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Radioterapi(2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Angiografi & Radiologi Intervensi (2) (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Kedokteran Nuklir/mamografi ( 1SKS x 16 TM)', 'volume' => 16.0, 'unit_price' => 350000, 'total' => 5600000, 'allocation' => 15, 'unit_cost' => 373333],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Tugas Mandiri (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitOK->id, 'activity_name' => 'Biaya Transportasi Stase', 'volume' => 1.0, 'unit_price' => 3500000, 'total' => 3500000, 'allocation' => 1, 'unit_cost' => 3500000],
            ['unit_id' => $unitOK->id, 'activity_name' => 'Biaya Akomodasi Stase', 'volume' => 1.0, 'unit_price' => 5000000, 'total' => 5000000, 'allocation' => 1, 'unit_cost' => 5000000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Pembimbingan dan pembacaan tugas ilmiah ( Jurnal, Laporan kasus, Referat) ( 4 jam x 2 pembimbing x 3 divisi )', 'volume' => 24.0, 'unit_price' => 350000, 'total' => 8400000, 'allocation' => 1, 'unit_cost' => 8400000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Foto side Teaching (2 jam x 2 pembimbing x 5 hari x 24 minggu)', 'volume' => 480.0, 'unit_price' => 350000, 'total' => 168000000, 'allocation' => 15, 'unit_cost' => 11200000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Morning report ( 1 jam x 2 hari x 2 pembimbing x 24 minggu)', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Small Group Discussion (1 jam x 2 hari x 24 minggu x 2 Pemb.)', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Workshop up date knowledge dan skill PPDS (2 x 4 jam x 2 dosen)', 'volume' => 16.0, 'unit_price' => 350000, 'total' => 5600000, 'allocation' => 15, 'unit_cost' => 373333],
            ['unit_id' => $unitOK->id, 'activity_name' => 'Biaya Kontribusi Pendidikan', 'volume' => 24.0, 'unit_price' => 180000, 'total' => 4320000, 'allocation' => 1, 'unit_cost' => 4320000],
        ];

        $this->insertActivities($report->id, $activities);
    }

    private function seedSemester7($studyProgram, $user, $unitSKSTM, $unitDU, $unitOK, $unitKEG)
    {
        $semester = Semester::where('semester_code', 'SEM7')->first();
        if (!$semester) {
            $this->command->warn('Semester 7 tidak ditemukan, dilewati.');
            return;
        }

        $report = Report::create([
            'study_program_id' => $studyProgram->id,
            'semester_id' => $semester->id,
            'user_id' => $user->id,
            'grand_total' => 378250000,
        ]);

        $activities = [
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Obstetri & Gynecology (2)(2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Neuroradiology (4) (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lisan Divisi ( 3 jam x 1 pengujil )', 'volume' => 3.0, 'unit_price' => 350000, 'total' => 1050000, 'allocation' => 15, 'unit_cost' => 70000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Semester CBIT ( 1 jam x 2 dosen)', 'volume' => 2.0, 'unit_price' => 350000, 'total' => 700000, 'allocation' => 15, 'unit_cost' => 46667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Head & Neck (2) (2 SKS x 16 TM)', 'volume' => 32.0, 'unit_price' => 350000, 'total' => 11200000, 'allocation' => 15, 'unit_cost' => 746667],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Karya Akhir (Tesis) ( 6 SKS X 16 TM)', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Bimbingan Penelitian (9 jam x 5 Pemb.)', 'volume' => 45.0, 'unit_price' => 350000, 'total' => 15750000, 'allocation' => 1, 'unit_cost' => 15750000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Tutup (Tesis) Penelitian (2 jam x 5 Pemb.)', 'volume' => 10.0, 'unit_price' => 350000, 'total' => 3500000, 'allocation' => 1, 'unit_cost' => 3500000],
            ['unit_id' => $unitSKSTM->id, 'activity_name' => 'Sari Pustaka(2 SKS x 16 TM)', 'volume' => 36.0, 'unit_price' => 350000, 'total' => 12600000, 'allocation' => 15, 'unit_cost' => 840000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Pembimbingan dan pembacaan tugas ilmiah ( Jurnal, Laporan kasus, Referat) ( 4 jam x 2 pembimbing x 3 divisi )', 'volume' => 24.0, 'unit_price' => 350000, 'total' => 8400000, 'allocation' => 1, 'unit_cost' => 8400000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Foto side Teaching (2 jam x 2 pembimbing x 5 hari x 24 minggu)', 'volume' => 480.0, 'unit_price' => 350000, 'total' => 168000000, 'allocation' => 15, 'unit_cost' => 11200000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Morning report ( 1 jam x 2 hari x 24 minggu x 2 pembimbing )', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Small Group Discussion (1 jam x 2 hari x 24 minggu x 2 Pemb.)', 'volume' => 96.0, 'unit_price' => 350000, 'total' => 33600000, 'allocation' => 15, 'unit_cost' => 2240000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Ujian Lokal Institusi CBIT dan Lisan ( 5 jam x 10 penguji)', 'volume' => 50.0, 'unit_price' => 350000, 'total' => 17500000, 'allocation' => 1, 'unit_cost' => 17500000],
            ['unit_id' => $unitKEG->id, 'activity_name' => 'Ujian Nasional Kolegium', 'volume' => 1.0, 'unit_price' => 9000000, 'total' => 9000000, 'allocation' => 1, 'unit_cost' => 9000000],
            ['unit_id' => $unitDU->id, 'activity_name' => 'Workshop up date knowledge dan skill PPDS (2 x 4 jam x 2 dosen)', 'volume' => 16.0, 'unit_price' => 350000, 'total' => 5600000, 'allocation' => 15, 'unit_cost' => 373333],
        ];

        $this->insertActivities($report->id, $activities);
    }

    private function insertActivities($reportId, $activities)
    {
        foreach ($activities as $activity) {
            ActivityDetail::create([
                'report_id' => $reportId,
                'unit_id' => $activity['unit_id'],
                'activity_name' => $activity['activity_name'],
                'calculation_type' => 'manual',
                'volume' => $activity['volume'],
                'unit_price' => $activity['unit_price'],
                'total' => $activity['total'],
                'allocation' => $activity['allocation'],
                'unit_cost' => $activity['unit_cost'],
                'notes' => '',
            ]);
        }
    }
}
