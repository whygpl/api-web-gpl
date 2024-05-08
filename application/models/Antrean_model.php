<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Antrean_Model extends CI_Model {
    protected $antrean_table = 'bpjs_jkn_antrean';
    protected $bride_table = 'bpjs_jkn_booking_sh_md';

    /**
     * Add a new Article
     * @param: {array} Article Data
     */
    public function create_antrean(array $data) {
        $this->db->insert($this->antrean_table, $data);
        return $this->db->insert_id();
    }

    public function create_bride(array $data) {
        $this->db->insert($this->bride_table, $data);
        return $this->db->insert_id();
    }
    
    public function create_pasien(array $data) {
        $db_sirs = $this->load->database('sirs', true);
        $db_sirs->insert("sh_mst_pasien", $data);
        return $db_sirs->insert_id();
    }

    public function get_rekap_antrean($tanggalperiksa = 0, $kodepoli = 0, $polieksekutif = 0) {
        $this->db->select('COUNT(bpjs_jkn_antrean.kodebooking) as totalantrean');
        $this->db->where('bpjs_jkn_antrean.tanggalperiksa',$tanggalperiksa);
        $this->db->where('bpjs_jkn_antrean.kodepoli',$kodepoli);
        $this->db->where('bpjs_jkn_antrean.polieksekutif',$polieksekutif);
        $this->db->join('bpjs_jkn_booking_sh_md','bpjs_jkn_antrean.kodebooking = bpjs_jkn_booking_sh_md.kodebooking','LEFT');
        $query = $this->db->get('bpjs_jkn_antrean');
        return  $query->result_array();
    }

    public function get_rekap_terlayani($tanggalperiksa = 0, $kodepoli = 0, $polieksekutif = 0) {
        $this->db->select('COUNT(bpjs_jkn_booking_sh_md.is_registered) as terlayani');
        $this->db->where('bpjs_jkn_antrean.tanggalperiksa',$tanggalperiksa);
        $this->db->where('bpjs_jkn_antrean.kodepoli',$kodepoli);
        $this->db->where('bpjs_jkn_antrean.polieksekutif',$polieksekutif);
        $this->db->where('bpjs_jkn_booking_sh_md.is_registered',1);
        $this->db->join('bpjs_jkn_booking_sh_md','bpjs_jkn_antrean.kodebooking = bpjs_jkn_booking_sh_md.kodebooking','LEFT');
        $query = $this->db->get('bpjs_jkn_antrean');
        return  $query->result_array();
    }

    public function get_rekap_lastupdate($tanggalperiksa = 0, $kodepoli = 0, $polieksekutif = 0) {
        $this->db->select('MAX(bpjs_jkn_antrean.date_added) as lastupdate, bpjs_jkn_ref_poli.nm_poli_bpjs');
        $this->db->where('bpjs_jkn_antrean.tanggalperiksa',$tanggalperiksa);
        $this->db->where('bpjs_jkn_antrean.kodepoli',$kodepoli);
        $this->db->where('bpjs_jkn_antrean.polieksekutif',$polieksekutif);
        $this->db->join('bpjs_jkn_booking_sh_md','bpjs_jkn_antrean.kodebooking = bpjs_jkn_booking_sh_md.kodebooking','LEFT');
        $this->db->join('bpjs_jkn_ref_poli','bpjs_jkn_booking_sh_md.kd_poli_bpjs = bpjs_jkn_ref_poli.kd_poli_bpjs','LEFT');
        $query = $this->db->get('bpjs_jkn_antrean');
        return  $query->result_array();
    }

    public function check_no_rm_bynomorkartu_bride($no_peserta = 0) {
        $db_sirs = $this->load->database('sirs', true);
        $db_sirs->select("a.no_rm");
        $db_sirs->where("a.no_peserta","$no_peserta");
        $db_sirs->from("sh_mst_pasien as a");
        $data = $db_sirs->get()->result();
        return $data;
    }

    public function check_no_rm_bynik_bride($nik = 0) {
        $db_sirs = $this->load->database('sirs', true);
        $db_sirs->select("a.no_rm");
        $db_sirs->where("a.no_ktp","$nik");
        $db_sirs->from("sh_mst_pasien as a");
        $data = $db_sirs->get()->result();
        return $data;
    }

    public function gen_no_rm_bride() {
        $db_sirs = $this->load->database('sirs', true);
        $db_sirs->select('RIGHT(sh_mst_pasien.no_rm,8) as kode', FALSE);   
		$db_sirs->order_by('no_rm','DESC');   
		$db_sirs->limit(1);  
	  	$query = $db_sirs->get('sh_mst_pasien');  
	  	//cek dulu apakah ada sudah ada kode di tabel.   
	  	if($query->num_rows() <> 0){  
	   	//jika kode ternyata sudah ada.    
	  		$data = $query->row();    
	  		$kode = intval($data->kode) + 1;  
	  	}else{  
	   	//jika kode belum ada    
	  	$kode = 1;  
	  	}   
		$kodemax = str_pad($kode, 8, "0", STR_PAD_LEFT);  
		$kodejadi = $kodemax;  
	  	return $kodejadi;
    }

    function gen_antrean($kodepoli = 0,$kodedokter = 0,$id_trx_schedule_item = 0)  {   
        $today = date("Y-m-d");
        $this->db->select("MAX(bpjs_jkn_booking_sh_md.nomorantrean) as kode", FALSE);
        $this->db->where("bpjs_jkn_booking_sh_md.kodedokter","$kodedokter");
        $this->db->where("bpjs_jkn_booking_sh_md.kd_poli_bpjs","$kodepoli");
        $this->db->where("bpjs_jkn_booking_sh_md.id_trx_schedule_item","$id_trx_schedule_item");
        $this->db->limit(1);  
        $query = $this->db->get('bpjs_jkn_booking_sh_md');  
        $data = $query->row();    
        $noOrder = $data->kode;
        $noUrut = (int) substr($noOrder, 3, 3);
        $noUrut++;
        $id_Order = sprintf("%03s", $noUrut);
        return $id_Order;
    }

    function gen_booking($kodepoli = 0)  {   
        $today = date("Y-m-d");
        $this->db->select("MAX(bpjs_jkn_booking_sh_md.kodebooking) as kode", FALSE);
        $this->db->like("bpjs_jkn_booking_sh_md.date_booking","$today");
        $this->db->limit(1);  
        $query = $this->db->get('bpjs_jkn_booking_sh_md');  
        $data = $query->row();    
        $noOrder = $data->kode;
        $noUrut = (int) substr($noOrder, 8, 3);
        $noUrut++;
        $id_Order = "BRP".date("md").sprintf("%04s", $noUrut);
        return $id_Order;
    }

    function get_nm_poli($kodepoli = 0)  {   
        $this->db->select("a.nm_poli_bpjs");
        $this->db->where("a.kd_poli_bpjs","$kodepoli");
        $this->db->from("bpjs_jkn_ref_poli as a");
        $data = $this->db->get()->result();    
        // $noOrder = $data->nm_poli_bpjs;
        return $data[0]->nm_poli_bpjs;
    }

    function checkreferensi($nomorreferensi = 0)  {   
        $this->db->select("a.nomorreferensi");
        $this->db->where("a.nomorreferensi","$nomorreferensi");
        $this->db->from("bpjs_jkn_antrean as a");
        $data = $this->db->get()->result();    
        // $noOrder = $data->nm_poli_bpjs;
        return $data[0];
    }

    function checkreferensi_nik_day_poli($nik = 0, $day = 0, $poli = 0, $dokter = 0)  {   
        $this->db->select("a.nik");
        $this->db->where("a.nik","$nik");
        $this->db->where("a.tanggalperiksa","$day");
        $this->db->where("a.kodepoli","$poli");
        $this->db->where("a.kodedokter","$dokter");
        $this->db->from("bpjs_jkn_antrean as a");
        $data = $this->db->get()->result();    
        // $noOrder = $data->nm_poli_bpjs;
        return $data[0];
    }

    function get_jadwal_poli($kodepoli = 0)  {   
        $this->db->select("*");
        $this->db->where("a.kd_poli_bpjs","$kodepoli");
        $this->db->from("bpjs_jkn_ref_poli as a");
        $data = $this->db->get()->result();    
        // $noOrder = $data->nm_poli_bpjs;
        return $data[0];
    }

    function get_jadwal_dokter_poli($poli = 0,$day = 0,$dokter = 0)  {   
        $db_sirs = $this->load->database('sirs', true);
        // $db_sirs->insert("sh_mst_pasien", $data);
        // return $db_sirs->insert_id();

        $db_sirs->select("sm_schedule_doctor_item.*, sm_mst_pegawai.nm_mst_pegawai_lengkap,a.kodeantrean");
        $db_sirs->where("sm_trx_pegawai_jabatan.id_mst_unit_kerja_sub","$poli");
        $db_sirs->where("sm_mst_pegawai.id_bpjs_vclaim","$dokter");
        $db_sirs->where("sm_schedule_doctor_item.day","$day");
        $db_sirs->where("sm_schedule_doctor_item.quota_all != 0");
        $db_sirs->where("sm_schedule_doctor_item.quota_jkn != 0");
        $db_sirs->from("sm_schedule_doctor as a");
        $db_sirs->join('sm_schedule_doctor_item','a.id_trx_schedule = sm_schedule_doctor_item.id_trx_schedule','LEFT');
        $db_sirs->join('sm_trx_pegawai_jabatan','a.id_mst_pegawai = sm_trx_pegawai_jabatan.id_mst_pegawai','LEFT');
        $db_sirs->join('sm_mst_pegawai','a.id_mst_pegawai = sm_mst_pegawai.id_mst_pegawai','LEFT');
        $data = $db_sirs->get()->result();    
        // $noOrder = $data->nm_poli_bpjs;
        return $data[0];
    }

    function get_quota_by_id_trx_schedule_item($id_trx_schedule_item = 0)  {   
        $this->db->select("a.nomorreferensi");
        $this->db->where("a.id_trx_schedule_item","$id_trx_schedule_item");
        $this->db->from("bpjs_jkn_antrean as a");
        $data = $this->db->get()->result();    
        // $noOrder = $data->nm_poli_bpjs;
        return $data[0];
    }
        

    function get_dokter($dokter = 0)  {   
        $db_sirs = $this->load->database('sirs', true);
        // $db_sirs->insert("sh_mst_pasien", $data);
        // return $db_sirs->insert_id();

        $db_sirs->select("a.nm_mst_pegawai_lengkap");
        $db_sirs->where("a.id_bpjs_vclaim","$dokter");
        $db_sirs->from("sm_mst_pegawai as a");
        $data = $db_sirs->get()->result();    
        // $noOrder = $data->nm_poli_bpjs;
        return $data[0];
    }

    /**
     * Delete an Article
     * @param: {array} Article Data
     */
    // public function delete_article(array $data) {
    //     /**
    //      * Check Article exist with article_id and user_id
    //      */
    //     $query = $this->db->get_where($this->article_table, $data);
    //     if ($this->db->affected_rows() > 0) {
            
    //         // Delete Article
    //         $this->db->delete($this->article_table, $data);
    //         if ($this->db->affected_rows() > 0) {
    //             return true;
    //         }
    //         return false;
    //     }   
    //     return false;
    // }

    // /**
    //  * Update an Article
    //  * @param: {array} Article Data
    //  */
    // public function update_article(array $data) {
    //     /**
    //      * Check Article exist with article_id and user_id
    //      */
    //     $query = $this->db->get_where($this->article_table, [
    //         'user_id' => $data['user_id'],
    //         'id' => $data['id'],
    //     ]);

    //     if ($this->db->affected_rows() > 0) {
            
    //         // Update an Article
    //         $update_data = [
    //             'title' =>  $data['title'],
    //             'description' =>  $data['description'],
    //             'updated_at' => time(),
    //         ];

    //         return $this->db->update($this->article_table, $update_data, ['id' => $query->row('id')]);
    //     }   
    //     return false;
    // }
}