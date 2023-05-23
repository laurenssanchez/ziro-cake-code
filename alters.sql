ALTER TABLE `customers`
	ALTER `gender` DROP DEFAULT,
	ALTER `date_birth` DROP DEFAULT,
	ALTER `country_birth` DROP DEFAULT,
	ALTER `department_birth` DROP DEFAULT,
	ALTER `city_birth` DROP DEFAULT,
	ALTER `identification_date` DROP DEFAULT,
	ALTER `identification_country` DROP DEFAULT,
	ALTER `identification_department` DROP DEFAULT,
	ALTER `city_identification` DROP DEFAULT,
	ALTER `profession` DROP DEFAULT,
	ALTER `occupation` DROP DEFAULT,
	ALTER `monthly_income` DROP DEFAULT,
	ALTER `politics` DROP DEFAULT;
ALTER TABLE `customers`
	CHANGE COLUMN `gender` `gender` VARCHAR(45) NULL AFTER `tyc`,
	CHANGE COLUMN `date_birth` `date_birth` DATE NULL AFTER `gender`,
	CHANGE COLUMN `country_birth` `country_birth` VARCHAR(45) NULL AFTER `date_birth`,
	CHANGE COLUMN `department_birth` `department_birth` VARCHAR(45) NULL AFTER `country_birth`,
	CHANGE COLUMN `city_birth` `city_birth` VARCHAR(45) NULL AFTER `department_birth`,
	CHANGE COLUMN `identification_date` `identification_date` DATE NULL AFTER `city_birth`,
	CHANGE COLUMN `identification_country` `identification_country` VARCHAR(45) NULL AFTER `identification_date`,
	CHANGE COLUMN `identification_department` `identification_department` VARCHAR(45) NULL AFTER `identification_country`,
	CHANGE COLUMN `city_identification` `city_identification` VARCHAR(45) NULL AFTER `identification_department`,
	CHANGE COLUMN `profession` `profession` VARCHAR(255) NULL AFTER `city_identification`,
	CHANGE COLUMN `occupation` `occupation` VARCHAR(255) NULL AFTER `profession`,
	CHANGE COLUMN `monthly_income` `monthly_income` FLOAT NULL AFTER `occupation`,
	CHANGE COLUMN `politics` `politics` INT(11) NULL AFTER `monthly_income`,
	CHANGE COLUMN `state` `state` INT(11) NOT NULL DEFAULT 1 AFTER `politics`;


ALTER TABLE `customers`
	ALTER `name` DROP DEFAULT,
	ALTER `lastname` DROP DEFAULT;
ALTER TABLE `customers`
	CHANGE COLUMN `name` `name` VARCHAR(45) NULL AFTER `id`,
	CHANGE COLUMN `lastname` `lastname` VARCHAR(45) NULL AFTER `name`;


ALTER TABLE `customers`
	ALTER `identification_date` DROP DEFAULT;
ALTER TABLE `customers`
	CHANGE COLUMN `identification_date` `identification_date` DATE NOT NULL AFTER `city_birth`,
	ADD COLUMN `identification_place` VARCHAR(100) NOT NULL AFTER `identification_department`;


ALTER TABLE `users`
	ADD COLUMN `customer_id` INT(11) NULL DEFAULT NULL AFTER `shop_id`;


ALTER TABLE `customers`
	ADD COLUMN `data_full` INT(11) NOT NULL DEFAULT 0 AFTER `state`,
	DROP COLUMN `lastname`,
	DROP COLUMN `country_birth`,
	DROP COLUMN `department_birth`,
	DROP COLUMN `identification_country`,
	DROP COLUMN `identification_department`,
	DROP COLUMN `identification_place`,
	DROP COLUMN `city_identification`,
	DROP COLUMN `profession`;

ALTER TABLE `customers_addresses`
	DROP COLUMN `address_department`;

ALTER TABLE `customers_phones`
	CHANGE COLUMN `state` `state` INT(11) NOT NULL DEFAULT 1 AFTER `phone_number`;

CREATE TABLE `customers_codes` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`code` INT(11) NOT NULL,
`customer_id` INT(11) NOT NULL,
`deadline` BIGINT(20) NOT NULL,
`type_code` INT(11) NOT NULL,
`state` INT(11) NOT NULL DEFAULT '0',
`created` DATETIME NOT NULL,
`modified` DATETIME NOT NULL,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
;



ALTER TABLE `users`
	ADD COLUMN `customer_complete` INT(11) NULL DEFAULT 0 AFTER `customer_id`;


CREATE TABLE `credits_requests` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`customer_id` INT NOT NULL,
	`request_value` FLOAT NOT NULL,
	`request_number` INT NOT NULL,
	`credits_line_id` INT NOT NULL,
	`state` INT NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci'
;



