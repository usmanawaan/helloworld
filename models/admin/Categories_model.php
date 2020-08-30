<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_model extends CI_Model {

    protected $table = "categories";

    public function __construct()
    {
        parent::__construct();
    }


    public function getCategories()
    {
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



    public function addCategory($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function updateCategory($table, $data)
    {
        $where = "id = 1";

        return $this->db->update($table, $data, $where);
    }

}
