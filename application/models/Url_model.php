<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Url_Model extends CI_Model {
    /**
     * Get About
     * ----------------------------------
     */
    public function read_url() {
      $this->db->select('*');
      $this->db->from('url');
      $this->db->where('type','images_url');
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
