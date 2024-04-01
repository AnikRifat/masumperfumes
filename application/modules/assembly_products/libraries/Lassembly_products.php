<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Lassembly_products {
        //Retrive product list
        public function product_list($links,$per_page,$page)
        {
            $CI =& get_instance();
            $CI->load->model('assembly_products');
            $CI->load->model('dashboard/Soft_settings');
            $products_list 	   = $CI->assembly_products->product_list($per_page,$page);
            $all_category_list = $CI->assembly_products->all_category_list();
            $i=$page;
            if(!empty($products_list)){
                foreach($products_list as $k=>$v){$i++;
                    $products_list[$k]['sl']=$i;
                }
            }
            $currency_details = $CI->Soft_settings->retrieve_currency_info();
            $data = array(
                'title' 	        => display('manage_assembly_products'),
                'products_list'     => $products_list,
                'links' 	        => $links,
                'all_category_list' => $all_category_list,
                'currency' 	        => $currency_details[0]['currency_icon'],
                'position' 	        => $currency_details[0]['currency_position'],
            );
            $productList = $CI->parser->parse('assemble_products/manage_assembly_products',$data,true);
            return $productList;
        }

    }
?>