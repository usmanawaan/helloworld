<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model {

    protected $table = "products";
    protected $catTable = "categories";

    public function __construct()
    {
        parent::__construct();
    }


    public function getCategories()
    {
        $query = $this->db->get($this->catTable);
        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }
    public function getSingleCategory($slug)
    {
        $query = $this->db->where('slug',$slug)->get($this->catTable);
        if ($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return FALSE;
        }
    }
    public function getProducts($catSlug=null)
    {
        if($catSlug){
            $category = $this->getSingleCategory($catSlug);
            $this->db->where('category',$category->id);
        }
        $this->db->where('is_deleted',0);
        $this->db->where('status',1);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }
}