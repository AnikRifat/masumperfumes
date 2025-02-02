"use strict";
var csrf_test_name = $("#CSRF_TOKEN").val();
$("#store_hide").css("display", "none");
function bank_info_show(payment_type) {
  if (payment_type.value == "1") {
    document.getElementById("store_hide").style.display = "none";
    document.getElementById("wearhouse_hide").style.display = "block";
  } else {
    document.getElementById("wearhouse_hide").style.display = "none";
    document.getElementById("store_hide").style.display = "block";
  }
}

//Product purchase or list
function product_pur_or_list(sl) {
  var supplier_id = $("#supplier_id").val();
  var product_name = $("#product_name_" + sl).val();

  //Supplier id existing check
  if (supplier_id == 0) {
    alert(display("please_select_supplier"));
    $("#product_name_" + sl).val("");
    return false;
  }

  // Auto complete ajax
  var options = {
    minLength: 0,
    source: function (request, response) {
      $.ajax({
        url: base_url + "dashboard/Cpurchase/product_search_by_supplier",
        method: "post",
        dataType: "json",
        data: {
          csrf_test_name: csrf_test_name,
          term: request.term,
          supplier_id: $("#supplier_id").val(),
          product_name: product_name,
        },
        success: function (data) {
          response(data);
        },
      });
    },
    focus: function (event, ui) {
      $(this).val(ui.item.label);
      return false;
    },
    select: function (event, ui) {
      $(this)
        .parent()
        .parent()
        .find(".autocomplete_hidden_value")
        .val(ui.item.value);
      var sl = $(this).parent().parent().find(".sl").val();
      var id = ui.item.value;
      var dataString = "csrf_test_name=" + csrf_test_name + "&product_id=" + id;
      var avl_qntt = "avl_qntt_" + sl;
      var price_item = "price_item_" + sl;
      var variant_id = "variant_id_" + sl;
      var color_variant = "color_variant_" + sl;
      $.ajax({
        type: "POST",
        url: base_url + "dashboard/Cstore/retrieve_product_data",
        data: dataString,
        cache: false,
        success: function (data) {
          var obj = JSON.parse(data);
          $("#" + price_item).val(obj.supplier_price);
          $("#" + avl_qntt).val(obj.total_product);
          $("#" + variant_id).html(obj.variant);
          $("#" + color_variant)
            .empty()
            .append(obj.variant_color);
        },
      });

      $(this).unbind("change");
      return false;
    },
  };
  $("body").on("keydown.autocomplete", ".product_name", function () {
    $(this).autocomplete(options);
  });
}

// Counts and limit for purchase order
var count = 2;
var limits = 500;
//Add Purchase Order Field
function addPurchaseOrderField(divName) {
  if (count == limits) {
    alert("You have reached the limit of adding " + count + " inputs");
  } else {
    var newdiv = document.createElement("tr");
    var tabin = "product_name_" + count;
    var batch_no = $("#generated_batch").val();
    $("select.form-control:not(.dont-select-me)").select2({
      placeholder: "Select option",
      allowClear: true,
    });
    newdiv.innerHTML =
      '<td><input type="text" name="product_name" required class="form-control product_name productSelection" onkeyup="product_pur_or_list(' +
      count +
      ');" placeholder="' +
      display("product_name") +
      '" id="product_name_' +
      count +
      '" tabindex="5" ><input type="hidden" class="autocomplete_hidden_value product_id_' +
      count +
      '" name="product_id[' +
      count +
      ']" id="SchoolHiddenId"/><input type="hidden" class="sl" value="' +
      count +
      '"></td><td class="text-center"><div class="variant_id_div"><select name="variant_id[' +
      count +
      ']" id="variant_id_' +
      count +
      '" class="form-control variant_id width_100p" required=""><option value=""></option></select></div><div><select name="color_variant[' +
      count +
      ']" id="color_variant_' +
      count +
      '" class="form-control color_variant width_100p" ><option value=""></option></select></div></td><td class="text-right"><input type="text" id="batch_no_' +
      count +
      '" name="batch_no[' +
      count +
      ']" value="' +
      batch_no +
      count +
      '" class="form-control text-right" placeholder="0" readonly /></td><td class="text-right"><input type="text" id="expiry_date_' +
      count +
      '" name="expiry_date[' +
      count +
      ']" class="form-control datepicker" placeholder="' +
      display("enter_expire_date") +
      '"/></td><td class="text-right"><input type="number" id="avl_qntt_' +
      count +
      '" class="form-control text-right" placeholder="0" readonly /></td><td class="text-right"><input type="number" name="product_quantity[' +
      count +
      ']" id="total_qntt_' +
      count +
      '" onkeyup="calculate_add_purchase(' +
      count +
      ')" onchange="calculate_add_purchase(' +
      count +
      ')"  class="form-control text-right p_quantity" placeholder="0" min="0" required/></td><td><input type="number" name="product_rate[' +
      count +
      ']" id="price_item_' +
      count +
      '" class="price_item1 text-right form-control" placeholder="0.00" min="0" onkeyup="calculate_add_purchase(' +
      count +
      ')" onchange="calculate_add_purchase(' +
      count +
      ')"/></td><td><input type="number" name="discount[' +
      count +
      ']" onkeyup="calculate_add_purchase(' +
      count +
      ');"onchange="calculate_add_purchase(' +
      count +
      ');" id="discount_' +
      count +
      '"class="form-control text-right" placeholder="0.00" min="0"/></td><td><input type="number" name="vat_rate[' +
      count +
      ']" onkeyup="calculate_add_purchase(' +
      count +
      ');"onchange="calculate_add_purchase(' +
      count +
      ');" id="item_vat_rate_' +
      count +
      '"class="form-control text-right" placeholder="0.00" min="0"/></td><td><input type="number" name="vat[' +
      count +
      ']" id="item_vat_' +
      count +
      '"class="form-control text-right item_vat" placeholder="0.00" min="0" readonly /></td><td class="text-right"><input class="total_price text-right form-control" type="text" name="total_price[' +
      count +
      ']" id="total_price_' +
      count +
      '" placeholder="0.00" readonly="readonly" /> </td><td><button  class="btn btn-danger text-right" type="button" value="' +
      display("delete") +
      '" onclick="deleteRow(this)">' +
      display("delete") +
      "</button></td>";
    document.getElementById(divName).appendChild(newdiv);
    document.getElementById(tabin).focus();
    count++;
    $("select.form-control:not(.dont-select-me)").select2({
      placeholder: "Select option",
      allowClear: true,
    });
    $(".datepicker").datepicker({
      dateFormat: "yy-mm-dd",
    });
  }
}

