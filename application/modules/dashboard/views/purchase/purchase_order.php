<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Manage Purchase Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('create_purchase_order') ?></h1>
	        <small><?php echo display('manage_your_purchase_orders') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('purchase') ?></a></li>
	            <li><a href="#"><?php echo display('purchase_order') ?></a></li>
	            <li class="active"><?php echo display('create_purchase_order') ?></li>
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
                	<?php if($this->permission->check_label('purchase_order')->create()->access()){ ?>
                  	<a href="<?php echo base_url('dashboard/Cpurchase/add_purchase_order')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('add_purchase_order')?></a>  
                  	<?php } ?>
                </div>
            </div>
        </div>


		<!-- Manage Purchase report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('purchase_order') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample4" class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('purchase_order') ?></th>
										<th><?php echo display('date') ?></th>
										<th><?php echo display('store') ?></th>
										<th><?php echo display('supplier') ?></th>
										<th><?php echo display('total_amount') ?></th>
										<th><?php echo display('status') ?></th>
										<th><?php echo display('action') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php 
								if (!empty($order_list)) { $i=1;
									foreach ($order_list as $purchase) {
								?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td>
											<a href="<?php echo base_url().'dashboard/Cpurchase/manage_purorder/view/'.$purchase['pur_order_id']; ?>">
                                                <?php echo html_escape($purchase['pur_order_no'])?><i class="fa fa-tasks pull-right" aria-hidden="true"></i>
                                            </a>
										</td>
										<td><?php echo html_escape($purchase['purchase_date'])?></td>
										<td>
											<?php echo html_escape($purchase['store_name'])?>
										 </td>
										 <td>
											<a href="<?php echo base_url().'dashboard/Csupplier/supplier_details/'.$purchase['supplier_id']; ?>">
                                                <?php echo html_escape($purchase['supplier_name'])?> <i class="fa fa-user pull-right" aria-hidden="true"></i>
                                            </a>
										</td>
										
										<td class="text-right"><?php echo html_escape(($position==0)?$currency.' '.$purchase['grand_total_amount']:$purchase['grand_total_amount'].' '.$currency) ?></td>
										<td>
											<?php 
											if($purchase['approve_status']) {
												echo '<span class="label label-primary">'.display('approved').'</span> ';
											}
											if($purchase['receive_status']) {
												echo '<span class="label label-success">'.display('received').'</span> ';
											}
											?>
										</td>
										<td>
											<center>
												<a href="<?php echo base_url().'dashboard/Cpurchase/purchase_order_print/'.$purchase['pur_order_id']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('view_details') ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
											<?php if($this->permission->check_label('purchase_order')->update()->access()){ 
												if($purchase['receive_status'] != '1'){
											?>
												<a href="<?php echo base_url().'dashboard/Cpurchase/edit_purchase_order/'.$purchase['pur_order_id']; ?>" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
											<?php } } ?>
											<?php if($this->permission->check_label('purchase_order')->delete()->access()){
													if($purchase['receive_status'] != '1'){
											 ?>
												<a href="<?php echo base_url('dashboard/Cpurchase/purchase_order_delete/'.$purchase['pur_order_id'])?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo display('are_you_sure_want_to_delete')?>');" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
											<?php  } } ?>
											</center>
										</td>
									</tr>
								<?php 
									}
								} 
								?>
								</tbody>
		                    </table>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<!-- Manage Purchase End -->