ALTER TABLE `shops`
	DROP COLUMN `commerce`;


ALTER TABLE `shop_references`
	ALTER `shop_referencescol` DROP DEFAULT;
ALTER TABLE `shop_references`
	CHANGE COLUMN `shop_referencescol` `commerce` VARCHAR(255) NOT NULL AFTER `phone`;


ALTER TABLE `shop_payments`
	ALTER `outstanding balance` DROP DEFAULT;
ALTER TABLE `shop_payments`
	CHANGE COLUMN `outstanding balance` `outstanding_balance` FLOAT NOT NULL AFTER `date`;


ALTER TABLE `shop_payments`
	ADD COLUMN `shop_id` INT NOT NULL DEFAULT 0 AFTER `date`;

ALTER TABLE `shop_payments`
	CHANGE COLUMN `payment_date` `payment_date` DATETIME NULL DEFAULT NULL AFTER `notes`;


ALTER TABLE `shop_commerces`
	ALTER `user_id` DROP DEFAULT;
ALTER TABLE `shop_commerces`
	CHANGE COLUMN `user_id` `user_id` INT(11) NULL AFTER `state`;

ALTER TABLE `users`
	CHANGE COLUMN `shop_commerce` `shop_commerce_id` INT(11) NULL DEFAULT NULL AFTER `salt_password`;

ALTER TABLE `shop_commerces`
	ADD COLUMN `code` BIGINT NULL AFTER `shop_id`;

ALTER TABLE `credits_requests`
	ADD COLUMN `date_admin` DATETIME NULL DEFAULT NULL AFTER `user_id`,
	ADD COLUMN `reason_reject` VARCHAR(255) NULL DEFAULT NULL AFTER `date_admin`,
	ADD COLUMN `total_score` FLOAT NULL DEFAULT NULL AFTER `reason_reject`,
	ADD COLUMN `vars_score` LONGTEXT NULL DEFAULT NULL AFTER `total_score`,
	ADD COLUMN `number_approve` INT NULL DEFAULT NULL AFTER `vars_score`,
	ADD COLUMN `value_approve` INT NULL DEFAULT NULL AFTER `number_approve`,
	ADD COLUMN `date_disbursed` DATETIME NULL DEFAULT NULL AFTER `value_approve`;


ALTER TABLE `shops`
	ADD COLUMN `cost_min` INT(11) NOT NULL AFTER `plan`,
	ADD COLUMN `cost_max` INT(11) NOT NULL AFTER `cost_min`;

CREATE TABLE `shops_debts` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`user_id` INT(11) NOT NULL,
	`shop_id` INT(11) NOT NULL,
	`credit_id` INT(11) NULL DEFAULT NULL,
	`credit_payments_shop` INT(11) NULL DEFAULT NULL,
	`value` FLOAT NOT NULL,
	`reason` VARCHAR(255) NOT NULL,
	`state` INT(11) NOT NULL DEFAULT '0',
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM
;


ALTER TABLE `credits_requests`
	ADD COLUMN `user_disbursed` INT(11) NULL DEFAULT NULL AFTER `user_id`;

ALTER TABLE `credits_requests`
	ADD COLUMN `credit_id` INT(11) NULL AFTER `shop_commerce_id`;

ALTER TABLE `credits`
	ALTER `value_aprooved` DROP DEFAULT,
	ALTER `deadlines` DROP DEFAULT,
	ALTER `credits_line_id` DROP DEFAULT,
	ALTER `customer_id` DROP DEFAULT,
	ALTER `value_pending` DROP DEFAULT;
ALTER TABLE `credits`
	CHANGE COLUMN `value_aprooved` `value_aprooved` INT(11) NOT NULL AFTER `value_request`,
	CHANGE COLUMN `deadlines` `number_fee` INT(11) NOT NULL AFTER `value_aprooved`,
	CHANGE COLUMN `credits_line_id` `credits_line_id` INT(11) NOT NULL AFTER `number_fee`,
	CHANGE COLUMN `value_pending` `value_pending` FLOAT NOT NULL AFTER `quota_value`,
	ADD COLUMN `deadline` DATE NOT NULL AFTER `value_pending`,
	CHANGE COLUMN `customer_id` `customer_id` INT(11) NOT NULL AFTER `state`,
	DROP COLUMN `type_payment`;

ALTER TABLE `credits_plans`
	ADD COLUMN `deadline` DATE NOT NULL AFTER `others_value`,
	ADD COLUMN `value_pending` FLOAT NOT NULL AFTER `deadline`;


ALTER TABLE `credits_requests`
	ADD COLUMN `value_disbursed` FLOAT NULL DEFAULT NULL AFTER `date_disbursed`;


