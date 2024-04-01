"use strict";
var CSRF_TOKEN = $("#CSRF_TOKEN").val();
var base_url = $("#base_url").val();


// Price change on variant select
$('.dd-option').on('click',function(){

    var item_id = $(this).find('.dd-option-value').val();
    var item_qty = $(this).parent().parent().parent().parent().find('.a_item_qty').val();

    var total = 0;
    var item_total = 0;
    $( ".a_item_price" ).each( function(){
        var pvalue = $( this ).val().toString().match(/\d+/);
        var item_qty = $(this).parent().find('.a_item_qty').val();
        item_total = parseFloat( pvalue[0] ) || 0;
        total += (item_total * parseInt(item_qty));
    });

    var position1 = $('#position1').val();
    var currency1 = $('#currency1').val();

    if(position1 == 0){
        var total_price = currency1 + ' '+total;
    }else{
        var total_price = total + ' '+currency1;
    }

    $('.var_amount').text(total_price);

    if(item_id != 0){
        checkProduct_stock(item_id, item_qty);
    }
    
});


//Check product wise quantity in stock
$('.assemble_quantity').on("change", function(){
    var product_quantity = $(this).val();
    var assem_id = $(this).attr('data-aid');
    var product_id = $('#a_item_id_'+assem_id).val();
    $('#a_item_qty_'+assem_id).val(product_quantity);

    $.ajax({
        type: "post",
        url: base_url + "assembly_products/front_assembly_products/check_assembly_quantity_wise_stock",
        data: {
            "csrf_test_name": CSRF_TOKEN,
            "product_quantity": product_quantity,
            "product_id": product_id
        },
        success: function(data) {
            if (data == 'yes') {

                //change product price value
                var total = 0;
                var item_total = 0;
                $( ".a_item_price" ).each( function(){
                    var pvalue = $( this ).val().toString().match(/\d+/);
                    var item_qty = $(this).parent().find('.a_item_qty').val();
                    item_total = parseFloat( pvalue[0] ) || 0;
                    total += (item_total * parseInt(item_qty));
                });

                var position1 = $('#position1').val();
                var currency1 = $('#currency1').val();

                if(position1 == 0){
                    var total_price = currency1 + ' '+total;
                }else{
                    var total_price = total + ' '+currency1;
                }

                $('.var_amount').text(total_price);


                return true;
            }else {
                Swal({
                    type: 'warning',
                    title: display('not_enough_product_in_stock')
                });
                return false;
            }
        },
        error: function() {
            Swal({
                type: 'warning',
                title: display('request_failed')
            });
        }
    });
});

//check stock on product change
function checkProduct_stock(item_id, item_qty)
{
    $.ajax({
        type: "post",
        url: base_url + "assembly_products/front_assembly_products/check_assembly_quantity_wise_stock",
        data: {
            "csrf_test_name": CSRF_TOKEN,
            "product_quantity": item_qty,
            "product_id": item_id
        },
        success: function(data) {
            if (data == 'yes') {
                return true;
            }else {
                Swal({
                    type: 'warning',
                    title: display('not_enough_product_in_stock')
                });
                return false;
            }
        },
        error: function() {
            Swal({
                type: 'warning',
                title: display('request_failed')
            });
        }
    });
}

// Add to cart
$("#assembly_cart_form").on('submit',function(e){
    e.preventDefault();
    var dataString = $("#assembly_cart_form").serialize();

    $.ajax({
        type: "POST",
        url: base_url + "assembly_products/front_assembly_products/add_to_cart",
        data: dataString,
        success: function(data) {
            console.log(data);
            $("#tab_up_cart").load(location.href + " #tab_up_cart>*", "");
            Swal({
                type: 'success',
                title: display('product_added_to_cart')
            })
        },
        error: function() {
            Swal({
                type: 'warning',
                title: display('request_failed')
            })
        }
    });
    return false;  //stop the actual form post !important!
});