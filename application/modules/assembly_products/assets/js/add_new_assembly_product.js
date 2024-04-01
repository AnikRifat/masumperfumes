"use strict";
// Counts and limit for invoice
var count = 2;
var limits = 500;
var csrf_test_name=  $("#CSRF_TOKEN").val();
//Add Invoice Field
function addInputField(divName) {
    if (count == limits) {
        alert("You have reached the limit of adding " + count + " inputs");
    } else {
        var newdiv = document.createElement('div');
        newdiv.classList.add("panel");
        newdiv.classList.add("panel-default");
        newdiv.classList.add("panel-assembly");
        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true
        });
        var assembly_product_area = "'assembly_product_area_"+ count +"'";
        newdiv.innerHTML = 
            '<div class="panel-body"><div class="row"><div class="col-sm-8 col-sm-offset-2"><div class="row title-row"><div class="col-sm-6"><div class="form-group row"><label for="assembly_title" class="col-sm-3">'+display('title')+'<i class="text-danger">*</i></label><div class="col-sm-9"><input type="text" name="assembly_title[' + count + ']" class="form-control" required="" id="assembly_title"><input type="hidden" name="assembly_title_id[' + count + ']" class="form-control" value="' + count + '" id="assembly_title_id"></div></div></div><div class="col-sm-6"><div class="form-group row"><label for="sub_title" class="col-sm-3">'+display('sub_title')+'</label><div class="col-sm-9"><input type="text" name="assembly_sub_title[' + count + ']" class="form-control" required="" id="assembly_sub_title"></div></div></div></div></div><div class="col-sm-2 text-right"><button class="btn btn-danger mr-auto assemble_delete" type="button" value="'+display('delete')+'" onclick="deleteRow(this)"><i class="fa fa-minus-circle"></i></button></div></div>'+
            '<div class="row"><div class="col-sm-9 col-sm-offset-2"><div class="form-group row required-box"><label class="checkbox-inline"><input type="checkbox" name="required[' + count + ']" id="inlineCheckbox1"><strong>'+display('required')+'</strong></label><label class="checkbox-inline"><input type="checkbox" name="change_quantity[' + count + ']" id="inlineCheckbox1"><strong>'+display('change_quantity')+'</strong></label></div></div></div>'+
            '<div class="row"><div class="col-sm-8 col-sm-offset-2"><div><table class="table table-bordered"><thead><tr><th>'+display('product')+'<span class="color-red">*</span></th><th>'+display('is_default')+'</th><th>'+display('action')+'</th></tr></thead>'+
            '<tbody id="assembly_product_area_'+ count +'"><tr><td><input type="text" name="product_name_' + count + '[]" onkeyup="invoice_productList(' + count + ');" class="form-control productSelection" placeholder="'+display('product_name')+'" required="" id="product_name_' + count + '"/><input type="hidden" class="autocomplete_hidden_value product_id_' + count + '" name="product_id_' + count + '[]"/>'+
            '</td><td><input type="radio" name="is_default_'+ count +'" class="default_box is_default_'+ count +'" value="1"/></td><td><button class="btn btn-success mr-auto" type="button" onclick="addProductRow('+assembly_product_area+','+count+');"><i class="ti-plus"></i></button></td></tr></tbody></table></div></div></div>';
        document.getElementById(divName).appendChild(newdiv);
        count++;
        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true
        });
        $('.default_box').on('click',function(){
            var sl = $(this).parent().parent().find(".autocomplete_hidden_value").val();
            $(this).val(sl);
            console.log(sl);
        });
    }
}
//Delete a row from invoice table
function deleteRow(t) {

    var a = $("#addinvoiceItem2 > div").length;
    if (1 == a){
        alert("There only one row you can't delete.");
        return false;
    }else{
        var e = t.parentNode.parentNode.parentNode.parentNode;
        e.remove(t);
    }
}
var p_count = 1;
function addProductRow(divName,row_sl) {
    if (p_count == limits) {
        alert("You have reached the limit of adding " + p_count + " inputs");
    } else {
        var newdiv = document.createElement('tr');
        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true
        });
        newdiv.innerHTML = 
            '<td><input type="text" name="product_name_' + row_sl + '[]" onkeyup="invoice_productList('+ row_sl +');" class="form-control productSelection" placeholder="'+display('product_name')+'" required="" id="product_name_' + row_sl + '" /><input type="hidden" class="autocomplete_hidden_value product_id_' + p_count + '" name="product_id_' + row_sl + '[]"/><input type="hidden" class="sl" value="' + p_count + '"/><input type="hidden" class="baseUrl" value="'+base_url+'" /></td>'+
            '<td><input type="radio" name="is_default_'+ row_sl +'" class="default_box is_default_'+ row_sl +'" value="1"></td>'+
            '<td><button class="btn btn-danger mr-auto" type="button" onclick="removeProductRow(this);"><i class="ti-minus"></i></button></td>';
        document.getElementById(divName).appendChild(newdiv);
        p_count++;
        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true
        });
        $('.default_box').on('click',function(){
            var sl = $(this).parent().parent().find(".autocomplete_hidden_value").val();
            $(this).val(sl);
        });
    }

}
var f_count = 1
function addFirstProductRow(divName){
    if (f_count == limits) {
        alert("You have reached the limit of adding " + f_count + " inputs");
    } else {
        var newdiv = document.createElement('tr');
        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true
        });
        newdiv.innerHTML = 
            '<td><input type="text" name="product_name_1[' + f_count + ']" onkeyup="invoice_productList('+ f_count +');" class="form-control productSelection" placeholder="'+display('product_name')+'" required="" id="product_name_' + f_count + '" /><input type="hidden" class="autocomplete_hidden_value sabit_vai product_id_' + f_count + '" name="product_id_1[' + f_count + ']"/><input type="hidden" class="sl" value="' + f_count + '"/><input type="hidden" class="baseUrl" value="'+base_url+'" /></td>'+
            '<td><input type="radio" name="is_default_1" class="is_default_1 default_box" value="1"></td>'+
            '<td><button class="btn btn-danger mr-auto" type="button" onclick="removeProductRow(this);"><i class="ti-minus"></i></button></td>';
        document.getElementById(divName).appendChild(newdiv);
        f_count++;
        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true
        });

       $('.default_box').on('click',function(){
            var sl = $(this).parent().parent().find(".autocomplete_hidden_value").val();
            $(this).val(sl);
        });
    }

}
function removeProductRow(t) {
    var a = $(".assembly_product_area > tr").length;
    if (1 == a){
        alert("There only one row you can't delete.");
        return false;
    }else{
        var e = t.parentNode.parentNode;
        e.remove();
    }
}
$('.default_box').on('click',function(){
    var sl = $(this).parent().parent().find(".autocomplete_hidden_value").val();
    $(this).val(sl);
});


var tr_count = 1;
$('#add_row').on('click',function(){
    var csrf_test_name=  $("#CSRF_TOKEN").val();
    $.ajax({
            type: "POST",
            url: base_url+'assembly_products/back_assembly_products/add_translation',
            data: {csrf_test_name:csrf_test_name,tr_count:tr_count},
            cache: false,
            success: function(datas)
            {
                $('.new_row').append(datas);
                // select 2 dropdown 
                $("select.brand-control").select2({
                    placeholder: "Select option",
                    allowClear: true
                });
                tr_count++;
                $('.remove_row').on('click',function(){
                    $(this).parent().parent().parent().parent().remove();
                });
            } 
        });
});
$('.remove_data_row').on('click',function(){
    $(this).parent().parent().parent().parent().remove();
});