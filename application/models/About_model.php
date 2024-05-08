<?php defined('BASEPATH') OR exit('No direct script access allowed');
class About_Model extends CI_Model {
  /**
   * Get About Image Homes
   * ----------------------------------
   */
  public function read_about_image($q_strings,$type) {
    $this->db->select('*');
    $this->db->from('media');
    // $this->db->where('page_id',1);
    // $this->db->where('status','live');
    if ($q_strings != NULL) {
      $this->db->where($q_strings);
    }
    $this->db->where('type',$type);
    return $this->db->get()->result();
  }
  /**
   * Get About
   * ----------------------------------
   */
  public function read_about($q_strings) {
    $this->db->select('*');
    $this->db->from('abouts');
    $this->db->where('page_id',2);
    if ($q_strings != NULL) {
      $this->db->where($q_strings);
    }
    return $this->db->get()->result()[0];
  }

  /**
   * Get About Company Profile
   * ----------------------------------
   */
  public function read_about_company_profile($q_strings) {
    $this->db->select('*');
    $this->db->from('about_company_profiles');
    if ($q_strings != NULL) {
      $this->db->where($q_strings);
    }
    return $this->db->get()->result()[0];
  }

  /**
   * Get About Company Historie
   * ----------------------------------
   */
  public function read_about_company_historie($q_strings) {
    $this->db->select('*');
    $this->db->from('about_company_histories');
    if ($q_strings != NULL) {
      $this->db->where($q_strings);
    }
    return $this->db->get()->result()[0];
  }

  /**
   * Get About Company Value
   * ----------------------------------
   */
  public function read_about_company_value($q_strings) {
    $this->db->select('*');
    $this->db->from('about_company_values');
    if ($q_strings != NULL) {
      $this->db->where($q_strings);
    }
    return $this->db->get()->result()[0];
  }

  /**
   * Get About Company Certification
   * ----------------------------------
   */
  public function read_about_certification($q_strings) {
    $this->db->select('*');
    $this->db->from('about_certifications');
    if ($q_strings != NULL) {
      $this->db->where($q_strings);
    }
    return $this->db->get()->result()[0];
  }

  /**
   * Get About Company Award
   * ----------------------------------
   */
  public function read_about_award($q_strings) {
    $this->db->select('*');
    $this->db->from('about_awards');
    if ($q_strings != NULL) {
      $this->db->where($q_strings);
    }
    return $this->db->get()->result()[0];
  }

  /**
   * Get About Greeting
   * ----------------------------------
   */
  public function read_about_greeting($q_strings) {
    $this->db->select('*');
    $this->db->from('about_greeting_presdirs');
    if ($q_strings != NULL) {
      $this->db->where($q_strings);
    }
    return $this->db->get()->result()[0];
  }

  /**
   * Get About Vision
   * ----------------------------------
   */
  public function read_about_mission($q_strings) {
    $this->db->select('*');
    $this->db->from('about_missions');
    if ($q_strings != NULL) {
      $this->db->where($q_strings);
    }
    return $this->db->get()->result()[0];
  }

  /**
   * Get About Vision
   * ----------------------------------
   */
  public function read_about_vision($q_strings) {
    $this->db->select('*');
    $this->db->from('about_visions');
    if ($q_strings != NULL) {
      $this->db->where($q_strings);
    }
    return $this->db->get()->result()[0];
  }

  /**
   * Get About Vision
   * ----------------------------------
   */
  public function read_about_office($q_strings) {
    $this->db->select('*');
    $this->db->from('about_office');
    if ($q_strings != NULL) {
      $this->db->where($q_strings);
    }
    return $this->db->get()->result()[0];
  }

  /**
   * Get About Vision
   * ----------------------------------
   */
  public function read_about_factory($q_strings) {
    $this->db->select('*');
    $this->db->from('about_factory');
    if ($q_strings != NULL) {
      $this->db->where($q_strings);
    }
    return $this->db->get()->result()[0];
  }

  /**
   * Get About Us Tab
   * ----------------------------------
   */
  public function read_about_us_tab() {
    $this->db->select('*');
    $this->db->from('about_us_tab_sub_menus');
    return $this->db->get()->result();
  }
}
