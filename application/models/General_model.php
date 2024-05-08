<?php defined('BASEPATH') OR exit('No direct script access allowed');
class General_Model extends CI_Model {
    protected $user_table = 'users';
    /**
     * Get Footer
     * ----------------------------------
     */
    public function read_footer($q_strings) {
      $this->db->select('*');
      $this->db->from('footers');
      if ($q_strings != NULL) {
        $this->db->where($q_strings);
      }
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