//Calculate store product
function calculate_add_purchase(sl) {
  var e = 0;
  var gr_tot = 0;
  var total_vat = 0;

  var total_qntt = $("#total_qntt_" + sl).val();
  var price_item = $("#price_item_" + sl).val();
  var item_discount = $("#discount_" + sl).val();
  var item_vat_rate = $("#item_vat_rate_" + sl).val();
  var total_price =
    total_qntt * price_item - (total_qntt * price_item * item_discount) / 100;
  var item_vat = (total_price * item_vat_rate) / 100;
  var Total_Quantity = 0;

  $("#item_vat_" + sl).val(item_vat);
  $(".item_vat").each(function () {
    isNaN(this.value) ||
      0 == this.value.length ||
      (total_vat += parseFloat(this.value));
  });
  $(".p_quantity").each(function () {
    isNaN(this.value) ||
      0 == this.value.length ||
      (Total_Quantity += parseFloat(this.value));
  });
  $("#total_items").val(Total_Quantity);
  $("#total_items").trigger("change");
  $("#total_price_" + sl).val(total_price.toFixed(2));
  //Total Price
  $(".total_price").each(function () {
    isNaN(this.value) ||
      0 == this.value.length ||
      (gr_tot += parseFloat(this.value));
  });
  $("#total_vat").val(total_vat.toFixed(2, 2));
  $("#subTotal").val(gr_tot.toFixed(2, 2));
  $("#grandTotal").val((gr_tot + total_vat).toFixed(2, 2));
}

//Select stock by product and variant id
$("body").on("change", ".variant_id, .color_variant", function () {
  var sl = $(this).parent().parent().parent().find(".sl").val();
  var product_id = $(".product_id_" + sl).val();
  var avl_qntt = $("#avl_qntt_" + sl).val();
  var purchase_to = $("#purchase_to").val();
  var wearhouse_id = $("#wearhouse_id").val();
  var store_id = $("#store_id").val();
  var variant_id = $("#variant_id_" + sl).val();
  var variant_color = $("#color_variant_" + sl).val();

  if (purchase_to == 1) {
    if (wearhouse_id == 0) {
      alert(display("please_select_wearhouse"));
      return false;
    }
  }

  if (purchase_to == 2) {
    if (store_id == 0) {
      alert(display("please_select_store"));
      return false;
    }
  }

  $.ajax({
    type: "post",
    async: false,
    url: base_url + "dashboard/Cpurchase/wearhouse_available_stock",
    data: {
      csrf_test_name: csrf_test_name,
      product_id: product_id,
      variant_id: variant_id,
      variant_color: variant_color,
      purchase_to: purchase_to,
      wearhouse_id: wearhouse_id,
      store_id: store_id,
    },
    success: function (data) {
      if (data) {
        $("#avl_qntt_" + sl).val(data);
      }
    },
    error: function () {
      alert("Request Failed, Please try again!");
    },
  });
});

//Delete a row from purchase table
function deleteRow(t) {
  var a = $("#purchaseTable > tbody > tr").length;
  if (1 == a) {
    alert("There only one row you can't delete.");
    return false;
  } else {
    var e = t.parentNode.parentNode;
    e.parentNode.removeChild(e);
    calculate_add_purchase();
  }
  calculate_add_purchase();
  $("#item-number").html("0");
  $(".itemNumber>tr").each(function (i) {
    $("#item-number").html(i + 1);
    $(".item_bill").html(i + 1);
  });
}

function calculate_purchase_vat() {
  var subTotal = $("#subTotal").val();
  var vat = $("#purchase_vat").val();
  var total_purchase_vat = (subTotal * vat) / 100;
  var grandTotal = +total_purchase_vat + +subTotal;
  $("#total_vat").val(total_purchase_vat);
  $("#grandTotal").val(grandTotal);
}

var p_cost_count = 2;
function add_new_p_cost_row(divName) {
  $.ajax({
    url: base_url + "dashboard/Cpurchase/add_new_p_cost_row/" + p_cost_count,
    data: { csrf_test_name: csrf_test_name },
    type: "post",
    success: function (data) {
      $("#addPurchaseCost").append(data);
      $(".del_more_btn").on("click", function () {
        var row_id = $(this).attr("data-row_id");
        $("#row_" + row_id).remove();
        calculate_add_purchase_cost(1);
      });
    },
  });
  p_cost_count++;
}

function calculate_add_purchase_cost(c) {
  var total_cost = 0;
  $(".purchase_expences").each(function () {
    isNaN(this.value) ||
      0 == this.value.length ||
      (total_cost += parseFloat(this.value));
  });
  $("#purchase_expences").val(total_cost);
}
