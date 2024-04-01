<?php
// module directory name
$HmvcConfig['assembly_products']["_title"] = "Assembly Products";
$HmvcConfig['assembly_products']["_description"] = "Assembly Products";
// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['assembly_products']['_database'] = true;
$HmvcConfig['assembly_products']["_tables"] = array(
	'assembly_products',
	'assembly_products_details'
);
//Table sql Data insert into existing tables to run module
$HmvcConfig['assembly_products']["_extra_query"] = true;