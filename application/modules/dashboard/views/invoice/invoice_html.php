<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?php echo MOD_URL . 'dashboard/assets/js/print.js'; ?>"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('invoice_details') ?></h1>
            <small><?php echo display('invoice_details') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active"><?php echo display('invoice_details') ?></li>
            </ol>
        </div>
    </section>
    <!-- Main content -->
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
                <div class="panel panel-bd">
                    <div id="printableArea">
                        <link href="<?php echo MOD_URL . 'dashboard/assets/css/print.css'; ?>" rel="stylesheet"
                            type="text/css" />
                        <style type="text/css">
                        @media print {
                            .panel-body {
                                font-size: 10px;
                            }

                            .cominfo_div {
                                display: inline-block;
                                width: 30%;
                            }

                            .cus_div {
                                display: inline-block;
                                margin-left: 4%;
                                width: 25%;
                                margin-top: 6%
                            }

                            .qr_div {
                                display: width: 10%;
                            }

                            .width_30p {
                                width: 30%;
                            }

                            .width_70p {
                                width: 70%;
                            }
                        }
                        </style>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6 cominfo_div" style="float: left;">
                                    <img src="<?php if (isset($Soft_settings[0]['invoice_logo'])) {
                                                    echo base_url() . $Soft_settings[0]['invoice_logo'];
                                                } ?>" class="img img-responsive inv_logo" alt="logo">
                                    <br>
                                    <span
                                        class="label label-success-outline m-r-15 p-10"><?php echo display('billing_from') ?></span>
                                    <address class="mt_10">
                                        <strong>
                                            <?php echo html_escape($company_info[0]['company_name']); ?></strong><br>
                                        <div><?php echo html_escape($company_info[0]['address']); ?> :
                                            <?php echo display('address') ?></div>
                                        <div><abbr><?php echo display('mobile'); ?> :
                                                <?php echo html_escape($company_info[0]['mobile']); ?></abbr></div>
                                        <div><abbr><?php echo display('email') ?> :
                                            </abbr><?php echo html_escape($company_info[0]['email']); ?></div>
                                        <div><abbr><?php echo display('website') ?> :
                                            </abbr><?php echo html_escape($company_info[0]['website']); ?></div>
                                        <div>
                                            <abbr><?php echo display('payment_status') ?>:</abbr>
                                            <span
                                                class="<?php echo (($total_amount == $paid_amount) ? 'text-success' : 'text-danger'); ?>">
                                                <?php echo (($total_amount == $paid_amount) ? '<strong>Paid </strong>' : '<strong>Due</strong>'); ?>
                                            </span>
                                        </div>
                                        <div>
                                            <?php $store = $this->db->select('store_name')->from('store_set')->where('store_id', $store_id)->get()->row(); ?>
                                            <abbr><?php echo display('branch') ?>:</abbr>
                                            <?php echo html_escape(@$store->store_name); ?>
                                        </div>
                                        <?php
                                        $company_vat = $this->db->select('vat_no')->from('company_information')->where('status', 1)->get()->row();
                                        if (!empty($company_vat)) {
                                        ?>
                                        <div>
                                            <abbr><?php echo display('our_vat_no') ?>:</abbr><?php echo html_escape($company_vat->vat_no); ?>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </address>
                                </div>

                                <div class="col-sm-3 qr_div" style="float: left;">
                                    <?php
                                    $base_encoded = base64_encode($company_info[0]['company_name'] . '  ' . $company_vat->vat_no . '  ' . $invoice_all_data[0]['created_at'] . '  ' . $total_amount . '  ' . $invoice_all_data[0]['total_vat']);
                                    ?>
                                    <?php
                                    $checkQr = $this->db->select("isActive")->from("captcha_print_setting")->get()->row();
                                    if (@$checkQr->isActive == 1) {
                                    ?>
                                    <img src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=<?php echo $base_encoded; ?>"
                                        alt="Invoice QR code">
                                    <?php } ?>
                                </div>
                                <div class="col-sm-2 text-left cus_div" style="float: right; ">
                                    <h4 class="m-t-0">
                                        <?php if ($total_amount == $paid_amount) { ?>
                                        <span class="label label-success-outline "><?php echo display('paid') ?></span>
                                        <?php } elseif (($paid_amount > 0) && ($paid_amount < $total_amount)) { ?>
                                        <span
                                            class="label label-warning-outline"><?php echo display('partial_paid') ?></span>
                                        <?php } elseif ($paid_amount == 0) {
                                        ?>
                                        <span class="label label-danger-outline"><?php echo display('unpaid') ?></span>
                                        <?php } ?>
                                    </h4>
                                    <h2 class="m-t-0" style="margin-bottom: 0px;"><?php echo display('invoice') ?></h2>
                                    <br>
                                    <div>
                                        <?php echo display('invoice_no') ?> : <?php echo html_escape($invoice_no); ?>
                                    </div>

                                    <div>
                                        <?php echo display('order_no') ?> :
                                        <?php echo (!empty($order_no['0']->order_no)) ? $order_no['0']->order_no : 'N/A' ?>
                                    </div>
                                    <div>
                                        <?php echo display('quotation_no') ?> :
                                        <?php echo (!empty($quotation_no['0']->quotation_no)) ? $quotation_no['0']->quotation_no : 'N/A' ?>
                                    </div>
                                    <div>
                                        <?php echo display('invoice_date') ?> :<?php echo html_escape($final_date) ?>
                                    </div>
                                    <div class="m-b-15">
                                        <?php echo display('invoice_time') ?> :<?php echo html_escape($invoice_time) ?>
                                    </div>
                                    <span
                                        class="label label-success-outline m-r-15"><?php echo display('billing_to') ?></span>
                                    <?php if (!strcmp($customer_mobile, $ship_customer_mobile)) { ?>
                                    <address class="mt_10">
                                        <strong>
                                            <?php echo display('customer_name') ?>
                                            :<?php echo html_escape($customer_name); ?>
                                        </strong>
                                        <br>
                                        <abbr><?php echo display('address') ?> :</abbr>
                                        <?php if ($customer_address) { ?>
                                        <c class="ctext"><?php echo html_escape($customer_address) ?></c>
                                        <?php } ?><br>
                                        <abbr lang="ar"
                                            dir="rtl"><?php if ($customer_mobile) { ?><?php echo html_escape($customer_mobile) ?><?php } ?>
                                            :<?php echo display('mobile') ?></abbr>

                                        <?php if ($customer_email) {  ?>
                                        <br>
                                        <abbr><?php echo display('email') ?> :</abbr>
                                        <?php echo html_escape($customer_email); ?>
                                        <?php } ?>
                                        <?php if ($vat_no) {  ?>
                                        <br>
                                        <abbr><?php echo display('vat_for_customer') ?>:</abbr>
                                        <?php echo html_escape($vat_no); ?>
                                        <?php } ?>

                                    </address>
                                    <?php if($shipping_cname) { ?>
                                    <span
                                        class="label label-success-outline m-r-15"><?php $shipping = explode(" ",display('shipping_method')); echo $shipping[0]; ?></span>
                                    <address class="mt_10">
                                        <strong>
                                            <?php echo display('customer_name') ?>
                                            :<?php echo html_escape($shipping_cname); ?>
                                        </strong>
                                        <br>
                                        <abbr><?php echo display('address') ?> :</abbr>
                                        <?php if ($shipping_address) { ?>
                                        <c class="ctext"><?php echo html_escape($shipping_address) ?></c>
                                        <?php } ?><br>
                                        <abbr lang="ar"
                                            dir="rtl"><?php if ($shipping_cmobile) { ?><?php echo html_escape($shipping_cmobile) ?><?php } ?>
                                            :<?php echo display('mobile') ?></abbr>

                                        <?php if ($shipping_cemail) {  ?>
                                        <br>
                                        <abbr><?php echo display('email') ?> :</abbr>
                                        <?php echo html_escape($shipping_cemail); ?>
                                        <?php } ?>
                                    </address>
                                    <?php } ?>

                                    <?php } else { ?>
                                    <address class="mt_10">
                                        <strong><?php echo html_escape($ship_customer_name) ?> </strong><br>
                                        <abbr><?php echo display('address') ?>:</abbr>
                                        <?php if ($ship_customer_short_address) { ?>
                                        <c class="ctext">
                                            <?php echo html_escape($ship_customer_short_address); ?>
                                        </c>
                                        <?php } ?><br>
                                        <abbr><?php echo display('mobile') ?> :
                                            <?php if ($ship_customer_mobile) { ?>{ship_customer_mobile}<?php } ?></abbr>
                                        <?php if ($ship_customer_email) { ?>
                                        <br>
                                        <abbr><?php echo display('email') ?>:</abbr><?php echo html_escape($ship_customer_email); ?>
                                        <?php } ?>
                                    </address>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="table-responsive m-b-20">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo display('sl') ?></th>
                                            <th><?php echo display('item_code') ?></th>
                                            <th><?php echo display('item_picture') ?></th>
                                            <th><?php echo display('product_name') ?></th>
                                            <th><?php echo display('variant') ?></th>
                                            <th><?php echo display('unit') ?></th>
                                            <th><?php echo display('batch_no') ?></th>
                                            <th><?php echo display('quantity') ?></th>
                                            <th><?php echo display('unit_price_before_VAT') ?></th>
                                            <th><?php echo display('discount') ?></th>
                                            <th><?php echo display('vat_rate') ?></th>
                                            <th><?php echo display('vat_value') ?></th>
                                            <th><?php echo display('total_value') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($invoice_all_data)) {
                                            $total_quantity = $total_return_amount = $i_grand_discount = $i_total_discount_price_amount = $i_total_discount_price = $i_grand_amount = 0;
                                            foreach ($invoice_all_data as $invoice) {
                                        ?>
                                        <tr>
                                            <td><?php echo html_escape($invoice['sl']); ?></td>
                                            <td><?php echo html_escape($invoice['product_id']); ?></td>
                                            <td><img src="<?php echo  base_url() . (!empty(html_escape($invoice['image_thumb'])) ? html_escape($invoice['image_thumb']) : 'assets/img/icons/default.jpg') ?>"
                                                    width="50" height="50"></td>
                                            <td>
                                                <strong><?php echo html_escape($invoice['product_name']); ?> -
                                                    (<?php echo html_escape($invoice['product_model']); ?>)</strong><br>
                                                <?php
                                                        $arabic_name = $this->db->select('trans_name')->from('product_translation')->where('language', 'Arabic')->where('product_id', $invoice['product_id'])->get()->row();
                                                        if (!empty($arabic_name->trans_name)) { ?>
                                                <strong dir="rtl"
                                                    lang="ar"><?php echo html_escape($arabic_name->trans_name); ?></strong>
                                                <?php
                                                        }

                                                        ?>
                                            </td>
                                            <td><?php echo html_escape($invoice['variant_name']);
                                                        if (!empty($invoice['variant_color'])) {
                                                            $cvarinfo = $this->db->select('variant_name')->from('variant')->where('variant_id', $invoice['variant_color'])->get()->row();
                                                            if (!empty($cvarinfo)) {
                                                                echo ', ' . $cvarinfo->variant_name;
                                                            }
                                                        }
                                                        ?>
                                            </td>
                                            <td><?php echo html_escape($invoice['unit_short_name']); ?></td>
                                            <td><?php echo html_escape($invoice['batch_no']); ?></td>
                                            <td><?php echo html_escape($invoice['quantity']); ?></td>
                                            <td><?php echo (($position == 0) ? $currency . " " . $invoice['rate'] : $invoice['rate'] . " " . $currency) ?>
                                            </td>
                                            <td><?php echo (($position == 0) ? $currency . " " . $invoice['discount'] : $invoice['discount'] . " " . $currency) ?>
                                            </td>
                                            <?php
                                                    $item_tax = $this->db->select('*')->from('tax_product_service')->where('product_id', $invoice['product_id'])->where('tax_id', '52C2SKCKGQY6Q9J')->get()->row();
                                                    ?>
                                            <td><?php if (!empty($item_tax)) {
                                                            echo $item_tax->tax_percentage . '%';
                                                        } else {
                                                            echo '0%';
                                                        } ?></td>

                                            <td>
                                                <?php
                                                        if (!empty($item_tax)) {
                                                            echo (($position == 0) ? $currency . " " . ($item_tax->tax_percentage * ($invoice['total_price'] - ($invoice['discount'] * $invoice['quantity'])) / 100) : ($item_tax->tax_percentage * ($invoice['total_price'] - ($invoice['discount'] * $invoice['quantity'])) / 100) . " " . $currency);
                                                        } else {
                                                            echo (($position == 0) ? $currency . " " . 0 : 0 . " " . $currency);
                                                        }
                                                        ?>
                                            </td>

                                            <td><?php if (!empty($invoice['total_price'])) {
                                                            echo (($position == 0) ? $currency . " " . $invoice['total_price'] : $invoice['total_price'] . " " . $currency);
                                                        } ?></td>
                                        </tr>
                                        <?php
                                                $invoice['price'] = ($invoice['rate']);
                                                $i_total_price    = $invoice['quantity'] * ($invoice['price']);
                                                $i_total_discount_price = $invoice['quantity'] * ($invoice['price'] - $invoice['discount']);
                                                $i_discount_amount = $invoice['discount'] * ($invoice['quantity']);
                                                $i_grand_discount += $i_discount_amount;
                                                $i_total_discount_price_amount += $i_total_discount_price;
                                                $i_grand_amount   += $i_total_price;
                                                ?>
                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="width_70p ft_left">
                                        <?php if (!empty($cardpayments)) {  ?>
                                        <div class="col-sm-7">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tr class="info">
                                                        <th><?php echo display('card_type'); ?></th>
                                                        <th><?php echo display('card_no'); ?></th>
                                                        <th><?php echo display('pay_amount'); ?></th>
                                                    </tr>
                                                    <?php foreach ($cardpayments as $payitem) { ?>
                                                    <tr>
                                                        <td><?php echo html_escape($payitem['card_type']); ?></td>
                                                        <td><?php echo html_escape($payitem['card_no']); ?></td>
                                                        <td><?php echo html_escape($payitem['amount']); ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </table>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div>
                                            <p><strong><?php echo htmlspecialchars_decode($invoice_details) ?></strong>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="width_30p ft_left">

                                        <table class="table">
                                            <tr>
                                                <th class="grand_total">Total price before Discount: </th>
                                                <td>
                                                    <?php echo (($position == 0) ? $currency . " " . $i_grand_amount : $i_grand_amount . " " . $currency); ?>
                                                </td>
                                            </tr>

                                            <?php if ($invoice_all_data[0]['total_discount'] != 0) { ?>
                                            <tr>
                                                <th class="grand_total"> Product Discount Value: </th>
                                                <td>
                                                    <?php echo (($position == 0) ? $currency . " " . $invoice_all_data[0]['total_discount'] : $invoice_all_data[0]['total_discount'] . " " . $currency); ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="grand_total">Total price after Discount: </th>
                                                <td>
                                                    <?php echo (($position == 0) ? $currency . " " . $i_total_discount_price_amount : $i_total_discount_price_amount . " " . $currency); ?>
                                                </td>
                                            </tr>
                                            <?php } else { ?>
                                            <tr>
                                                <th class="grand_total"> Discount Value : </th>
                                                <td>
                                                    <?php echo (($position == 0) ? $currency . " " . 0 : 0 . " " . $currency); ?>
                                                </td>
                                            </tr>
                                            <?php } ?>

                                            <?php if ($invoice_all_data[0]['invoice_discount'] != 0) { ?>
                                            <tr>
                                                <th class="invoice_discount">Total Product discount
                                                    :
                                                </th>
                                                <td class="invoice_discount">
                                                    <?php echo (($position == 0) ? $currency . " " . $invoice_discount : $invoice_discount . " " . $currency) ?>
                                                </td>
                                            </tr>
                                            <?php } ?>

                                            <?php if ($invoice_all_data[0]['service_charge'] != 0) { ?>
                                            <tr>
                                                <th class="service_charge"><?php echo display('service_charge') ?> :
                                                </th>
                                                <td class="service_charge">
                                                    <?php echo (($position == 0) ? "$currency " . " $service_charge" : "$service_charge " . " $currency") ?>
                                                </td>
                                            </tr>
                                            <?php } ?>


                                            <?php if ($invoice_all_data[0]['shipping_charge'] != 0) { ?>
                                            <tr>
                                                <th class="shipping_charge"><?php echo display('shipping_charge') ?> :
                                                </th>
                                                <td class="shipping_charge">
                                                    <?php echo (($position == 0) ? "$currency " . " $shipping_charge" : "$shipping_charge " . " $currency") ?>
                                                </td>
                                            </tr>
                                            <?php } ?>

                                            <?php if (!empty($invoice_all_data[0]['shipping_method'])) { ?>
                                            <tr>
                                                <th class="shipping_method"><?php echo display('shipping_method') ?> :
                                                </th>
                                                <td class="shipping_method"><?php echo html_escape($shipping_method); ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <?php $taxAmount = 0; ?>
                                            <?php
                                            $this->db->select('a.*,b.tax_name');
                                            $this->db->from('tax_collection_summary a');
                                            $this->db->join('tax b', 'a.tax_id = b.tax_id');
                                            $this->db->where('a.invoice_id', $invoice_id);
                                            $this->db->where('a.tax_id', 'H5MQN4NXJBSDX4L');
                                            $tax_info = $this->db->get()->row();
                                            if ($tax_info) { ?>
                                            <tr>
                                                <th class="total_igst">The total VAT value (15%) :</th>
                                                <td class="total_cgst">
                                                    <?php echo (($position == 0) ? $currency . " " . $tax_info->tax_amount : $tax_info->tax_amount . " " . $currency);
                                                        $taxAmount = $tax_info->tax_amount; ?>
                                                </td>
                                            </tr>
                                            <?php }
                                            $this->db->select('a.*,b.tax_name');
                                            $this->db->from('tax_collection_summary a');
                                            $this->db->join('tax b', 'a.tax_id = b.tax_id');
                                            $this->db->where('a.invoice_id', $invoice_id);
                                            $this->db->where('a.tax_id', '52C2SKCKGQY6Q9J');
                                            $tax_info = $this->db->get()->row();
                                            if ($tax_info) { ?>
                                            <tr>
                                                <th class="total_igst">The total VAT value:</th>
                                                <td class="total_sgst">
                                                    <?php echo (($position == 0) ? $currency . " " . $tax_info->tax_amount : $tax_info->tax_amount . " " . $currency);
                                                        $taxAmount = $tax_info->tax_amount; ?>
                                                </td>
                                            </tr>
                                            <?php }
                                            $this->db->select('a.*,b.tax_name');
                                            $this->db->from('tax_collection_summary a');
                                            $this->db->join('tax b', 'a.tax_id = b.tax_id');
                                            $this->db->where('a.invoice_id', $invoice_id);
                                            $this->db->where('a.tax_id', '5SN9PRWPN131T4V');
                                            $tax_info = $this->db->get()->row();
                                            if ($tax_info) {
                                            ?>
                                            <tr>
                                                <th class="total_igst">The total VAT value:</th>
                                                <td class="total_igst">
                                                    <?php echo (($position == 0) ? $currency . " " . $tax_info->tax_amount : $tax_info->tax_amount . " " . $currency);
                                                        $taxAmount = $tax_info->tax_amount; ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <?php if ($invoice_all_data[0]['total_discount'] != 0) { ?>
                                            <tr>
                                                <th class="grand_total">Total with VAT</th>
                                                <td class="grand_total">
                                                    <?php echo (($position == 0) ? $currency . " " . ($i_total_discount_price_amount + $taxAmount) : ($i_total_discount_price_amount + $taxAmount) . " " . $currency) ?>
                                                </td>
                                            </tr>
                                            <?php } else { ?>
                                            <tr>
                                                <th class="grand_total">Total with VAT</th>
                                                <td class="grand_total">
                                                    <?php echo (($position == 0) ? $currency . " " . $total_amount : $total_amount . " " . $currency) ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <th class="bt_bb_0"><?php echo display('paid_ammount') ?>
                                                    :
                                                </th>
                                                <td class="bt_bb_0">
                                                    <?php echo (($position == 0) ? $currency . " " . $paid_amount : $paid_amount . " " . $currency) ?>
                                                </td>
                                            </tr>
                                            <?php if ($invoice_all_data[0]['due_amount'] != 0) { ?>
                                            <tr>
                                                <th><?php echo display('due') ?> : </th>
                                                <td><?php echo (($position == 0) ? $currency . " " . $due_amount : $due_amount . " " . $currency) ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            <div class="row" style="padding: 50px  0px 0px; ">
                                <div class="col-sm-12">
                                    <table class="table">
                                        <thead>
                                            <th class="text-left" style="width: 50%">
                                                <strong style="border-top:1px solid #ddd">Buyer's signature </strong>
                                            </th>

                                            <th class="text-right" style="width: 50%">
                                                <strong style="border-top:1px solid #ddd">Seller's signature </strong>
                                            </th>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer text-left">
                        <?php if ($this->permission->check_label('manage_sale')->read()->access()) { ?>
                        <a class="btn btn-danger"
                            href="<?php echo base_url('dashboard/Cinvoice/manage_invoice'); ?>"><?php echo display('back') ?></a>
                        <?php } ?>
                        <a class="btn btn-info" href="<?php echo current_url(); ?>"
                            onclick="printPageDiv('printableArea')"><span class="fa fa-print"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->