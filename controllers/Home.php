<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Public_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('public/products_model');
    }


	public function index()
	{
	    $this->data['categories'] = $this->products_model->getCategories();
	    $this->data['products'] = $this->products_model->getProducts();
	    $this->load->view('public/home', $this->data);
	}
    public function search($str=null)
    {
        echo $str;
        exit;
        //$this->load->view('public/home', $this->data);
    }
    public function category($str=null)
    {
        echo $str;
        exit;
        //$this->load->view('public/home', $this->data);
    }
}
