INSERT INTO `language` (`phrase`, `english`) VALUES 
('assembly_products', 'Assembly Products'),
('new_assembly_product', 'New Assembly Product'),
('add_assembly_product', 'Add Assembly Product'),
('add_new_assembly_product', 'Add New Assembly Product'),
('manage_assembly_products', 'Manage Assembly Products'),
('sub_title', 'Sub Title'),
('change_quantity', 'Change Quantity'),
('required', 'Required'),
('product_selection', 'Product Selection'),
('product_already_exist', 'Product Already Exist'),
('assembly_cart', 'assembly_cart'),
('is_default', 'Is Default'),
('update_assembly_product', 'Update Assembly Product'),
('manage_assembly_product', 'Manage Assembly Product');

INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `actions`, `is_report`, `createby`, `createdate`) VALUES 
(NULL,'assembly_products', NULL, 'assembly_products', NULL, '0100', '0', '1', current_timestamp()),
(NULL,'add_assembly_product', NULL, 'assembly_products', '136', '1100', '0', '1', current_timestamp()),
(NULL,'manage_assembly_product', NULL, 'assembly_products', '136', '0111', '0', '1', current_timestamp());

ALTER TABLE `product_information` ADD `is_assemble` TINYINT(2) NULL AFTER `status`;