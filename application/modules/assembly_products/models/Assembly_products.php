<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Assembly_products extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
            'dashboard/Products',
        ));
	}
	//Count Product
	//Product generator id check
    public function product_id_check($product_id)
    {
        $query = $this->db->select('*')
            ->from('product_information')
            ->where('product_id', $product_id)
            ->get();
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function product_entry($data)
    {
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('status', 1);
        $this->db->where('product_model', $data['product_model']);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return FALSE;
        }else{
            $result = $this->db->insert('product_information', $data);

            $this->db->select('*');
            $this->db->from('product_information');
            $this->db->where('status', 1);
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);
            }
            $cache_file = './my-assets/js/admin_js/json/product.json';
            $productList = json_encode($json_product);
            file_put_contents($cache_file, $productList);

            return $result;
        }
    }
    //Product List Count
    public function product_list_count()
    {
        $this->db->select('product_information.product_id');
        $this->db->from('product_information');
        $this->db->where('status', 1);
        $this->db->where('is_assemble', 1);
        $this->db->group_by('product_information.product_id');
        $query = $this->db->get();
        return $query->num_rows();
    }
    //Product List
    public function product_list($per_page = null, $page = null)
    {
        $is_aff = false;
        if(check_module_status('affiliate_products') == 1){
            $is_aff = true;
        }
        $this->db->select('
			product_information.product_id,
            product_information.product_name,
            product_information.product_model,
            product_information.price,
            product_information.image_thumb,
			product_category.category_name');  
        $this->db->from('product_information');
        $this->db->join('product_category', 'product_category.category_id = product_information.category_id', 'left');
        if($is_aff){
            $this->db->where('product_information.is_affiliate IS NULL');
        }
        $this->db->limit($per_page, $page);
        $this->db->where('product_information.status', 1);
        $this->db->where('product_information.is_assemble', 1);
        $this->db->order_by('product_information.product_name','asc');
        $this->db->group_by('product_information.product_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }
    //all_category_list
    public function all_category_list()
    {
        $query = $this->db->select('product_category.*')
            ->from('product_category')
            ->order_by('product_category.category_name', 'asc')
            ->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    // Delete Product Item
    public function delete_product($product_id)
    {
        #### Check product is using on system or not##########
        # If this product is used any calculation you can't delete this product.
        # but if not used you can delete it from the system.
        $this->db->select('product_id');
        $this->db->from('product_purchase_details');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();
        $affected_row = $query->num_rows();
        if ($affected_row == 0) {
            //product image delete
            $product_info = $this->db->select('image_large_details, image_thumb')->from('product_information')->where('product_id',$product_id)->get()->result();
            if($product_info){
                @unlink(FCPATH.$product_info->image_large_details);
                @unlink(FCPATH.$product_info->image_thumb);
            }
            $this->db->select('id');
            $this->db->from('assembly_products');
            $this->db->where('a_product_id', $product_id);
            $assembly_products = $this->db->get()->result_array();
            $assembly_product_ids = [];
            if (!empty($assembly_products)) {
                $assembly_product_ids = array_column($assembly_products, 'id');
            }
            foreach ($assembly_product_ids as $assembly_product_id) {
                $this->db->where('assembly_product_id', $assembly_product_id);
                $this->db->delete('assembly_products_details');
            }
            $this->db->where('a_product_id', $product_id);
            $this->db->delete('assembly_products');
            $this->db->where('product_id', $product_id);
            $this->db->delete('product_information');
            $this->session->set_userdata(array('message' => display('successfully_delete')));
            $this->db->select('*');
            $this->db->from('product_information');
            $this->db->where('status', 1);
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);
            }
            $cache_file = './my-assets/js/admin_js/json/product.json';
            $productList = json_encode($json_product);
            file_put_contents($cache_file, $productList);
            redirect('assembly_products/back_assembly_products/index');
        } else {
            $this->session->set_userdata(array('error_message' => display('you_cant_delete_this_product')));
            redirect('assembly_products/back_assembly_products/index');
        }
    }
    public function default_variant($product_id){
        $this->db->select('default_variant,variants');
        $this->db->from('product_information');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();
        $result =  $query->row_array();
        return $result;
    }
    //Check product Quantity wise stock
    public function check_quantity_wise_stock($quantity, $product_id, $variant, $variant_color = false)
    {
        $result = $this->db->select('*')
        ->from('store_set')
        ->where('default_status','1')
        ->get()
        ->row();
        $this->db->select("SUM(quantity) as totalPurchaseQnty");
        $this->db->from('transfer');
        $this->db->where('product_id',$product_id);
        $this->db->where('store_id',$result->store_id);
        $this->db->where('variant_id',$variant);
        if(!empty($variant_color)){
            $this->db->where('variant_color', $variant_color);
        }
        $purchase = $this->db->get()->row();
        $this->db->select("quantity");
        $this->db->from('invoice_stock_tbl');
        $this->db->where('product_id',$product_id);
        $this->db->where('store_id',$result->store_id);
        $this->db->where('variant_id',$variant);
        if(!empty($variant_color)){
            $this->db->where('variant_color', $variant_color);
        }
        $order = $this->db->get()->row();
        $cart_qnty = $quantity;
        $result = ($purchase->quantity - ($order->totalSalesQnty + $cart_qnty));
        return $result;
    }

    //Product Details
    public function product_details($product_id)
    {
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }
    public function check_variant_wise_price($product_id, $variant_id, $variant_color = false)
    {
        $pinfo = $this->db->select('price, onsale, onsale_price, variant_price')
                ->from('product_information')
                ->where('product_id', $product_id)
                ->get()->row();
        if($pinfo->variant_price){
            $this->db->select('price');
            $this->db->from('product_variants');
            $this->db->where('product_id', $product_id);
            $this->db->where('var_size_id', $variant_id);
            if(!empty($variant_color)){
                $this->db->where('var_color_id', $variant_color);
            }else{
                $this->db->where("var_color_id IS NULL");
            }
            $varprice = $this->db->get()->row();
            if(!empty($varprice)){
                $price_arr['price'] = $varprice->price;
                $price_arr['regular_price'] = $pinfo->price;
            }else{
                 if(!empty($pinfo->onsale) && !empty($pinfo->onsale_price)){
                    $price_arr['price'] = $pinfo->onsale_price;
                    $price_arr['regular_price'] = $pinfo->price;
                }else{
                    $price_arr['price'] = $price_arr['regular_price'] = $pinfo->price;
                }
            }
        } else{

            if(!empty($pinfo->onsale) && !empty($pinfo->onsale_price)){
                $price_arr['price'] = $pinfo->onsale_price;
                $price_arr['regular_price'] = $pinfo->price;
            }else{
                $price_arr['price'] = $price_arr['regular_price'] = $pinfo->price;
            }
        }
        return $price_arr;
    }
    // Check Product Tax Status
    public function get_product_tax_info($product_id, $tax_id)
    {
        $this->db->select('a.*, b.status as tax_status');
        $this->db->from('tax_product_service a');
        $this->db->join('tax b','b.tax_id=a.tax_id','left');
        $this->db->where('a.product_id', $product_id);
        $this->db->where('a.tax_id', $tax_id);
        $tax_info = $this->db->get()->row();
        return $tax_info;
    }
    public function main_product_info($product_id){
        $this->db->select('product_id,category_id,product_name,price,product_model,image_thumb,image_large_details');
        $this->db->from('product_information');
        $this->db->where('product_id', $product_id);
        $main_product_info = $this->db->get()->row();
        return $main_product_info;
    }
    public function assembly_products_info($product_id){
        $this->db->select('id,assembly_title,assembly_sub_title,required,change_quantity');
        $this->db->from('assembly_products');
        $this->db->where('a_product_id', $product_id);
        $assembly_products_info = $this->db->get()->result_array();
        return $assembly_products_info;
    }
    //Update Categories
    public function update_product($data, $product_id)
    {
        $this->db->where('product_id', $product_id);
        $this->db->update('product_information', $data);
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('status', 1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);
        }
        $cache_file = './my-assets/js/admin_js/json/product.json';
        $productList = json_encode($json_product);
        file_put_contents($cache_file, $productList);
        return true;
    }
    public function delete_assembly_product($a_product_id){
        $this->db->delete('assembly_products', array('a_product_id' => $a_product_id));
        return true;
    }
    public function update_assembly_product($assembly_products_table_id){
        $this->db->delete('assembly_products_details', array('assembly_product_id' => $assembly_products_table_id));
        return true;
    }
}