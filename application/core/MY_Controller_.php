<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    
    public $data;
	public $basename;
	public $directory;
	public $tablename;
	public $name;
	public $title;
	public $method;
	public $layout = 'layout';
	public $menu = array();
	// public $listmenu = array();
    
	protected $publicAction = array('login', 'denied', 'logout');
    protected $allows = array();
	protected $user = array();
    
	public function __construct() {
		parent::__construct();
		
		$this->data['page'] = 'home';
		if (empty($this->name)) {
			$this->name = get_class($this);
		} 
		
		$this->directory = $this->router->directory;
		$this->basename = strtolower(get_class($this));
		$this->set('basename', strtolower(get_class($this)));
		$this->set('name', strtolower($this->name));
		$this->set('tablename', strtolower($this->tablename));
		$this->set('directory', strtolower($this->directory));
		$this->set('grid_name', strtolower($this->name));
		$this->set('method', strtolower($this->method));
		/* Versi 2.0 (2)*/
		//set user
		$this->user = (array) $this->session->userdata('userLogin');
		if ($this->session->userdata('isLogin')) {
			$this->set('user', $this->user);	
			$this->nmsatker = $satker;		 		//sesi2 
			
		}
		
		date_default_timezone_set('Asia/Jakarta');
	}
		
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function get($key) {
      if (array_key_exists($key, $this->data))
         return $this->data[$key];
      else 
         return false;
	}
	
	public function loadElement($element, $data=null) {
		if (is_array($data) && !empty($data)) {
			$this->data = array_merge((array)$this->data, (array)$data);
		} 
      return $this->load->view($element, $this->data, true);
	}
	
	public function render($view, $data=null) {
		if (is_array($data) && !empty($data)) {
			$this->data = array_merge((array)$this->data, (array)$data);
		} 
		
		$this->data['title'] = $this->title;
		$this->data['flashMessage'] = $this->session->flashdata('flashMessage');		
        $this->data['layout_content'] = $this->load->view("{$this->directory}{$this->name}/{$view}", $this->data, true);
		
		if ($this->input->is_ajax_request()) {
			$format = $this->input->get_post('format');
			if (!empty($format) && $format == 'json') {
				echo json_encode(array('html' => $this->data['layout_content']));
			} else {
				echo $this->data['layout_content'];
			}
			return;
		}
		$this->load->view($this->layout, $this->data); 
	}
	
	public function setFlash($title, $message, $class='alert-warning') {
		$out = '<a class="close" data-dismiss="alert" href="#">&times;</a>';
		$this->session->set_flashdata('flashMessage', "<div class=\"alert {$class} alert-dismissible\" role=\"alert\" >{$out}\n <strong>{$title}</strong> {$message}</div>");		
	}
	
	public function get_notification($f = 'notif') {
		/* $d = array(
			'notif' => array(
				array(
					'title' => 'New user registered.',
					'detail' => 'label-success',
					'icon' => 'fa fa-plus',
					'url' => site_url(),
					'time' => 'Just Now',
				),
				array(
					'title' => 'Server #12 overloaded.',
					'detail' => 'label-danger',
					'icon' => 'fa fa-bolt',
					'url' => site_url(),
					'time' => '15 mins',
				),
				array(
					'title' => 'Server #2 not responding.',
					'detail' => 'label-warning',
					'icon' => 'fa fa-bell-o',
					'url' => site_url(),
					'time' => '22 mins',
				),
				array(
					'title' => 'Application error.',
					'detail' => 'label-info',
					'icon' => 'fa fa-bullhorn',
					'url' => site_url(),
					'time' => '40 mins',
				),
				array(
					'title' => 'Database overloaded 68%.',
					'detail' => 'label-danger',
					'icon' => 'fa fa-bolt',
					'url' => site_url(),
					'time' => '5 hrs',
				),
			),
			'inbox' => array(
				array(
					'title' => 'Sumi',
					'detail' => 'Testing Message',
					'icon' => site_url("assets/img/avatar/default.png"),
					'url' => site_url(),
					'time' => 'Just Now',
				),
				array(
					'title' => 'Yudi Aja',
					'detail' => 'Jangan Lupa dikerjakan',
					'url' => site_url(),
					'time' => '15 mins',
				),
				array(
					'title' => 'Triana',
					'detail' => 'Disposisi Akuntansi',
					'url' => site_url(),
					'time' => '22 mins',
				),
				array(
					'title' => 'Syukron',
					'detail' => 'Layout Baru',
					'url' => site_url(),
					'time' => '40 mins',
				),
				array(
					'title' => 'Farmasi',
					'detail' => 'Error tarik data',
					'url' => site_url(),
					'time' => '5 hrs',
				),
			),
			'tasks' => array(
				array(
					'title' => 'New release v1.2',
					'detail' => 'success',
					'url' => site_url(),
					'time' => '79',
				),
				array(
					'title' => 'Application deployment',
					'detail' => 'danger',
					'url' => site_url(),
					'time' => '15',
				),
				array(
					'title' => 'Mobile app release',
					'detail' => 'warning',
					'url' => site_url(),
					'time' => '22',
				),
				array(
					'title' => 'Database migration',
					'detail' => 'info',
					'url' => site_url(),
					'time' => '40',
				),
				array(
					'title' => 'Web server upgrade',
					'detail' => 'danger',
					'url' => site_url(),
					'time' => '50',
				),
			),
		);
		$data[$f] = $d[$f]; 
		$this->load->view($f, $data); */
		$this->load->view($f, array());
		return;
	}
	
	public function load_menu_user($dir = null) {
		$this->load->model('Auth_model');
		$tmp = $this->Auth_model->get_menu_user();
		$menu = array();		
		foreach($tmp as $r):
			$t = array (
				'id' => $r->id_mst_menu,
				'name' => $r->nm_mst_menu,
				'icon' => $r->icon_menu,
				'dir' => $r->directory,
				'cont' => $r->controller,
				'url' => site_url("$r->directory/$r->controller"),
				'sub' => array()
			);
				
			if ($r->id_mst_menu_parent == '0') {
				$menu[$r->id_mst_menu] = $t;
			} else {
				if (isset($menu[$r->id_mst_menu_parent]['sub'])) {
					if ($dir != null && $r->id_mst_menu_parent == 5) {
						if ($r->directory == strtolower(str_replace('/', '', $dir)))
							$menu[$r->id_mst_menu_parent]['sub'][$r->id_mst_menu] = $t;
					} else $menu[$r->id_mst_menu_parent]['sub'][$r->id_mst_menu] = $t;
				}
			}	
						
			// $this->listmenu[$r->controller] = $t;
			
		endforeach;
		$this->menu = $menu;
		$this->set('menu', $this->menu);
		// $this->set('listmenu', $this->listmenu);
		$this->load->view('sidemenu', $this->data);
		return;
	}
		
	public function create($p = 0) {
		$model = $this->name;
		if (is_ajax()):	
			if (COUNT($this->input->post()) > 0) {
				$ret = $this->$model->create();
				if (!$ret) {
					$this->setFlash('FAILED: ', 'Failed save data '.ucwords($this->name).'. Contact your IT Support.', 'alert-danger');
				} else $this->setFlash('SUCCESS', 'Success save data '.ucwords($this->name), 'alert-success');
				echo json_encode(array($ret, $this->session->flashdata('flashMessage'), $this->input->post()));
				return;
			}
			
            if ($p != 0) $this->set('rows', $this->$model->read($p)[0]);
            else $this->set('rows', array());
			
            $this->set('primaryKey', $this->$model->primaryKey);
            $this->set('fieldsList', $this->$model->get_field_list());
			$this->load->view($this->directory."$this->name/create", $this->data);
			return;
		endif;
		redirect($this->directory."$this->name");
	}
	
	public function read($a = 'view', $p = '0') {
		$model = $this->name;
        if (is_ajax()):            
            if ($a == 'json') {
                echo json_encode($this->$model->read($p));
                return;
            } else if ($a == 'array') {
                return $this->$model->read($p);
            } else {
                $this->set('primaryKey', $this->$model->primaryKey);
                $this->set('rows', $this->$model->read($p));
                $this->load->view($this->directory.ucwords($this->name)."/{$a}", $this->data);
				return;
            }
        endif;
        redirect($this->directory."$this->name");
		return;
	}
	
	public function update($p = 0) {
		$model = $this->name;
		if (is_ajax()):	
            if (COUNT($this->input->post()) > 0) {
                $ret = $this->$model->update($p);
				if (!$ret) {
					$this->setFlash('FAILED: ', 'Failed update data '.ucwords($this->name).'. Contact your IT Support.', 'alert-danger');
				} else $this->setFlash('SUCCESS', 'Success update data '.ucwords($this->name), 'alert-success');
				echo json_encode(array($ret, $this->session->flashdata('flashMessage'), $this->input->post()));
				return;				
            }
            
			$this->set('irows', $this->$model->read($p)[0]);
			$this->set('primaryKey', $this->$model->primaryKey);

            $this->load->view($this->directory.ucwords($this->name)."/update", $this->data);
			return;
		endif;
		
		redirect($this->directory."$this->name");
		return;
	}
		
	public function delete() {
		$model = $this->name;
		if (is_ajax()):
			if (COUNT($this->input->post()) > 0) {
				$ret = $this->$model->delete();
				if (!$ret) {
					$this->setFlash('FAILED: ', 'Failed delete data '.ucwords($this->name).'. Contact your IT Support.', 'alert-danger');
				} else $this->setFlash('SUCCESS', 'Success delete data '.ucwords($this->name), 'alert-success');
				
				echo json_encode(array($ret, $this->session->flashdata('flashMessage'), $this->input->post()));
				return;
			}
			$this->load->view($this->directory."$this->name/create", $this->data);
			return;
		endif;
		redirect($this->directory."$this->name");
	}
	
	
	
}