<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cfiltration_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	//Parent List
	public function get_all_types()
	{
		$this->db->select('*');
		$this->db->from('filter_types');
		$this->db->order_by('fil_type_id','desc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	public function category_list_all()
	{
		$this->db->select('category_name, category_id');
		$this->db->from('product_category');
		$this->db->order_by('category_name','asc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	public function get_filter_type($id)
	{
		$this->db->select('filter_types.fil_type_name,filter_types.fil_type_id');
		$this->db->from('filter_types');
		$this->db->where('fil_type_id',$id);
		$query = $this->db->get();
		return $query->row_array();	
	}
	public function get_filter_names($id)
	{
		$this->db->select('filter_items.item_id,filter_items.item_name');
		$this->db->from('filter_items');
		$this->db->where('type_id',$id);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_filter_categories($id)
	{
		$this->db->select('filter_type_category.category_id');
		$this->db->from('filter_type_category');
		$this->db->where('type_id',$id);
		$query = $this->db->get();
		return $query->result();
	}
	public function category_wise_filters($category_id){
		$this->db->select('b.*');
		$this->db->from('filter_type_category a');
		$this->db->where('a.category_id',$category_id);
		$this->db->join('filter_types b','b.fil_type_id = a.type_id');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function filter_type_wise_items($type_id){
		$this->db->select('filter_items.item_id,filter_items.item_name');
		$this->db->from('filter_items');
		$this->db->where('type_id',$type_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function category_wise_filter_types($cat_id){
		$this->db->select('a.*');
		$this->db->from('filter_types a');
		$this->db->join('filter_type_category b','b.type_id = a.fil_type_id');
		$this->db->where('b.category_id',$cat_id);
		$this->db->order_by('a.fil_type_id','desc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
}