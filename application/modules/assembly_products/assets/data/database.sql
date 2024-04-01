CREATE TABLE `assembly_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `a_product_id` varchar(50) NOT NULL,
  `assembly_title` varchar(255) DEFAULT NULL,
  `assembly_sub_title` varchar(255) DEFAULT NULL,
  `required` tinyint(2) DEFAULT NULL DEFAULT '0',
  `change_quantity` tinyint(2) DEFAULT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `assembly_products_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assembly_product_id` varchar(50) DEFAULT NULL,
  `product_id` varchar(50) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `product_price` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `assembly_products_translation` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `assembly_product_id` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `trans_name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
