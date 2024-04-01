DELETE FROM `language` WHERE `language`.`phrase` = 'assembly_products';
DELETE FROM `language` WHERE `language`.`phrase` = 'new_assembly_product';
DELETE FROM `language` WHERE `language`.`phrase` = 'add_new_assembly_product';
DELETE FROM `language` WHERE `language`.`phrase` = 'manage_assembly_products';
DELETE FROM `language` WHERE `language`.`phrase` = 'sub_title';
DELETE FROM `language` WHERE `language`.`phrase` = 'change_quantity';
DELETE FROM `language` WHERE `language`.`phrase` = 'required';
DELETE FROM `language` WHERE `language`.`phrase` = 'product_selection';
DELETE FROM `language` WHERE `language`.`phrase` = 'product_already_exist';
DELETE FROM `language` WHERE `language`.`phrase` = 'assembly_cart';
DELETE FROM `language` WHERE `language`.`phrase` = 'is_default';
DELETE FROM `language` WHERE `language`.`phrase` = 'update_assembly_product';

ALTER TABLE `product_information` DROP `is_assemble`;
