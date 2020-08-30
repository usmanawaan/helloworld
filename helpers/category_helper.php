<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('getCategoryById'))
{
    function getCategoryById($id,$field='title')
    {
        $CI    =& get_instance();
        $query = $CI->db->where('id',$id)->get('categories');
        $record = $query->row();
        return $record->$field;
    }
}