CREATE TABLE `credit_limits` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`value` FLOAT NOT NULL,
	`type_movement` INT NOT NULL,
	`state` INT NOT NULL DEFAULT '1',
	`reason` VARCHAR(255) NOT NULL,
	`deadline` DATE NOT NULL,
	`credit_id` INT NULL,
	`credits_request_id` INT NULL,
	`user_id` INT NULL,
	`customer_id` INT NOT NULL,
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci'
;


ALTER TABLE `credits_requests`
	DROP COLUMN `credit_id`;

ALTER TABLE `users`
	ADD COLUMN `customer_new_request` INT(11) NOT NULL DEFAULT '0' AFTER `customer_complete`;

ALTER TABLE `customers`
	ADD COLUMN `code` INT(11) NULL DEFAULT NULL AFTER `customer_id`;


ALTER TABLE `credit_limits`
	ADD COLUMN `number_fee` INT NULL DEFAULT NULL AFTER `value`;

ALTER TABLE `credit_limits`
	ADD COLUMN `credit_request_final` INT(11) NULL AFTER `customer_id`,
	ADD COLUMN `active` INT(11) NOT NULL DEFAULT '1' AFTER `credit_request_final`;


ALTER TABLE `customers`
	ADD COLUMN `total_datacredito` FLOAT NULL DEFAULT NULL AFTER `data_full`,
	ADD COLUMN `vars_datacredito` TEXT NULL DEFAULT NULL AFTER `total_datacredito`;

ALTER TABLE `credit_limits`
	DROP COLUMN `number_fee`;


ALTER TABLE `credits`
	ADD COLUMN `credits_request_id` INT(11) NOT NULL AFTER `customer_id`;


CREATE TABLE `disbursements` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`value` FLOAT NOT NULL,
	`unpaid_value` FLOAT NOT NULL DEFAULT '0',
	`credit_id` FLOAT NOT NULL,
	`shop_commerce_id` INT(11) NOT NULL,
	`state` INT(11) NOT NULL DEFAULT '1',
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci'
ENGINE=MyISAM
;


ALTER TABLE `customers_codes`
	ADD COLUMN `credits_request_id` INT(11) NULL DEFAULT NULL AFTER `type_code`;


ALTER TABLE `shops_debts`
	ALTER `shop_id` DROP DEFAULT;
ALTER TABLE `shops_debts`
	CHANGE COLUMN `shop_id` `shop_commerce_id` INT(11) NOT NULL AFTER `user_id`,
	CHANGE COLUMN `credit_payments_shop` `type` INT(11) NOT NULL DEFAULT '1' AFTER `credit_id`;


CREATE TABLE `money_collections` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`value` FLOAT NOT NULL DEFAULT '0',
	`shop_commerce_id` INT(11) NOT NULL,
	`user_id` INT(11) NOT NULL,
	`state` INT(11) NOT NULL DEFAULT '1',
	`payment_credit` INT(11) NULL DEFAULT NULL,
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci'
ENGINE=MyISAM
;


CREATE TABLE `shop_payment_requests` (
	`id` INT(11) NULL DEFAULT NULL,
	`final_value` FLOAT NULL DEFAULT NULL,
	`request_value` FLOAT NOT NULL,
	`iva` FLOAT NOT NULL,
	`request_date` FLOAT NOT NULL DEFAULT '0',
	`shop_commerce_id` INT(11) NOT NULL,
	`user_id` INT(11) NOT NULL,
	`notes` TEXT NULL,
	`state` INT(11) NOT NULL DEFAULT '0',
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL
)
ENGINE=MyISAM
;

ALTER TABLE `disbursements`
	ADD COLUMN `shop_payment_request_id` INT(11) NULL AFTER `shop_commerce_id`,
	DROP COLUMN `unpaid_value`;


ALTER TABLE `shops_debts`
	ADD COLUMN `shop_payment_request_id` INT(11) NULL DEFAULT NULL AFTER `credit_id`;

ALTER TABLE `shop_payment_requests`
	CHANGE COLUMN `iva` `iva` FLOAT NULL DEFAULT NULL AFTER `request_value`;


ALTER TABLE `shop_payment_requests`
	ADD COLUMN `payment_type` INT NOT NULL AFTER `request_value`;


ALTER TABLE `shop_payment_requests`
	ALTER `request_date` DROP DEFAULT;
ALTER TABLE `shop_payment_requests`
	ADD COLUMN `iva_final` FLOAT NULL DEFAULT NULL AFTER `iva`,
	CHANGE COLUMN `request_date` `request_date` DATETIME NOT NULL AFTER `iva_final`,
	ADD COLUMN `final_date` DATETIME NOT NULL AFTER `request_date`;


ALTER TABLE `shop_payment_requests`
	ALTER `final_date` DROP DEFAULT;
ALTER TABLE `shop_payment_requests`
	CHANGE COLUMN `final_date` `final_date` DATETIME NULL AFTER `request_date`;

