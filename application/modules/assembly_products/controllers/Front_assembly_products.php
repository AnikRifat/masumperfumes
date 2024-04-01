<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
#------------------------------------
# Author: Bdtask Ltd
# Author link: https://www.bdtask.com/
# Dynamic style php file
# Developed by :Sahariar Sabit
#------------------------------------
class Front_assembly_products extends MX_Controller
{
	function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'assembly_products',
        ));
        $this->load->library('lassembly_products');
    }
    //Check assembly quantity wise stock
    public function check_assembly_quantity_wise_stock()
    {
        $quantity   = $this->input->post('product_quantity',TRUE);
        $product_id = $this->input->post('product_id',TRUE);

        $variant    = $this->assembly_products->default_variant($product_id);
        $variants   = explode(',',$variant['variants']);
        $default_variant = $variant['default_variant'];
        $default_color = '';
        $this->db->select('*');
        $this->db->from('variant');
        $this->db->where_in('variant_id', $variants);
        $colors = $this->db->get()->result_array();

        foreach($colors as $color){
            if($color['variant_type'] == 'color'){
                $default_color = $color['variant_id'];
                break;
            }
        }
        
        $stock = $this->assembly_products->check_quantity_wise_stock($quantity, $product_id, $default_variant, $default_color);
        if ($stock >= $quantity) {
            echo "yes";
        } else {
            echo "no";
        }
    }
    //Add to cart for details
    public function add_to_cart(){
        $main_product_id = $this->input->post('main_product_id',TRUE);
        $a_item_ids = $this->input->post('a_item_id',TRUE);
        $a_item_qty = $this->input->post('a_item_qty',TRUE);
        $quantity = $this->input->post('qty',TRUE);

        $count = 0;
        if(!empty($a_item_ids)){
            for ($i=0; $i<count($a_item_ids); $i++) {

                if(!empty($a_item_ids[$i])){

                    $aproduct_id = $a_item_ids[$i];
                    $aproduct_qty = (!empty($a_item_qty[$i])?$a_item_qty[$i]:1);

                    // Get Product variant
                    $variant    = $this->assembly_products->default_variant($aproduct_id);
                    $variants   = explode(',',$variant['variants']);
                    $default_variant = $variant['default_variant'];
                    $default_color = '';
                    $this->db->select('*');
                    $this->db->from('variant');
                    $this->db->where_in('variant_id', $variants);
                    $colors = $this->db->get()->result_array();
                    foreach($colors as $color){
                        if($color['variant_type'] == 'color'){
                            $default_color = $color['variant_id'];
                            break;
                        }
                    }

                    $discount = 0;
                    $onsale_price = 0;
                    $cgst = 0;
                    $cgst_id = 0;

                    $sgst = 0;
                    $sgst_id = 0;

                    $igst = 0;
                    $igst_id = 0;

                
                    $product_details = $this->assembly_products->product_details($aproduct_id);
                    $price_arr =  $this->assembly_products->check_variant_wise_price($aproduct_id, $default_variant, $default_color);
                    $final_price = $price_arr['price'];
                    
                    if ($product_details->onsale) {
                        $onsale_price = $product_details->onsale_price;
                        $discount = $product_details->price - $final_price;
                        $discount = (($discount > 0)?$discount:0);
                    }
                    //CGST product tax
                    $tax_info = $this->assembly_products->get_product_tax_info($assymbled_item[$count], 'H5MQN4NXJBSDX4L');
                    if (!empty($tax_info) && !empty($tax_info->tax_status)) {
                        $cgst = ($tax_info->tax_percentage * $final_price) / 100;
                        $cgst_id = $tax_info->tax_id;
                    }
                    // SGST product tax
                    $tax_info = $this->assembly_products->get_product_tax_info($assymbled_item[$count], '52C2SKCKGQY6Q9J');
                    if (!empty($tax_info) && !empty($tax_info->tax_status)) {
                        $sgst = ($tax_info->tax_percentage * $final_price) / 100;
                        $sgst_id = $tax_info->tax_id;
                    }
                    //IGST product tax
                    $tax_info = $this->assembly_products->get_product_tax_info($assymbled_item[$count], '5SN9PRWPN131T4V');
                    if (!empty($tax_info) && !empty($tax_info->tax_status)) {
                        $igst = ($tax_info->tax_percentage * $final_price) / 100;
                        $igst_id = $tax_info->tax_id;
                    }
                    //Shopping cart validation
                    $flag = TRUE;
                    $dataTmp = $this->cart->contents();
                    if(!empty($dataTmp)){
                        foreach ($dataTmp as $item) {
                            if(!empty($default_color)){
                              if (($item['product_id'] == $aproduct_id) && ($item['variant'] == $default_variant) && ($item['variant_color'] == $default_color)) {
                                    $data = array(
                                        'rowid' => $item['rowid'],
                                        'qty' => $item['qty'] + $quantity*$aproduct_qty
                                    );
                                    $this->cart->update($data);
                                    $flag = FALSE;
                                    break;
                                }  
                            }else{
                                if (($item['product_id'] == $aproduct_id) && ($item['variant'] == $default_variant)) {
                                    $data = array(
                                        'rowid' => $item['rowid'],
                                        'qty' => $item['qty'] + $quantity*$aproduct_qty
                                    );
                                    $this->cart->update($data);
                                    $flag = FALSE;
                                    break;
                                }
                            }
                        }
                    }
                    if ($flag) {
                        $data = array(
                            'id' => $this->generator(15),
                            'product_id' => $aproduct_id,
                            'qty' => $quantity*$aproduct_qty,
                            'price' => $final_price,
                            'actual_price' => $final_price,
                            'supplier_price'=> $product_details->supplier_price,
                            'onsale_price'  => $onsale_price,
                            'name'          => clean($product_details->product_name),
                            'discount'      => $discount,
                            'variant'       => $default_variant,
                            'variant_color' => $default_color,
                            'options'       => array(
                            'image'=> $product_details->image_thumb,
                            'model'=> $product_details->product_model,
                            'cgst' => $cgst,
                            'sgst' => $sgst,
                            'igst' => $igst,
                            'cgst_id' => $cgst_id,
                            'sgst_id' => $sgst_id,
                            'igst_id' => $igst_id,
                            )
                        );
                        $result = $this->cart->insert($data);
                    }
                }
            }
        }
        echo "1";
    }
    //This function is used to Generate Key
    public function generator($lenth)
    {
        $number = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "N", "M", "O", "P", "Q", "R", "S", "U", "V", "T", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 34);
            $rand_number = $number["$rand_value"];
            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }
}