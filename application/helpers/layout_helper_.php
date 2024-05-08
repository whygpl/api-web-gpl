<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('time_elapsed_string') ) {
   
	function time_elapsed_string($datetime, $full = false) { 
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
		
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
}

if ( !function_exists('is_active_nav') ) {
    function is_active_nav($current, $nav) {
        if ($current == $nav)
            return "active";
        else
            return false;
    }
}

if ( !function_exists('is_ajax') ) {
    function is_ajax() {
        $ci =& get_instance();
        
        return $ci->input->is_ajax_request();
    }
}

if ( !function_exists('show_navigation') ) {
    function show_navigation($template='navigation', $lavel=0) {
        $ci =& get_instance();

        $data = $ci->Auth->get_navigation($level);
        return $ci->load->view($template, $data, true);
    }
}

if ( !function_exists('load_element') ) {
    function load_element($element, $data=false) {
        $ci =& get_instance();

        return $ci->loadElement($element, $data);
    }
}

if ( !function_exists('is_auth') ) {
    function is_auth($controller, $action) {
        // $ci =& get_instance();

        // if ($ci->get('isAdmin')) return true;

        // return $ci->auth->is_auth($controller, $action);
		return true;
    }
}

if ( !function_exists('is_date_format') ) {
    function is_date_format($data, $format="d-m-Y") {
        if (trim($data) == '0000-00-00' || trim($data) == '0000-00-00 00:00'
            || trim($data) == '0000-00-00 00:00:00') return;
        if (@date('Y-m-d', @strtotime($data)) == trim($data) || @date('Y-m-d H:i:s', @strtotime($data)) == trim($data))
            return @date($format, @strtotime($data));
        else 
            return $data;
    }
}

if ( !function_exists('layout_header') ) {
    function layout_header($options) {
        $head = '';
        extract($options);

        if (isset($css)) {
            if (is_array($css)) {
                foreach($css as $item) {
                    $head .= '<link href="'.base_url('assets/css/'.$item).'" rel="stylesheet">';
                }
            } else {
                $head .= '<link href="'.base_url('assets/css/'.$css).'" rel="stylesheet">';
            }
        }

        if (isset($js)) {
            if (is_array($js)) {
                foreach($js as $item) {
                    $head .= '<script type="text/javascript" src="'.base_url('assets/js/'.$item).'"></script>';
                }
            } else {
                $head .= '<script type="text/javascript" src="'.base_url('assets/js/'.$js).'"></script>';
            }
        }

        $ci =& get_instance();
        $ci->set('layout_header', $head);
    }
}

if (!function_exists('show_last_query')) {
	function show_last_query() {
		$ci =& get_instance();
		
		$query = $ci->db->last_query();
		echo "<pre>{$query}</pre>";
	}
}

if (!function_exists('field_alias')) {
	function field_alias($field) {
		$split = preg_split('/\s[aAsS]+\s|\s|\./', $field);
		return trim($split[count($split) - 1]);
	}
}

?>
