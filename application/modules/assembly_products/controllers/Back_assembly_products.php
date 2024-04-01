<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
#------------------------------------
# Author: Bdtask Ltd
# Author link: https://www.bdtask.com/
# Dynamic style php file
# Developed by :Sahariar Sabit
#------------------------------------
class Back_assembly_products extends MX_Controller
{
	function __construct()
    {
        parent::__construct();
        $this->auth->check_user_auth();
        $this->load->model(array(
            'Assembly_products',
            'dashboard/Products'
        ));
        $this->load->library('lassembly_products');
    }
    private $table = "language";
    //Manage Product
    public function index($page = 0)
    {
        $this->permission->check_label('manage_assembly_product')->read()->redirect();
        #
        #pagination starts
        #
        $config["base_url"]    = base_url('assembly_products/assembly_products/index/');
        $config["total_rows"]  = $this->Assembly_products->product_list_count();
        $config["per_page"]    = 20;
        $config["uri_segment"] = 4;
        $config["num_links"]   = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open']    = "<ul class='pagination'>";
        $config['full_tag_close']   = "</ul>";
        $config['num_tag_open']     = '<li>';
        $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close']    = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open']    = "<li>";
        $config['next_tag_close']   = "</li>";
        $config['prev_tag_open']    = "<li>";
        $config['prev_tagl_close']  = "</li>";
        $config['first_tag_open']   = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open']    = "<li>";
        $config['last_tagl_close']  = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $links = $this->pagination->create_links();
        #
        #pagination ends
        #
        $content = $this->lassembly_products->product_list($links,$config["per_page"],$page);
        $this->template_lib->full_admin_html_view($content);
    }
    //Add new Product
    public function new_product()
    {
        $this->permission->check_label('add_assembly_product')->read()->redirect();
        $this->load->model(array(
            'dashboard/Stores',
            'dashboard/Variants',
            'dashboard/Customers',
            'dashboard/Shipping_methods',
            'dashboard/Categories'
        ));
        $store_list       = $this->Stores->store_list();
        $variant_list     = $this->Variants->variant_list();
        $shipping_methods = $this->Shipping_methods->shipping_method_list();
        $customer         = $this->Customers->customer_list();
        $category_list    = $this->Categories->category_list();
        $languages        = $this->languages();
        $data = array(
            'title'            => display('new_assembly_product'),
            'store_list'       => $store_list,
            'variant_list'     => $variant_list,
            'customer'         => $customer[0],
            'shipping_methods' => $shipping_methods,
            'category_list'    => $category_list,
            'languages'        => $languages,
        );
        $data['module'] = "assembly_products";
        $data['page']   = "assemble_products/add_prduct_form";
        echo Modules::run('template/layout', $data);
    }
    public function insert_product()
    {
        $this->permission->check_label('add_assembly_product')->create()->redirect();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', display('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('category_id', display('category_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('model', display('model'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->new_product();
        } else {
            if ($_FILES['image_thumb']['name']) {
                //Chapter chapter add start
                $config['upload_path'] = './my-assets/image/product/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
                $config['max_size'] = "*";
                $config['max_width'] = "*";
                $config['max_height'] = "*";
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image_thumb')){
                    $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                    redirect('assembly_products/back_assembly_products/new_product');
                } else {
                    $image = $this->upload->data();
                    $image_url = "my-assets/image/product/" . $image['file_name'];
                    //Resize image config
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $image['full_path'];
                    $config['maintain_ratio'] = FALSE;
                    $config['width'] = 400;
                    $config['height'] = 400;
                    $config['new_image'] = 'my-assets/image/product/thumb/' . $image['file_name'];
                    $this->upload->initialize($config);
                    $this->load->library('image_lib', $config);
                    $resize = $this->image_lib->resize();
                    //Resize image config
                    $thumb_image = $config['new_image'];
                }
            }
            $a_product_id  = $this->generator(8);
            $data = array(
                'product_id'   => $a_product_id,
                'product_name' => $this->input->post('name',TRUE),
                'product_model'=> $this->input->post('model',TRUE),
                'category_id'  => $this->input->post('category_id',TRUE),
                'price'        => 0,
                'image_large_details'=> (!empty($image_url) ? $image_url : 'my-assets/image/product.png'),
                'image_thumb'  => (!empty($thumb_image) ? $thumb_image : 'my-assets/image/product.png'),
                'status'       => 1,
                'is_assemble'  => 1,
            );
            $result = $this->Assembly_products->product_entry($data);
            if($result) {
                $assembly_title    =$this->input->post('assembly_title',TRUE);
                $assembly_sub_title=$this->input->post('assembly_sub_title',TRUE);
                $assembly_title_id =$this->input->post('assembly_title_id',TRUE);
                $required          =$this->input->post('required',TRUE);
                $change_quantity   =$this->input->post('change_quantity',TRUE);
                //Product details for assembly
                $price = 0;
                $n = array_key_last($assembly_title);
                $assembly_product_details=[];
                for ($i=1; $i<=$n; $i++){
                    if(!empty($assembly_title[$i])){
                        $title_assemble    =$assembly_title[$i];
                        $sub_title_assembly=$assembly_sub_title[$i];
                        $title_id_assembly =$assembly_title_id[$i];
                        $requireds         =(($required[$i])?1:0);
                        $quantity_change   =(($change_quantity[$i])?1:0);
                        $details = array(
                            'a_product_id'      => $a_product_id,
                            'assembly_title'    => $title_assemble,
                            'assembly_sub_title'=> $sub_title_assembly,
                            'required'          => $requireds,
                            'change_quantity'   => $quantity_change
                        );
                        $res = $this->db->insert('assembly_products', $details);
                        $a_title_id = $this->db->insert_id();
                        $product_name = $this->input->post('product_name_'.$title_id_assembly,TRUE);
                        $product_ids = $this->input->post('product_id_'.$title_id_assembly,TRUE);
                        $is_default = $this->input->post('is_default_'.$title_id_assembly,TRUE);
                        for ($j=0; $j<count($product_ids); $j++){
                            if(!empty($product_ids[$j])){
                                $single_price = $this->check_admin_2d_variant_info($product_ids[$j]);
                                if($product_ids[$j] == $is_default){
                                    $price = $price+$single_price;
                                }
                                $assembly_product_id = $a_title_id;
                                $assembly_product_details[] = array(
                                    'assembly_product_id'=> $assembly_product_id,
                                    'product_id' => $product_ids[$j],
                                    'is_default' => (($product_ids[$j] == $is_default) ? '1' : '0')
                                );
                            }
                        }
                    }
                }
                $trans_names = $this->input->post('trans_name',TRUE);
                $languages = $this->input->post('language',TRUE);
                if(!empty($languages)){
                    $data2 = [];
                    $language_array = [];
                    foreach ($languages as $key => $language){
                        if(!in_array($languages[$key], $language_array)){
                            $data2[] = array(
                                'language'   => $languages[$key],
                                'product_id' => $a_product_id,
                                'trans_name' => $trans_names[$key]
                            );
                        }else{
                            $this->session->set_userdata(array('error_message' => 'Multiple input of same language'));
                            redirect(base_url('assembly_products/back_assembly_products'));
                        }
                        $language_array[] = $data2[$key]['language'];
                    }
                    $result2 = $this->db->insert_batch('product_translation', $data2);
                }
                if(!empty($assembly_product_details)){
                    $assem_result = $this->db->insert_batch('assembly_products_details', $assembly_product_details);
                }
                $a_price = array(
                    'price' => $price,
                );
                $this->db->where('product_id', $a_product_id);
                $this->db->update('product_information',$a_price);

                $this->session->set_userdata(array('message' => display('successfully_added')));
                redirect('assembly_products/back_assembly_products/new_product');
            }else{
                $this->session->set_userdata(array('error_message' => display('product_already_exist')));
                redirect('assembly_products/back_assembly_products/new_product');
            }
        }
    }
    // Get variant price and stock
    public function check_admin_2d_variant_info($product_id)
    {
        $product_variant= $this->Assembly_products->default_variant($product_id);
        $default_variant= $product_variant['default_variant'];
        $variants= explode(',', $product_variant['variants']);
        $variant_colors= $this->db->select('variant_id')->from('variant')->where('variant_type','color')->where_in('variant_id',$variants)->get()->row();

        $variant_color = !empty($variant_colors->variant_id)?$variant_colors->variant_id:null;

        $price = $this->Assembly_products->check_variant_wise_price($product_id, $default_variant, $variant_color);
        return $price['regular_price'];
    }
    //This function is used to Generate Key
    public function generator($lenth)
    {
        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 8);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        $result = $this->Assembly_products->product_id_check($con);
        if ($result === true) {
            $this->generator(8);
        } else {
            return $con;
        }
    }
    public function product_delete($product_id){
        $this->permission->check_label('manage_assembly_product')->delete()->redirect();
        $this->db->delete('product_translation', array('product_id' => $product_id));
        $this->Assembly_products->delete_product($product_id);
    }
    public function product_edit($product_id){
        $this->permission->check_label('manage_assembly_product')->read()->redirect();
        $this->load->model(array(
            'dashboard/Stores',
            'dashboard/Variants',
            'dashboard/Customers',
            'dashboard/Shipping_methods',
            'dashboard/Categories'
        ));
        $store_list       = $this->Stores->store_list();
        $variant_list     = $this->Variants->variant_list();
        $shipping_methods = $this->Shipping_methods->shipping_method_list();
        $customer         = $this->Customers->customer_list();
        $category_list    = $this->Categories->category_list();
        $main_product_info= $this->Assembly_products->main_product_info($product_id);
        $assembly_products= $this->Assembly_products->assembly_products_info($product_id);
        $languages        = $this->languages();
        $data = array(
            'title'            => display('new_assembly_product'),
            'store_list'       => $store_list,
            'variant_list'     => $variant_list,
            'customer'         => $customer[0],
            'shipping_methods' => $shipping_methods,
            'category_list'    => $category_list,
            'main_product_info'=> $main_product_info,
            'assembly_products'=> $assembly_products,
            'languages'        => $languages,
        );
        $data['module']= "assembly_products";
        $data['page']  = "assemble_products/edit_prduct_form";
        echo Modules::run('template/layout', $data);
    }
    public function update_product()
    {
        $this->permission->check_label('manage_assembly_product')->update()->redirect();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', display('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('category_id', display('category_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('model', display('model'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->new_product();
        } else {
            $image = null;
            if ($_FILES['image_thumb']['name']) {
                //Chapter chapter add start
                $config['upload_path'] = './my-assets/image/product/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
                $config['max_size'] = "*";
                $config['max_width'] = "*";
                $config['max_height'] = "*";
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image_thumb')) {
                    $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                    redirect('assembly_products/back_assembly_products/index');
                } else {
                    $image = $this->upload->data();
                    $image_url = "my-assets/image/product/" . $image['file_name'];
                    //Resize image config
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $image['full_path'];
                    $config['maintain_ratio'] = FALSE;
                    $config['width'] = 400;
                    $config['height'] = 400;
                    $config['new_image'] = 'my-assets/image/product/thumb/' . $image['file_name'];
                    $this->upload->initialize($config);
                    $this->load->library('image_lib', $config);
                    $resize = $this->image_lib->resize();
                    //Resize image config
                    $thumb_image = $config['new_image'];

                    //Old image delete
                    $old_image = $this->input->post('old_img_lrg',TRUE);
                    $old_file = substr($old_image, strrpos($old_image, '/') + 1);
                    @unlink(FCPATH . 'my-assets/image/product/' . $old_file);

                    //Thumb image delete
                    $old_img_thumb = $this->input->post('old_thumb_image',TRUE);
                    $old_file_thumb = substr($old_img_thumb, strrpos($old_img_thumb, '/') + 1);
                    @unlink(FCPATH . 'my-assets/image/product/thumb/' . $old_file_thumb);
                }
            }
            $old_img_lrg = $this->input->post('old_img_lrg',TRUE);
            $old_thumb_image = $this->input->post('old_thumb_image',TRUE);
            $a_product_id  = $this->input->post('id',TRUE);
            $data = array(
                'product_name' => $this->input->post('name',TRUE),
                'product_model'=> $this->input->post('model',TRUE),
                'category_id'  => $this->input->post('category_id',TRUE),
                'price'        => $this->input->post('price',TRUE),
                'image_large_details' => (!empty($image_url) ? $image_url : $old_img_lrg),
                'image_thumb' => (!empty($thumb_image) ? $thumb_image : $old_thumb_image),
            );
            $result = $this->Assembly_products->update_product($data, $a_product_id);
            if($result) {
                $assembly_title             = $this->input->post('assembly_title',TRUE);
                $assembly_sub_title         = $this->input->post('assembly_sub_title',TRUE);
                $assembly_products_table_id = $this->input->post('assembly_products_table_id',TRUE);
                $assembly_title_id          = $this->input->post('assembly_title_id',TRUE);
                $required                   = $this->input->post('required',TRUE);
                $change_quantity            = $this->input->post('change_quantity',TRUE);
                //Product details for assembly
                $n = array_key_last($assembly_title);
                $delete_assembly = $this->Assembly_products->delete_assembly_product($a_product_id);
                $price =0;
                $assembly_product_details = [];
                for ($i=1; $i<=$n; $i++){
                    if(!empty($assembly_title[$i])){
                        $rest = $this->Assembly_products->update_assembly_product($assembly_products_table_id[$i]);
                        if($rest){
                            $title_assemble    = $assembly_title[$i];
                            $sub_title_assembly= $assembly_sub_title[$i];
                            $title_id_assembly = $assembly_title_id[$i];
                            $requireds         = (($required[$i])?'1':'0');
                            $quantity_change   = (($change_quantity[$i])?'1':'0');
                            $details = array(
                                'a_product_id'      => $a_product_id,
                                'assembly_title'    => $title_assemble,
                                'assembly_sub_title'=> $sub_title_assembly,
                                'required'          => $requireds,
                                'change_quantity'   => $quantity_change
                            );
                            $res = $this->db->insert('assembly_products', $details);
                            $a_title_id = $this->db->insert_id();
                            $product_name = $this->input->post('product_name_'.$title_id_assembly,TRUE);
                            $product_ids = $this->input->post('product_id_'.$title_id_assembly,TRUE);
                            $is_default = $this->input->post('is_default_'.$title_id_assembly,TRUE);
                            
                            for($j=0; $j<count($product_ids); $j++){
                                if(!empty($product_ids[$j])){
                                    $assembly_product_id = $a_title_id;
                                    $single_price = $this->check_admin_2d_variant_info($product_ids[$j]);
                                    if($product_ids[$j] == $is_default){ $price = $price+$single_price; }
                                    $assembly_product_details[] = array(
                                        'assembly_product_id'=> $assembly_product_id,
                                        'product_id'    => $product_ids[$j],
                                        'is_default'    => (($product_ids[$j] == $is_default) ? '1' : '0'),
                                    ); 
                                }
                            }
                        }
                    }
                }
                $trans_names = $this->input->post('trans_name',TRUE);
                $languages = $this->input->post('language',TRUE);
                if(!empty($languages)){
                    $data2 = [];
                    $language_array = [];
                    foreach ($languages as $key => $language){
                        if(!in_array($languages[$key], $language_array)){
                            $data2[] = array(
                                'language'   => $languages[$key],
                                'product_id' => $a_product_id,
                                'trans_name' => $trans_names[$key]
                            );
                        }else{
                            $this->session->set_userdata(array('error_message' => 'Multiple input of same language'));
                            redirect(base_url('assembly_products/back_assembly_products'));
                        }
                        $language_array[] = $data2[$key]['language'];
                    }
                    $this->db->delete('product_translation', array('product_id' => $a_product_id));
                    $result2 = $this->db->insert_batch('product_translation', $data2);
                }
                if(!empty($assembly_product_details)){
                    $assem_result = $this->db->insert_batch('assembly_products_details', $assembly_product_details);
                }
                $a_price = array(
                    'price' => $price,
                );
                $this->db->where('product_id',$a_product_id);
                $this->db->update('product_information',$a_price);

                $this->session->set_userdata(array('message' => display('successfully_updated')));
                redirect('assembly_products/back_assembly_products/index');
            }else {
                $this->session->set_userdata(array('error_message' => display('product_model_already_exist')));
                redirect('assembly_products/back_assembly_products/index');
            }
        }    
    }
    public function languages(){
        $settings = $this->db->select('language')->from('soft_setting')->where('setting_id',1)->get()->row();
        if ($this->db->table_exists($this->table)) {
            $fields = $this->db->field_data($this->table);
            $i = 1;
            foreach ($fields as $field) {
                if ($i++ > 2)
                    $result[$field->name] = ucfirst($field->name);
            }
            if (!empty($result)) {
                $langusges = array_diff($result, array($settings->language=>ucfirst($settings->language)));
                return $langusges;
            }
                return false;
        } else {
            return false;
        }
    }
    public function add_translation(){
        $count = $this->input->post('count',TRUE);
        $languages = $this->languages();
        $new_row_html = '<div style="margin-bottom: 35px;">
                            <div class="form-group row">
                                <label for="language" class="col-sm-3 col-form-label">'. display('language') .'</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <select class="form-control brand-control" id="language" name="language['.$count.']">
                                            <option value=""></option>';
                                            if(!empty($languages)){ foreach ($languages as $lkey => $lvalue) {
        $new_row_html .=                    '<option value="'.$lvalue.'" >'.$lvalue.'</option>';
                                            } }
        $new_row_html .=                '</select>
                                        <div class="input-group-addon btn btn-danger remove_row">
                                            <i class="ti-minus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="product_translation" class="col-sm-3 col-form-label"> '.display('product_name').'</label>
                                <div class="col-sm-6">
                                    <input class="form-control" name ="trans_name['.$count.']" id="product_translation" type="text" placeholder="'.display('product_name').'">
                                </div>
                            </div>
                        </div>';
        echo $new_row_html;
    }
}