ALTER TABLE `shop_payment_requests`
	CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT FIRST,
	ADD PRIMARY KEY (`id`);


	ALTER TABLE `credits_requests`
	ADD COLUMN `credit_id` INT NULL DEFAULT NULL AFTER `value_disbursed`;

ALTER TABLE `customers`
	ADD COLUMN `type_contract` VARCHAR(255) NULL DEFAULT NULL AFTER `monthly_income`;

ALTER TABLE `customers`
	ADD COLUMN `response_datacredito` TEXT NULL AFTER `vars_datacredito`;

	ALTER TABLE `credits_requests`
	ADD COLUMN `response_score` LONGTEXT NULL AFTER `vars_score`;

ALTER TABLE `credits_requests`
	ADD COLUMN `request_type` INT(11) NOT NULL DEFAULT '1' AFTER `request_number`;

ALTER TABLE `credits`
	ADD COLUMN `type` INT(11) NOT NULL DEFAULT '1' AFTER `credits_line_id`;

ALTER TABLE `credits_plans`
	ADD COLUMN `number` INT(11) NOT NULL AFTER `id`;


ALTER TABLE `credits_plans`
	ADD COLUMN `capital_payment` FLOAT NOT NULL DEFAULT '0' AFTER `capital_value`,
	ADD COLUMN `interest_payment` FLOAT NOT NULL DEFAULT '0' AFTER `interest_value`,
	ADD COLUMN `others_payment` FLOAT NOT NULL DEFAULT '0' AFTER `others_value`;


ALTER TABLE `credits_plans`
	ADD COLUMN `date_payment` DATE NULL DEFAULT NULL AFTER `deadline`;

ALTER TABLE `credits`
	ADD COLUMN `last_payment_date` DATE NULL DEFAULT NULL AFTER `credits_request_id`;


ALTER TABLE `collection_fees`
	ALTER `day end` DROP DEFAULT;
ALTER TABLE `collection_fees`
	CHANGE COLUMN `day end` `day_end` INT(11) NOT NULL AFTER `day_ini`;

ALTER TABLE `credits_plans`
	ADD COLUMN `date_debt` DATE NULL DEFAULT NULL AFTER `date_payment`;


ALTER TABLE `credits_plans`
	ADD COLUMN `dateini` DATE NULL DEFAULT NULL AFTER `others_payment`;


CREATE TABLE `payments` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`credits_plan_id` INT NOT NULL,
	`value` INT NOT NULL,
	`user_id` INT NOT NULL,
	`shop_commerce_id` INT NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci'
;

ALTER TABLE `payments`
	ADD COLUMN `type` INT(11) NOT NULL DEFAULT '1' AFTER `shop_commerce_id`;


ALTER TABLE `payments`
	ADD COLUMN `shop_payment_request_id` INT(11) NULL DEFAULT NULL AFTER `shop_commerce_id`;


ALTER TABLE `payments`
	ADD COLUMN `state` INT(11) NOT NULL DEFAULT '0' AFTER `type`;

ALTER TABLE `payments`
	ADD COLUMN `state_credishop` INT(11) NOT NULL DEFAULT '0' AFTER `state`,
	ADD COLUMN `date_credishop` DATE NULL DEFAULT NULL AFTER `state_credishop`;

ALTER TABLE `payments`
	CHANGE COLUMN `date_credishop` `date_credishop` BIGINT NULL DEFAULT NULL AFTER `state_credishop`;

ALTER TABLE `users`
	ADD COLUMN `code` INT NULL DEFAULT NULL AFTER `role`,
	ADD COLUMN `deadline` BIGINT NULL DEFAULT NULL AFTER `code`;


ALTER TABLE `users`
	ADD COLUMN `phone` VARCHAR(10) NULL DEFAULT NULL AFTER `role`;


ALTER TABLE `users`
	ADD COLUMN `validate` INT NULL DEFAULT '0' AFTER `deadline`;

ALTER TABLE `customers`
	CHANGE COLUMN `identification_date` `identification_date` DATE NULL DEFAULT NULL AFTER `city_birth`;

ALTER TABLE `customers`
	CHANGE COLUMN `identification_place` `identification_place` VARCHAR(255) NULL DEFAULT NULL AFTER `email`;


CREATE TABLE `commitments` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`credits_plan_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	`commitment` TEXT NOT NULL,
	`deadline` DATE NOT NULL,
	`state` INT NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci'
;

CREATE TABLE `notes` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`credits_plan_id` INT NOT NULL,
	`note` TEXT NOT NULL,
	`user_id` INT NOT NULL,
	`state` INT NOT NULL DEFAULT '1',
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci'
;

CREATE TABLE `histories` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`credits_plan_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	`action` TEXT NOT NULL,
	`state` INT NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci'
;
