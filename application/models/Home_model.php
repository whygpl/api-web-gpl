<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Home_Model extends CI_Model {
    protected $user_table = 'users';
    /**
     * Get Hero Homes
     * ----------------------------------
     */
    public function read_hero($q_strings) {
		$this->db->select('*');
		$this->db->from('hero_homes');
        if ($q_strings != NULL) {
          $this->db->where($q_strings);
        }
		return $this->db->get()->result();
    }

    /**
     * Get About Homes
     * ----------------------------------
     */
    public function read_about($q_strings) {
		$this->db->select('*');
		$this->db->from('abouts');
		$this->db->where('page_id',1);
        if ($q_strings != NULL) {
          $this->db->where($q_strings);
        }
		return $this->db->get()->result()[0];
    }

    /**
     * Get About Image Homes
     * ----------------------------------
     */
    public function read_about_image() {
		$this->db->select('a.*,m.image_url');
		$this->db->from('abouts as a');
		$this->db->join('media m','a.page_id = m.page_id','LEFT');
		$this->db->where('a.page_id',1);
		$this->db->where('a.status','live');
		$this->db->where('m.type','about');
		return $this->db->get()->result();
    }

    /**
     * Get About Image Homes
     * ----------------------------------
     */
    public function read_about_image_filter($q_strings) {
		$this->db->select('a.*,m.image_url');
		$this->db->from('abouts as a');
		$this->db->join('media m','a.page_id = m.page_id','LEFT');
		$this->db->where('a.page_id',1);
        if ($q_strings['status'] != NULL) {
          $this->db->where('m.status',$q_strings['status']);
          $this->db->where('a.status',$q_strings['status']);
        }
		$this->db->where('m.type','about');
		return $this->db->get()->result();
    }

    /**
     * Get Product Homes
     * ----------------------------------
     */
    public function read_product($q_strings) {
		$this->db->select('*');
		$this->db->from('home_products');
        if ($q_strings != NULL) {
          $this->db->where($q_strings);
        }
		return $this->db->get()->result();
    }

    /**
     * Get Qualitie Homes
     * ----------------------------------
     */
    public function read_qualitie() {
		$this->db->select('*');
		$this->db->from('home_qualities');
		$this->db->where('home_qualities.status','live');
		return $this->db->get()->result();
    }

    /**
     * Get Award Homes
     * ----------------------------------
     */
    public function read_award($q_strings) {
		$this->db->select('*');
		$this->db->from('media');
		$this->db->where('page_id',1);
        if ($q_strings != NULL) {
          $this->db->where($q_strings);
        }
		$this->db->where('type','award');
		return $this->db->get()->result();
    }

    /**
     * Get Footer
     * ----------------------------------
     */
    public function read_footer() {
		$this->db->select('*');
		$this->db->from('footers');
		$this->db->where('footers.status','live');
		return $this->db->get()->result()[0];
    }

    /**
     * Get Icon
     * ----------------------------------
     */
    public function read_icon() {
		$this->db->select('*');
		$this->db->from('media');
		$this->db->where_in('media.type',array('footer','navbar'));
		return $this->db->get()->result();
    }

    // /**
    //  * Get Qualitie Homes
    //  * ----------------------------------
    //  */
    // public function read_qualitie() {
	// 	$this->db->select('*');
	// 	$this->db->from('home_qualities');
	// 	return $this->db->get()->result();
    // }
}
