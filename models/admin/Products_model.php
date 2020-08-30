<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model {

    protected $table = "products";

    public function __construct()
    {
        parent::__construct();
    }


    public function getProducts()
    {
        $query = $this->db->where('is_deleted','0')->get($this->table);
        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }
    public function getTrashProducts()
    {
        $query = $this->db->where('is_deleted','1')->get($this->table);
        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }
    public function deleteProduct($id)
    {
        $query = $this->db->where('id',$id)->delete($this->table);
        return true;
    }
    public function getSingleProduct($id)
    {
        $query = $this->db->where('id',$id)->get($this->table);
        if ($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return FALSE;
        }
    }

    public function addProduct($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function check_unique_sku($id = '', $sku) {
        $this->db->where('sku', $sku);
        if($id) {
            $this->db->where_not_in('id', $id);
        }
        return $this->db->get($this->table)->num_rows();
    }


    public function updateProduct($id, $data)
    {
        $where = "id = $id";

        return $this->db->update($this->table, $data, $where);
    }

}
