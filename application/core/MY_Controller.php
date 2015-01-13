<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* load the MX_Controller class */
require APPPATH."third_party/MX/Controller.php";

class MY_Controller extends MX_Controller
{

	//Page info
	protected $data = array();
	protected $page_name = FALSE;
	protected $module_name = FALSE;
	protected $template = 'main';
	private $alerts = array();

	//Page contents
	protected $javascript = array();
	protected $css = array();
	protected $fonts = array();

	//Page Meta
	protected $title = FALSE;
	protected $description = FALSE;
	protected $keywords = FALSE;
	protected $author = FALSE;

	// URI segments
	private $segments = array();
	private $rsegments = array();

	// MX internal module call
	public $is_intern_call = FALSE;

	// Login sesion data
	public $is_log = FALSE;
	public $is_admin = FALSE;
	public $account_id = NULL;

	// Config 
	public $label_company = NULL;

	function __construct()
	{	
		parent::__construct();
		date_default_timezone_set($this->config->item('timezone'));
		// ORM
		Datamapper::add_model_path(array( APPPATH.'models/orm'));

		if ($this->input->is_cli_request())
			return;

		$this->load->library('session');

		// Bootstrap render
		$this->data["uri_segment_1"] = $this->uri->segment(1);
		$this->data["uri_segment_2"] = $this->uri->segment(2);
		$this->title = $this->config->item('site_title');
		$this->description = $this->config->item('site_description');
		$this->keywords = $this->config->item('site_keywords');
		$this->author = $this->config->item('site_author');

		$this->page_name = strToLower(get_class($this));
		$this->module_name=$this->uri->segment(1);
		if ($this->module_name == FALSE) // For default controller
			$this->module_name = $this->page_name;

		// Segments
		$this->segments=$this->uri->uri_to_assoc(2);
		$this->segments=$this->uri->ruri_to_assoc(2);

		// Login
		$this->account_id = $this->session->userdata('accountid');
		$this->is_log = $this->account_id;
		$this->is_admin = $this->account_id === -1;
		$this->data['is_log']=$this->is_log;

		if (get_class($this) != 'Welcome' AND
			get_class($this) != 'Authentification' AND
			!$this->is_log)
		{
			if ($this->input->is_ajax_request())
				exit;

			$this->_redirect('welcome?dest='.uri_string());
		}

		// Dev profiler
		$this->output->enable_profiler(FALSE);

		// Config
		$this->label_company = $this->config->item('company');
		$this->data['label_company'] = $this->label_company;
	}

	protected function _segments ($name_id)
	{
		return @$this->segments[$name_id];
	}

	protected function _rsegments ($name_id)
	{
		return @$this->rsegments[$name_id];
	}

	public function _detect_render_type ($render_data)
	{
		if ($this->input->is_ajax_request())
			return 'MINI';
		
		if ($this->is_intern_call)
			return 'MINI';
		
		return $render_data;
	}

