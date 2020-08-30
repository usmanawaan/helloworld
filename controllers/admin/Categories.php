<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth', 'refresh');
        }

        /* Title Page :: Common */
        $this->page_title->push('Categories');
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, 'Categories', 'admin/categories');
        $this->load->model('admin/categories_model');
    }


	public function index()
	{
		/* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();
        $this->data['categories'] = $this->categories_model->getCategories();
        $this->data['message'] = $this->session->flashdata('message');
        /* Load Template */
        $this->template->admin_render('admin/categories/index', $this->data);
	}

    public function create()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, 'Create Category', 'admin/categories/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->form_validation->set_rules('title', 'Product Title', 'required|min_length[4]|max_length[200]|trim');
        $this->form_validation->set_rules('status', 'Product Status', 'required|numeric|trim');

        if ($this->form_validation->run() == TRUE)
        {
            $title = $this->input->post('title');
            $status = $this->input->post('status');

            $additional_data = array(
                'title' => $title,
                'status'      => $status,
            );
        }
        if ($this->form_validation->run() == TRUE && $this->products_model->addProduct($additional_data))
        {
            $this->session->set_flashdata('message', '<div class="alert alert-success">SUCCESS: Category created successfully.</div>');
            redirect('admin/products', 'refresh');

        }
        else
        {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['title'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('title'),
            );
            /* Load Template */
            $this->template->admin_render('admin/categories/create', $this->data);
        }
    }

}
