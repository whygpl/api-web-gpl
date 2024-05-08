<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Product_Model extends CI_Model {
    /**
     * Get About
     * ----------------------------------
     */
    public function read_type($q_strings) {
      $this->db->select('*');
      $this->db->from('product_types');
      if ($q_strings != NULL) {
        $this->db->where($q_strings);
      }
      return $this->db->get()->result();
    }

    /**
     * Get About Company Profile
     * ----------------------------------
     */
    public function read_category($q_strings) {
      $this->db->select('*');
      $this->db->from('product_categorys');
      if ($q_strings != NULL) {
        $this->db->where($q_strings);
      }
      $this->db->where('is_delete','0');
      return $this->db->get()->result();
    }

    /**
     * Get About Company Profile
     * ----------------------------------
     */
    public function read_group($q_strings) {
      $this->db->select('*');
      $this->db->from('product_groups');
      if ($q_strings != NULL) {
        $this->db->where($q_strings);
      }
      $this->db->where('is_delete','0');
      return $this->db->get()->result();
    }

    /**
     * Get About Company Profile
     * ----------------------------------
     */
    public function read_product($q_strings) {
      $this->db->select('*');
      $this->db->from('products');
      // $this->db->join('product_groups pg','a.product_group_id = pg.id AND pg.status = "live"','LEFT');
      // $this->db->join('product_categorys pc','pg.product_category_id = pc.id AND pc.status = "live"','LEFT');
      if ($q_strings != NULL) {
        $this->db->where($q_strings);
      }
      $this->db->where('products.is_delete','0');
      return $this->db->get()->result();
    }

    /**
     * Get About Company Profile
     * ----------------------------------
     */
    public function read_product_filter($q_strings,$status) {
      $this->db->select('products.*');
      $this->db->from('products');
      // $this->db->join('product_groups pg','a.product_group_id = pg.id AND pg.status = "live"','LEFT');
      $this->db->join('product_categorys pc','products.product_category_id = pc.id AND pc.status = "live"','LEFT');
      if ($q_strings != 'drug') {
        $this->db->where('pc.product_type_id','1');
      } else {
        $this->db->where('pc.product_type_id','2');
      }
      $this->db->where('products.is_delete','0');
      $this->db->where('products.status',$status);
      return $this->db->get()->result();
    }

    /**
     * Get About Company Profile
     * ----------------------------------
     */
    public function read_product_group($id) {
      $this->db->select('*');
      $this->db->from('product_groups');
      if ($id != NULL) {
        $this->db->where('id',$id);
      }
      // $this->db->where('status','live');
      return $this->db->get()->result();
    }

    /**
     * Get About Company Profile
     * ----------------------------------
     */
    public function read_product_category($id) {
      $this->db->select('product_categorys.*,pt.id as type_id,pt.title as type_title,pt.title_en as type_title_en');
      $this->db->from('product_categorys');
      $this->db->join('product_types pt','product_categorys.product_type_id = pt.id AND pt.status = "live"','LEFT');
      if ($id != NULL) {
        $this->db->where('product_categorys.id',$id);
      }
      // $this->db->where('status','live');
      return $this->db->get()->result();
    }

    /**
     * Get About Company Profile
     * ----------------------------------
     */
    public function read_product_htu($product_id) {
      $this->db->select('*');
      $this->db->from('product_htu');
      $this->db->where('product_id',$product_id);
      $this->db->where('status','live');
      $this->db->order_by('id', 'ASC');
      return $this->db->get()->result();
    }

    /**
     * Get About Company Profile
     * ----------------------------------
     */
    public function read_product_netto($product_id) {
      $this->db->select('*');
      $this->db->from('product_netto');
      if ($product_id != NULL) {
        $this->db->where('product_id',$product_id);
      }
      // $this->db->where('status','live');
      return $this->db->get()->result();
    }

    /**
     * Get About Company Profile
     * ----------------------------------
     */
    public function read_netto($product_id) {
      $this->db->select('*');
      $this->db->from('product_netto');
      if ($product_id != NULL) {
        $this->db->where('product_id',$product_id);
      }
      // $this->db->where('status','live');
      return $this->db->get()->result();
    }

    /**
     * Get Product Image Homes
     * ----------------------------------
     */
    public function read_product_image($product_id,$status) {
		$this->db->select('a.*,m.image_url,m.imgvid');
		$this->db->from('products as a');
		$this->db->join('media m','a.id = m.product_detail_id','LEFT');
		$this->db->where('a.status',$status);
		$this->db->where('m.status',$status);
		$this->db->where('m.product_detail_id',$product_id);
		$this->db->where('m.page_id',3);
		$this->db->where('m.type','product_detail');
		return $this->db->get()->result();
    }

    /**
     * Get About Company Historie
     * ----------------------------------
     */
    public function read_about_company_historie() {
      $this->db->select('*');
      $this->db->from('about_company_histories');
      return $this->db->get()->result();
    }

    /**
     * Get About Company Value
     * ----------------------------------
     */
    public function read_about_company_value() {
      $this->db->select('*');
      $this->db->from('about_company_values');
      return $this->db->get()->result();
    }

    /**
     * Get About Greeting
     * ----------------------------------
     */
    public function read_about_greeting() {
      $this->db->select('*');
      $this->db->from('about_greeting_presdirs');
      return $this->db->get()->result();
    }

    /**
     * Get About Vision
     * ----------------------------------
     */
    public function read_about_vision() {
      $this->db->select('*');
      $this->db->from('about_visions');
      return $this->db->get()->result();
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