	public function _render($view='empty',$render_data='FULL') 
	{
		$view = empty($view)?'empty':$view;
		$render_data = $this->_detect_render_type ($render_data);
		$this->data['view_id']=md5(uniqid(rand()));
		$this->data['is_log']=$this->is_log;
		$this->data['is_admin']=$this->is_admin;


		switch ($render_data) 
		{
		case 'MINI'     :
			$toTpl['javascript'] = $this->javascript;
			$toTpl['css'] = $this->css;
			$toTpl['fonts'] = $this->fonts;

			$toTpl['content'] = $this->load->view($view,array_merge($this->data,$toTpl),true);
			$toTpl['alerts']= $this->input->is_ajax_request() ? $this->_render_alerts() :'';
	
			$this->load->view('template/ajax_skeleton',$toTpl);
			break;

		case 'FULL' :
		default         : 
			//static
			$toTpl['javascript'] = $this->javascript;
			$toTpl['css'] = $this->css;
			$toTpl['fonts'] = $this->fonts;

			//meta
			$toTpl['title'] = $this->title;
			$toTpl['description'] = $this->description;
			$toTpl['keywords'] = $this->keywords;
			$toTpl['author'] = $this->author;

			//data
			$toBody['content_body'] = $this->load->view($view,array_merge($this->data,$toTpl),true);
			//nav menu
			if($this->is_log)
			{
				$this->load->helper('nav');

				$time_interval = new Time_interval();
				$time_interval->where('contact_id',$this->account_id);
				$time_interval->where('end IS ','NULL',false);
				$time_interval->get();

				$toMenu['module_name'] = $this->module_name;
				$toMenu['highlight'] = $this->breadcrumb->root();
				$toMenu['internal_access'] = $this->acl->has_privilege('internal_access');
				$toMenu['time_interval'] = $time_interval;

				$toHeader['nav'] = $this->load->view("template/nav",$toMenu,true);
			}
			$toBody['footer'] = $this->load->view("template/footer",'',true);
			$toHeader['basejs'] = $this->load->view("template/basejs",$this->data,true);

			$toBody['header'] = $this->load->view("template/header",$toHeader,true);
			
			$toBreadcrumb = array("current" => json_encode(current_url()));
			$toBody["breadcrumb"] = $this->load->view("template/breadcrumb",$toBreadcrumb,true);

			$toTpl['body'] = $this->load->view("template/".$this->template,$toBody,true);
			$toTpl['alerts'] = $this->_render_alerts();


			//render view
			$this->load->view("template/fullpage_skeleton",$toTpl);
			break;
		}
	}

	protected function _render_alerts()
	{
		$session_alerts = $this->session->userdata('alerts');
		if ($session_alerts)
		{
			$this->alerts=array_merge($this->alerts,$session_alerts);
			$this->session->unset_userdata('alerts');
		}

		$this->data['alerts']=$this->alerts;
		$this->alerts=array();

		return $this->load->view('template/alerts.php',$this->data,true);
	}

	public function _redirect_previous($url=NULL)
	{
		$http_referer = $this->input->server('HTTP_REFERER');
		$last_redirection = $this->session->flashdata('redirection');
		$site_url=site_url();

		if ($http_referer != $last_redirection AND
			strncmp($http_referer,$site_url,strlen($site_url)) >= 0)
		{
			$this->session->set_flashdata('redirection', $http_referer);	
			$this->_redirect($http_referer,TRUE);
		}
		else if (!is_null($url))
			$this->_redirect($url);
		else
			$this->_redirect('/');
	}

	public function _redirect ($url,$full=FALSE)
	{
		if ($this->input->is_ajax_request() OR $this->is_intern_call)
		{
			$location = 'Location: '.($full?$url:site_url($url));
			echo $location;
			return $location;
		}
		else
		{
			redirect($full?$url:site_url($url));
		}
	}

	public function _alert ($string,$type='info')
	{
		$allowed_types = array ('info','warning','success','error');
		if (!in_array($type,$allowed_types))
			return false;

		$alert = array();
		$alert['text']=$string;
		$alert['type']=$type;

		$this->alerts[$string]=$alert;
		$this->session->set_userdata('alerts',$this->alerts);
	}

	public function _log ($id,$action,$title=NULL,$uri_link=NULL,$type=NULL)
	{
		$contact = new Contact($this->account_id);

		$activity = new Activity();
		$activity->id_link = $id;
		$activity->uri_link=is_null($uri_link)?strtolower(get_class($this))."/view/$id":$uri_link;
		$activity->type=is_null($type)?strtolower(get_class($this)):$type;
		$activity->title=is_null($title)?"$contact->login $action $activity->type":$title;
		$activity->action=$action;
		$activity->url=current_url();
		$activity->contact_id=$this->account_id;
		$activity->ip_address=$this->input->ip_address();
		$activity->user_agent=$this->input->user_agent();
		$activity->date = date("Y-m-d H:i:s");

		$activity->save();
	}
}
