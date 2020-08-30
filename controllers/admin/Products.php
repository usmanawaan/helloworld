<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth', 'refresh');
        }

        /* Title Page :: Common */
        $this->page_title->push('Products');
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, 'Products', 'admin/products');
        $this->load->model('admin/products_model');
        $this->load->model('admin/categories_model');
        $this->load->helper('category');
    }

	public function index()
	{
		/* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();
        $this->data['products'] = $this->products_model->getProducts();
        $this->data['message'] = $this->session->flashdata('message');
        /* Load Template */
        $this->template->admin_render('admin/products/index', $this->data);
	}

	public function create()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, 'Create Product', 'admin/products/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();
        $categories11 = $this->categories_model->getCategories();
        $categories = array('0' => 'Root');
        if($categories):
        foreach($categories11 as $category11){
            $categories[$category11->id] = $category11->title;
        }
        endif;
        $paramsMoneyValidate = "DOT,COMMA,This is no money";
        $this->form_validation->set_rules('title', 'Product Title', 'required|min_length[4]|max_length[200]|trim');
        $this->form_validation->set_rules('sku', 'Product Sku', 'required|is_unique[products.sku]|trim');
        $this->form_validation->set_rules('price', 'Product Price', 'callback_is_money_multi');
        $this->form_validation->set_rules('description', 'Product Description', 'trim');
        $this->form_validation->set_rules('status', 'Product Status', 'required|numeric|trim');
        $this->form_validation->set_rules('category', 'Product Category', 'required|numeric|trim');
        $this->data['categories'] = $categories;
        $this->data['category'] = $this->input->post('category');;
        if ($this->form_validation->run() == TRUE)
        {
            $title = $this->input->post('title');
            $sku    = $this->input->post('sku');
            $price    = $this->input->post('price');
            $description = $this->input->post('description');
            $status = $this->input->post('status');
            $category = $this->input->post('category');
            if(!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
                $image = null;
                $imageError = false;
            }else{
                $imageData = $this->do_upload('image');
                if($imageData){
                    $imageError = false;
                    $image = $imageData['file_name'];
                }else{
                    $imageError = true;
                    $image = null;
                }

            }
            $additional_data = array(
                'title' => $title,
                'sku'  => $sku,
                'price'  => $price,
                'category'  => $category,
                'image'    => $image,
                'description'      => $description,
                'status'      => $status,
            );
        }
        if ($this->form_validation->run() == TRUE && $this->products_model->addProduct($additional_data))
        {
            if($imageError){
                $this->session->set_flashdata('message', '<div class="alert alert-success">SUCCESS: Product created successfully.</div><div class="alert alert-info">INFO: Product image not uploaded due to error.</div>');
                redirect('admin/products', 'refresh');
            }else{
                $this->session->set_flashdata('message', '<div class="alert alert-success">SUCCESS: Product created successfully.</div>');
                redirect('admin/products', 'refresh');
            }
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
            $this->data['sku'] = array(
                'name'  => 'sku',
                'id'    => 'sku',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('sku'),
            );
            $this->data['price'] = array(
                'name'  => 'price',
                'id'    => 'price',
                'type'  => 'text',
                'placeholder' => '0.00',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('price'),
            );
            $this->data['description'] = array(
                'name'  => 'description',
                'id'    => 'description',
                'type'  => 'textarea',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('description'),
            );
            /* Load Template */
            $this->template->admin_render('admin/products/create', $this->data);
        }
    }

    public function edit($id)
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, 'Edit Product', 'admin/products/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();
        $categories11 = $this->categories_model->getCategories();
        $categories = array('0' => 'Root');
        if($categories):
            foreach($categories11 as $category11){
                $categories[$category11->id] = $category11->title;
            }
        endif;
        $product          = $this->products_model->getSingleProduct($id);
        $category        = $product->category;
        $this->form_validation->set_rules('title', 'Product Title', 'required|min_length[4]|max_length[200]|trim');
        $this->form_validation->set_rules('sku', 'Product Sku', 'required|trim|callback_check_unique_sku');
        $this->form_validation->set_rules('price', 'Product Price', 'callback_is_money_multi');
        $this->form_validation->set_rules('description', 'Product Description', 'trim');
        $this->form_validation->set_rules('status', 'Product Status', 'required|numeric|trim');
        $this->form_validation->set_rules('category', 'Product Category', 'required|numeric|trim');

        if (isset($_POST) && ! empty($_POST))
        {
            if ($this->form_validation->run() == TRUE)
            {
                if(!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
                    $image = $product->image;
                    $imageError = false;
                }else{
                    $imageData = $this->do_upload('image');
                    if($imageData){
                        $imageError = false;
                        $image = $imageData['file_name'];
                    }else{
                        $imageError = true;
                        $image = $product->image;
                    }
                }
                $data = array(
                    'title' => $this->input->post('title'),
                    'sku'  => $this->input->post('sku'),
                    'price'  => $this->input->post('price'),
                    'category'  => $this->input->post('category'),
                    'image'    => $image,
                    'description'      => $this->input->post('description'),
                    'status'      => $this->input->post('status'),
                );
                if($this->products_model->updateProduct($product->id, $data))
                {
                    if($imageError){
                        $this->session->set_flashdata('message', '<div class="alert alert-success">SUCCESS: Product updated successfully.</div><div class="alert alert-info">INFO: Product image not uploaded due to error.</div>');
                        redirect('admin/products', 'refresh');
                    }else{
                        $this->session->set_flashdata('message', '<div class="alert alert-success">SUCCESS: Product updated successfully.</div>');
                        redirect('admin/products', 'refresh');
                    }
                }
                else
                {
                    $this->session->set_flashdata('message', "Something's Wrong please try again.");
                    redirect('admin/products', 'refresh');
                }
            }
        }

        $this->data['product'] = $product;
        $this->data['categories'] = $categories;
        $this->data['category'] = $category;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['title'] = array(
            'name'  => 'title',
            'id'    => 'title',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('title',$product->title),
        );
        $this->data['sku'] = array(
            'name'  => 'sku',
            'id'    => 'sku',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('sku',$product->sku),
        );
        $this->data['price'] = array(
            'name'  => 'price',
            'id'    => 'price',
            'type'  => 'text',
            'placeholder' => '0.00',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('price',$product->price),
        );
        $this->data['description'] = array(
            'name'  => 'description',
            'id'    => 'description',
            'type'  => 'textarea',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('description',$product->description),
        );
        /* Load Template */
        $this->template->admin_render('admin/products/edit', $this->data);
    }

    public function delete($id)
    {
        $data = array('is_deleted' => 1);
        if($this->products_model->updateProduct($id, $data))
        {
            $this->session->set_flashdata('message', '<div class="alert alert-success">SUCCESS: Product moved to trash.</div>');
            redirect('admin/products', 'refresh');
        }
    }

    public function realdelete($id)
    {
        $product = $this->products_model->getSingleProduct($id);
        $image = "upload/products/".$product->image;
        if($this->products_model->deleteProduct($id))
        {
            if(file_exists($image)) {
                unlink($image);
            }
            $this->session->set_flashdata('message', '<div class="alert alert-success">SUCCESS: Product deleted permanently.</div>');
            redirect('admin/products/trash', 'refresh');
        }
    }

    public function restore($id)
    {
        $data = array('is_deleted' => 0);
        if($this->products_model->updateProduct($id, $data))
        {
            $this->session->set_flashdata('message', '<div class="alert alert-success">SUCCESS: Product restored successfully.</div>');
            redirect('admin/products/trash', 'refresh');
        }
    }

    public function trash()
    {
        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();
        $this->data['products'] = $this->products_model->getTrashProducts();
        $this->data['message'] = $this->session->flashdata('message');
        /* Load Template */
        $this->template->admin_render('admin/products/trash', $this->data);
    }

    public function status($id)
    {
        $product = $this->products_model->getSingleProduct($id);
        if($product->status){
            $data = array('status' => 0);
        }else{
            $data = array('status' => 1);
        }

        if($this->products_model->updateProduct($id, $data))
        {
            $this->session->set_flashdata('message', '<div class="alert alert-success">SUCCESS: Product status updated.</div>');
            redirect('admin/products', 'refresh');
        }
    }

    public function do_upload($fileInput){
        $config = array(
            'upload_path' => "./upload/products/",
            'allowed_types' => "gif|jpg|png|jpeg",
            'overwrite' => TRUE,
            'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
            'max_height' => "2000",
            'max_width' => "2000"
        );
        $this->load->library('upload', $config);
        if($this->upload->do_upload($fileInput))
        {
            return $this->upload->data();
        }
        else
        {
            $this->session->set_flashdata('message', $this->upload->display_errors());
            return false;
        }
    }

    function check_unique_sku($sku) {
        if($this->input->post('id')) {
            $id = $this->input->post('id');
        }else {
            $id = '';
        }
        $result = $this->products_model->check_unique_sku($id, $sku);
        if($result == 0) {
            $response = true;
        }else {
            $this->form_validation->set_message('check_unique_sku', 'Product Sku must be unique.');
            $response = false;
        }
        return $response;
    }

    function is_money_multi($input, $params) {
        if($input==""){$input = 0.00;}
        @list($thousand, $decimal, $message) = explode(',', $params);
        $thousand = (empty($thousand) || $thousand === 'COMMA') ? ',' : '.';
        $decimal = (empty($decimal) || $decimal === 'DOT') ? '.' : ',';
        $message = (empty($message)) ? 'The Price field is invalid' : $message;

        $regExp = "/^\s*[$]?\s*((\d+)|(\d{1,3}(\{thousand}\d{3})+)|(\d{1,3}(\{thousand}\d{3})(\{decimal}\d{3})+))(\{decimal}\d{2})?\s*$/";
        $regExp = str_replace("{thousand}", $thousand, $regExp);
        $regExp = str_replace("{decimal}", $decimal, $regExp);

        $ok = preg_match($regExp, $input);
        if(!$ok) {
            $CI =& get_instance();
            $CI->form_validation->set_message('is_money_multi', $message);
            return FALSE;
        }
        return TRUE;
    }

}
