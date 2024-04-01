<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Product invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product_assembly.js.php"></script>
<!-- Invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/invoice.js" type="text/javascript"></script>
<script src="<?php echo MOD_URL.'dashboard/assets/js/add_invoice_form.js'; ?>"></script>
<link rel="stylesheet" href="<?php echo MOD_URL.'assembly_products/assets/css/add_new_assembly_product.css' ?>">
<!-- Add New Invoice Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('update_assembly_product') ?></h1>
            <small><?php echo display('update_assembly_product') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('assembly_products') ?></a></li>
                <li class="active"><?php echo display('update_assembly_product') ?></li>
            </ol>
        </div>
    </section>
    <section class="content">
        <!-- Alert Message -->
        <?php
            $message = $this->session->userdata('message');
            if (isset($message)) {
                ?>
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $message ?>
                </div>
                <?php
                $this->session->unset_userdata('message');
            }
            $error_message = $this->session->userdata('error_message');
            if (isset($error_message)) {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $error_message ?>
                </div>
                <?php
                $this->session->unset_userdata('error_message');
            }
        ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if($this->permission->check_label('manage_assembly_product')->read()->access()){ ?>
                    <a href="<?php echo base_url('assembly_products/back_assembly_products/index'); ?>"
                       class="btn btn-primary color4 color5 m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('manage_assembly_products') ?></a>
                   <?php } ?>
                </div>
            </div>
        </div>
        <!--Add Invoice -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('update_assembly_product') ?></h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('#', array('class' => 'form-vertical')) ?>
                    <div class="panel-body">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#home"><?php echo display('product_information')?></a></li>
                            <li><a data-toggle="tab" href="#menu1"><?php echo display('product_translation')?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="home" class="tab-pane fade in active">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-4 col-form-label"><?php echo display('name') ?> <i class="text-danger">*</i></label>
                                                <div class="col-sm-8">
                                                    <input type="text" size="100" name="name" value="<?php echo $main_product_info->product_name ?>" class="produtSelection form-control" placeholder='<?php echo display('name') ?>' id="name" />
                                                    <input type="hidden" size="100" name="id" value="<?php echo $main_product_info->product_id ?>" class="produtSelection form-control" placeholder='<?php echo display('name') ?>' id="name" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="date" class="col-sm-4 col-form-label"><?php echo display('category') ?> <i class="text-danger">*</i></label>
                                                <div class="col-sm-8">
                                                    <select class="form-control select2 width_100p" id="category_id" name="category_id" required="">
                                                        <option value=""><?php echo display('select_one') ?></option>
                                                        <?php if ($category_list) {
                                                        foreach($category_list as $category){ ?>
                                                            <option <?php echo (($category['category_id'] = $main_product_info->category_id)?'selected' : '') ?> value="<?php echo html_escape($category['category_id']) ?>"><?php echo html_escape($category['category_name']); ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="image_thumb" class="col-sm-4 col-form-label"><?php echo display('image') ?></label>
                                                <div class="col-sm-8">
                                                    <input type="file" name="image_thumb" class="form-control" id="image_thumb">

                                                    <input type="hidden" name="old_thumb_image" value="<?php echo $main_product_info->image_thumb; ?>">
                                                    <input type="hidden" name="old_img_lrg" value="<?php echo $main_product_info->image_large_details; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="image_thumb" class="col-sm-4 col-form-label"><?php echo display('item_code') ?><i class="text-danger">*</i></label>
                                                <div class="col-sm-8">
                                                    <input type="text" tabindex="5" value="<?php echo $main_product_info->product_model ?>" class="form-control" name="model" placeholder="<?php echo display('item_code') ?>"  required  id="model"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><?php echo display('product_selection');?></div>
                                        <div class="panel-body" >
                                            <div class="row" id="addinvoiceItem2">
                                                <?php $assembly_count = 1; $total = count($assembly_products); foreach ($assembly_products as $assembly_product) { ?>
                                                    <div class="panel panel-default panel-assembly">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-sm-8 col-sm-offset-2">
                                                                    <div class="row title-row">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="assembly_title" class="col-sm-3"><?php echo display('title');?>
                                                                                    <i class="text-danger">*</i>
                                                                                </label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="assembly_title[<?php echo $assembly_count; ?>]" value="<?php echo $assembly_product['assembly_title']; ?>" class="form-control" required="" id="assembly_title">
                                                                                    <input type="hidden" name="assembly_title_id[<?php echo $assembly_count; ?>]" class="form-control" value="<?php echo $assembly_count; ?>" id="assembly_title_id">
                                                                                    <input type="hidden" name="assembly_products_table_id[<?php echo $assembly_count; ?>]" class="form-control" value="<?php echo $assembly_product['id']; ?>" id="assembly_title_id">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="sub_title" class="col-sm-3">
                                                                                    <?php echo display('sub_title');?>
                                                                                </label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="assembly_sub_title[<?php echo $assembly_count; ?>]" value="<?php echo $assembly_product['assembly_sub_title']; ?>" class="form-control" required="" id="assembly_sub_title">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 text-right">
                                                                    <button class="btn btn-danger mr-auto assemble_delete" type="button" value="<?php echo display('delete') ?>" onclick="deleteRow(this)"><i class="fa fa-minus-circle"></i></button>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-9 col-sm-offset-2">
                                                                    <div class="form-group row required-box">
                                                                        <label class="checkbox-inline">
                                                                            <input type="checkbox" name="required[<?php echo $assembly_count; ?>]" <?php echo (($assembly_product['required'] == 1)?'checked':'') ?> id="inlineCheckbox1"><strong><?php echo display('required') ?></strong>
                                                                        </label>
                                                                        <label class="checkbox-inline">
                                                                            <input type="checkbox" name="change_quantity[<?php echo $assembly_count; ?>]" <?php echo (($assembly_product['change_quantity'] == 1)?'checked':'') ?> id="inlineCheckbox1"><strong><?php echo display('change_quantity') ?></strong>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-8 col-sm-offset-2">
                                                                    <div>
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th><?php echo display('product') ?><span class="color-red">*</span></th>
                                                                                    <th><?php echo display('is_default') ?></th>
                                                                                    <th><?php echo display('action') ?></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="assembly_product_area_<?php echo $assembly_count; ?>">
                                                                                <?php
                                                                                    $product_row_rount = 0;
                                                                                    $this->db->select('a.product_id,a.is_default,b.product_name');
                                                                                    $this->db->from('assembly_products_details a');
                                                                                    $this->db->join('product_information b','b.product_id = a.product_id','left');
                                                                                    $this->db->where('assembly_product_id',$assembly_product['id']);
                                                                                    $assembly_products_details = $this->db->get()->result_array();
                                                                                    $a_count = count($assembly_products_details);
                                                                                    foreach ($assembly_products_details as $assembly_details) {
                                                                                ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <input type="text" name="product_name_<?php echo $assembly_count; ?>[<?php echo $product_row_rount; ?>]" onkeyup="invoice_productList(1);" class="form-control productSelection" placeholder='<?php echo display('product_name') ?>' value="<?php echo $assembly_details['product_name'] ; ?>" required="" id="product_name">
                                                                                        <input type="hidden" value="<?php echo $assembly_details['product_id'] ; ?>" class="autocomplete_hidden_value product_id_<?php echo $assembly_count; ?>" name="product_id_<?php echo $assembly_count; ?>[<?php echo $product_row_rount; ?>]"/>
                                                                                        <input type="hidden" class="sl" value="1">
                                                                                        <input type="hidden" class="baseUrl" value="<?php echo base_url(); ?>"/>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="radio" <?php echo (($assembly_details['is_default'] == 1)?'checked':'') ?> name="is_default_<?php echo $assembly_count; ?>" value="<?php echo $assembly_details['product_id'] ; ?>" class="is_default_<?php echo $assembly_count; ?> default_box">
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php if($assembly_count==1 && $product_row_rount==0){ ?>
                                                                                        <button class="btn btn-success mr-auto" type="button" onclick="addFirstProductRow('assembly_product_area_<?php echo $assembly_count; ?>','<?php echo $a_count ?>');"><i class="ti-plus"></i></button>
                                                                                        <?php }elseif($product_row_rount==0){?>
                                                                                            <button class="btn btn-success mr-auto" type="button" onclick="addProductRow('assembly_product_area_<?php echo $assembly_count; ?>','<?php echo $assembly_count ?>');"><i class="ti-plus"></i></button>
                                                                                        <?php }else{ ?>
                                                                                        <button class="btn btn-danger mr-auto" type="button" onclick="removeProductRow(this);"><i class="ti-minus"></i></button>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php $product_row_rount++; } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php $assembly_count++; } ?>
                                            </div>
                                        </div>
                                        <div class="panel-footer">
                                            <input type="button" id="add-invoice-item" class="btn btn-info color4 color5" name="add-invoice-item" onClick="addInputField('addinvoiceItem2','<?php echo $total+1 ?>');" value="<?php echo display('add_new_item') ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <div class="panel-body ">
                                    <?php 
                                        $a_product_languages = $this->db->select('*')
                                            ->from('product_translation')
                                            ->where('product_id',$main_product_info->product_id)
                                            ->get()
                                            ->result();
                                    ?>
                                    <?php if($a_product_languages){ foreach ($a_product_languages as $key => $a_product_language) { ?>
                                        <div class="trans_row" style="margin-bottom: 35px;">
                                            <div class="form-group row">
                                                <label for="language" class="col-sm-3 col-form-label"><?php echo display('language')?></label>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <select class="form-control" id="language" style="width: 100%" name="language[0]">
                                                            <option value=""></option>
                                                            <?php if(!empty($languages)){ foreach ($languages as $lkey => $lvalue) { ?>
                                                            <option value="<?php echo $lvalue; ?>"  <?php echo ($a_product_language->language==$lvalue)?'selected':'' ?> ><?php echo $lvalue; ?></option>
                                                            <?php 
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <?php if($key == 0){ ?>
                                                        <div class="input-group-addon btn btn-success" id="add_row">
                                                            <i class="ti-plus"></i>
                                                        </div>
                                                        <?php }else{ ?>
                                                        <div class="input-group-addon btn btn-danger remove_data_row">
                                                            <i class="ti-minus"></i>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="category_translation" class="col-sm-3 col-form-label"><?php echo display('category_name')?></label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" name ="trans_name[0]" id="category_translation" value="<?php echo $a_product_language->trans_name; ?>" type="text" placeholder="<?php echo display('category_name') ?>">
                                                </div>
                                            </div>
                                        </div>
                                    <?php }  }else{ ?>
                                        <div class="trans_row" style="margin-bottom: 35px;">
                                            <div class="form-group row">
                                                <label for="language" class="col-sm-3 col-form-label"><?php echo display('language')?></label>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <select class="form-control" id="language" style="width: 100%" name="language[0]">
                                                            <option value=""></option>
                                                            <?php if(!empty($languages)){ foreach ($languages as $lkey => $lvalue) { ?>
                                                            <option value="<?php echo $lvalue; ?>" ><?php echo $lvalue; ?></option>
                                                            <?php 
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <div class="input-group-addon btn btn-success" id="add_row">
                                                            <i class="ti-plus"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="category_translation" class="col-sm-3 col-form-label"><?php echo display('category_name')?></label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" name ="trans_name[0]" id="category_translation" type="text" placeholder="<?php echo display('category_name') ?>">
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>
                                    <div class="new_row"></div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 25px;">
                                <div class="col-sm-12">
                                    <div class="form-group row text-center">
                                        <button class="btn btn-success" type="submit">Save Product</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
            <script src="<?php echo MOD_URL.'assembly_products/assets/js/edit_new_assembly_product.js'; ?>"></script>
        </div>
    </section>
</div>
<!-- Invoice Report End -->