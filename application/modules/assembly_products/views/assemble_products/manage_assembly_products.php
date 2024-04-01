<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Manage Product Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('manage_assembly_products') ?></h1>
	        <small><?php echo display('manage_your_product') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="<?php echo base_url()?>"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li class="active"><?php echo display('manage_assembly_products') ?></li>
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
                	<?php if($this->permission->check_label('add_assembly_product')->create()->access()){ ?>
	                <a href="<?php echo base_url('assembly_products/back_assembly_products/new_product')?>" class="btn -btn-info color4 color5 m-b-5 m-r-2">
	                	<i class="ti-plus"> </i><?php echo display('add_new_assembly_product')?>
	                </a>
	            	<?php } ?>
                </div>
            </div>
        </div>
		<!-- Manage Product report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('manage_assembly_products') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample3" class="table table-bordered table-striped table-hover">
		                        <thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('product_name') ?></th>
										<th><?php echo display('category') ?></th>
										<th><?php echo display('sell_price') ?></th>
										<th><?php echo display('image') ?>s</th>
										<th class="width_130"><?php echo display('action') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										if ($products_list) {
										foreach ($products_list as $v_product_list):
                                    ?>
                                    <tr>
                                        <td><?php echo $v_product_list['sl'] ?></td>
                                        <td>
                                            <a href="<?php echo base_url() . 'dashboard/Cproduct/product_details/'.$v_product_list['product_id']; ?>">
                                                 <?php echo html_escape($v_product_list['product_name'])?>-(<?php echo html_escape($v_product_list['product_model']) ?>) <i class="fa fa-shopping-bag pull-right" aria-hidden="true"></i></a>
                                        </td>
                                        <td><?php echo html_escape($v_product_list['category_name'])?></td>
                                        <td class="text-right">
                                            <?php echo(($position == 0) ? ($currency.' '.$v_product_list["price"]) : ($v_product_list["price"].' '.$currency)) ?>
                                        </td>
                                        <td class="text-center">
                                            <img src="<?php echo base_url() . $v_product_list['image_thumb']; ?>" class="img img-responsive center-block" height="50" width="50">
                                        </td>
                                        <td>
                                            <center>
                                                <?php echo form_open() ?>
                                                <?php if($this->permission->check_label('manage_assembly_product')->update()->access()){ ?>
                                                <a href="<?php echo base_url('assembly_products/back_assembly_products/product_edit/').$v_product_list['product_id']; ?>"
                                                   class="btn btn-info btn-sm" data-toggle="tooltip"
                                                   data-placement="left" title="<?php echo display('update') ?>"><i
                                                            class="fa fa-pencil" aria-hidden="true"></i></a>
                                                <?php }if($this->permission->check_label('manage_assembly_product')->delete()->access()){?>
                                                <a href="<?php echo base_url('assembly_products/back_assembly_products/product_delete/').$v_product_list['product_id']; ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('<?php echo display('are_you_sure_want_to_delete') ?>');"
                                                   data-toggle="tooltip" data-placement="right" title=""
                                                   data-original-title="<?php echo display('delete') ?> "><i
                                                            class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                <?php } ?>
                                                <?php echo form_close() ?>
                                            </center>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
								}
								?>
								</tbody>
		                    </table>
		                    <div class="text-right">
		                    <?php echo htmlspecialchars_decode(@$links); ?>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<!-- Manage Product End -->