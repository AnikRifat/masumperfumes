<?php
	// module name
	$HmvcMenu["assembly_products"] = array(
	    //set icon
	    "icon"        => "<i class='ti-loop'></i>",
	    //menu name
	    "add_assembly_product" => array(
	        "controller" => "back_assembly_products",
	        "method"     => "new_product",
	        "permission" => "read"
	    ),
	    "manage_assembly_product" => array(
	        "controller" => "back_assembly_products",
	        "method"     => "index",
	        "permission" => "read"
	    )
	);
?>