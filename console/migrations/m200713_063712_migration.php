<?php

use yii\db\Migration;

class m200713_063712_migration extends Migration
{
    public function up()
    {
		$this->execute('SET foreign_key_checks = 0');
 
$this->createTable('{{%admin}}', [
	'user_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'username' => 'VARCHAR(255) NOT NULL',
	'real_name' => 'VARCHAR(60) NOT NULL',
	'password_hash' => 'VARCHAR(255) NOT NULL',
	'password_reset_hash' => 'VARCHAR(255) NULL',
	'email' => 'VARCHAR(255) NOT NULL',
	'email_verify_token' => 'VARCHAR(255) NULL',
	'phone' => 'VARCHAR(11) NOT NULL',
	'auth_key' => 'VARCHAR(32) NOT NULL',
	'access_token' => 'VARCHAR(255) NOT NULL',
	'role' => 'SMALLINT(6) UNSIGNED NOT NULL DEFAULT \'1\'',
	'role_id' => 'VARCHAR(255) NOT NULL',
	'status' => 'INT(11) UNSIGNED NOT NULL DEFAULT \'10\'',
	'created_at' => 'INT(11) UNSIGNED NOT NULL',
	'updated_at' => 'INT(11) UNSIGNED NOT NULL',
	'dep_isleader' => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'0\'',
	'team_leader' => 'TINYINT(3) NOT NULL DEFAULT \'0\'',
	'PRIMARY KEY (`user_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%admin_auth_relation}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'unitid' => 'INT(11) UNSIGNED NOT NULL DEFAULT \'0\'',
	'depid' => 'INT(11) UNSIGNED NOT NULL DEFAULT \'0\'',
	'teamid' => 'INT(11) UNSIGNED NOT NULL DEFAULT \'0\'',
	'appid' => 'INT(11) UNSIGNED NOT NULL DEFAULT \'0\'',
	'siteid' => 'INT(11) UNSIGNED NOT NULL DEFAULT \'0\'',
	'adminid' => 'INT(11) UNSIGNED NOT NULL DEFAULT \'0\'',
	'type' => 'TINYINT(1) UNSIGNED NULL DEFAULT \'0\'',
	'created_at' => 'INT(11) UNSIGNED NOT NULL DEFAULT \'0\'',
	'updated_at' => 'INT(11) UNSIGNED NOT NULL DEFAULT \'0\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%admin_dep}}', [
	'depid' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(128) NOT NULL',
	'description' => 'VARCHAR(255) NOT NULL',
	'd_status' => 'INT(11) NULL DEFAULT \'11\'',
	'father_id' => 'INT(11) NULL',
	'unit_id' => 'INT(11) NOT NULL',
	'siteid' => 'INT(11) NOT NULL',
	'sort_id' => 'INT(11) NOT NULL DEFAULT \'1\'',
	'app_id' => 'INT(11) UNSIGNED NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'is_del' => 'TINYINT(1) UNSIGNED NULL DEFAULT \'0\'',
	'auth_item_id' => 'VARCHAR(50) NULL',
	'PRIMARY KEY (`depid`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('FK_admindep_status','{{%admin_dep}}','d_status',0);
 
$this->createTable('{{%admin_dep_status}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(128) NOT NULL',
	'position' => 'INT(11) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%admin_status}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(128) NOT NULL',
	'position' => 'INT(11) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%admin_team}}', [
	'teamid' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(128) NOT NULL',
	'description' => 'VARCHAR(255) NOT NULL',
	'father_id' => 'INT(11) NULL',
	't_status' => 'INT(11) NULL DEFAULT \'10\'',
	'unit_id' => 'INT(11) NOT NULL',
	'sort_id' => 'INT(11) NULL DEFAULT \'1\'',
	'is_del' => 'TINYINT(1) UNSIGNED NULL DEFAULT \'0\'',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'auth_item_id' => 'VARCHAR(50) NULL',
	'PRIMARY KEY (`teamid`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('FK_adminteam_status','{{%admin_team}}','t_status',0);
 
$this->createTable('{{%admin_team_status}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(128) NOT NULL',
	'position' => 'INT(11) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%admin_unit}}', [
	'unitid' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(128) NOT NULL',
	'description' => 'VARCHAR(255) NOT NULL',
	'u_status' => 'INT(11) NULL DEFAULT \'10\'',
	'siteid' => 'INT(11) NOT NULL',
	'sort_id' => 'INT(11) NOT NULL',
	'app_id' => 'INT(11) UNSIGNED NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'is_del' => 'TINYINT(1) UNSIGNED NULL DEFAULT \'0\'',
	'auth_item_id' => 'VARCHAR(50) NULL',
	'PRIMARY KEY (`unitid`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('FK_adminunit_status','{{%admin_unit}}','u_status',0);
 
$this->createTable('{{%admin_unit_status}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(128) NOT NULL',
	'position' => 'INT(11) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%auth_assignment}}', [
	'item_name' => 'VARCHAR(64) NOT NULL',
	'user_id' => 'VARCHAR(64) NOT NULL',
	'created_at' => 'INT(11) NULL',
	'PRIMARY KEY (`item_name`,`user_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%auth_item}}', [
	'name' => 'VARCHAR(64) NOT NULL',
	'type' => 'INT(11) UNSIGNED NOT NULL',
	'parent_name' => 'VARCHAR(64) NULL',
	'description' => 'TEXT NULL',
	'rule_name' => 'VARCHAR(64) NULL',
	'data' => 'TEXT NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'status' => 'TINYINT(1) UNSIGNED NULL DEFAULT \'0\'',
	'order_sort' => 'SMALLINT(3) UNSIGNED NULL DEFAULT \'100\'',
	'icon' => 'CHAR(100) NULL',
	'is_menu' => 'TINYINT(1) UNSIGNED NULL DEFAULT \'0\'',
	'PRIMARY KEY (`name`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('rule_name','{{%auth_item}}','rule_name',0);
$this->createIndex('idx-auth_item-type','{{%auth_item}}','type',0);
 
$this->createTable('{{%auth_item_child}}', [
	'parent' => 'VARCHAR(64) NOT NULL',
	'child' => 'VARCHAR(64) NOT NULL',
	'PRIMARY KEY (`parent`,`child`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('child','{{%auth_item_child}}','child',0);
 
$this->createTable('{{%auth_rule}}', [
	'name' => 'VARCHAR(64) NOT NULL',
	'data' => 'TEXT NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'PRIMARY KEY (`name`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%config_api}}', [
	'id' => 'INT(4) UNSIGNED NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(255) NOT NULL',
	'name_en' => 'VARCHAR(255) NOT NULL',
	'domain' => 'VARCHAR(255) NOT NULL',
	'route' => 'VARCHAR(255) NOT NULL',
	'type' => 'VARCHAR(255) NOT NULL',
	'value' => 'VARCHAR(255) NOT NULL',
	'encryption_mode' => 'VARCHAR(255) NOT NULL',
	'encryption_algorithm' => 'VARCHAR(255) NOT NULL',
	'is_sign' => 'INT(4) NULL',
	'description' => 'VARCHAR(255) NULL',
	'status' => 'INT(4) NOT NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%config_base}}', [
	'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
	'title' => 'VARCHAR(50) NOT NULL',
	'name' => 'VARCHAR(50) NOT NULL',
	'app_id' => 'VARCHAR(20) NOT NULL',
	'type' => 'VARCHAR(30) NOT NULL',
	'extra' => 'VARCHAR(1000) NOT NULL',
	'description' => 'VARCHAR(1000) NOT NULL',
	'is_hide_des' => 'TINYINT(4) NULL DEFAULT \'1\'',
	'default_value' => 'VARCHAR(500) NULL',
	'sort' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'status' => 'TINYINT(4) NOT NULL DEFAULT \'1\'',
	'created_at' => 'INT(11) UNSIGNED NOT NULL DEFAULT \'0\'',
	'updated_at' => 'INT(11) UNSIGNED NOT NULL DEFAULT \'0\'',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('type','{{%config_base}}','type',0);
$this->createIndex('uk_name','{{%config_base}}','name',0);
 
$this->createTable('{{%config_behavior}}', [
	'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
	'app_id' => 'VARCHAR(50) NULL',
	'url' => 'VARCHAR(200) NULL',
	'method' => 'VARCHAR(20) NULL',
	'behavior' => 'VARCHAR(50) NULL',
	'action' => 'TINYINT(4) UNSIGNED NULL DEFAULT \'1\'',
	'level' => 'VARCHAR(20) NULL',
	'is_record_post' => 'TINYINT(4) UNSIGNED NULL DEFAULT \'1\'',
	'is_ajax' => 'TINYINT(4) UNSIGNED NULL DEFAULT \'2\'',
	'remark' => 'VARCHAR(100) NULL',
	'addons_name' => 'VARCHAR(100) NOT NULL',
	'is_addon' => 'TINYINT(1) UNSIGNED NULL DEFAULT \'0\'',
	'status' => 'TINYINT(4) NULL DEFAULT \'1\'',
	'created_at' => 'INT(11) NULL DEFAULT \'0\'',
	'updated_at' => 'INT(11) UNSIGNED NULL DEFAULT \'0\'',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%config_behaviorlog}}', [
	'id' => 'INT(10) NOT NULL AUTO_INCREMENT',
	'merchant_id' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'app_id' => 'VARCHAR(50) NULL',
	'user_id' => 'INT(10) NOT NULL DEFAULT \'0\'',
	'behavior' => 'VARCHAR(50) NULL',
	'method' => 'VARCHAR(20) NULL',
	'module' => 'VARCHAR(50) NULL',
	'controller' => 'VARCHAR(50) NULL',
	'action' => 'VARCHAR(50) NULL',
	'url' => 'VARCHAR(200) NULL',
	'get_data' => 'LONGTEXT NULL',
	'post_data' => 'LONGTEXT NULL',
	'header_data' => 'LONGTEXT NULL',
	'ip' => 'VARCHAR(16) NULL',
	'addons_name' => 'VARCHAR(100) NOT NULL',
	'remark' => 'VARCHAR(1000) NULL',
	'country' => 'VARCHAR(50) NULL',
	'provinces' => 'VARCHAR(50) NULL',
	'city' => 'VARCHAR(50) NULL',
	'device' => 'VARCHAR(200) NULL',
	'status' => 'TINYINT(4) NOT NULL DEFAULT \'1\'',
	'created_at' => 'INT(10) NOT NULL DEFAULT \'0\'',
	'updated_at' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%config_constant}}', [
	'id' => 'INT(4) NOT NULL',
	'name' => 'VARCHAR(255) NOT NULL',
	'name_en' => 'VARCHAR(255) NOT NULL',
	'description' => 'VARCHAR(255) NULL',
	'status' => 'INT(4) NOT NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%config_dbengine}}', [
	'id' => 'INT(4) UNSIGNED NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(255) NOT NULL',
	'name_en' => 'VARCHAR(255) NOT NULL',
	'description' => 'VARCHAR(255) NULL',
	'status' => 'INT(4) NOT NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%config_email}}', [
	'id' => 'INT(12) UNSIGNED NOT NULL AUTO_INCREMENT',
	'scene' => 'VARCHAR(255) NOT NULL',
	'email' => 'VARCHAR(255) NOT NULL',
	'smtp_host' => 'VARCHAR(255) NOT NULL',
	'smtp_account' => 'VARCHAR(255) NOT NULL',
	'smtp_password' => 'VARCHAR(255) NOT NULL',
	'smtp_port' => 'VARCHAR(255) NOT NULL',
	'encryp_mode' => 'VARCHAR(255) NOT NULL',
	'activation_type' => 'INT(4) NOT NULL DEFAULT \'1\'',
	'token_time' => 'INT(11) NULL',
	'status' => 'INT(4) NOT NULL',
	'email_widget' => 'VARCHAR(255) NULL',
	'email_viewpatch' => 'VARCHAR(255) NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%config_functionlog}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'app_id' => 'VARCHAR(50) NULL',
	'merchant_id' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'user_id' => 'INT(11) NULL DEFAULT \'0\'',
	'method' => 'VARCHAR(20) NULL',
	'module' => 'VARCHAR(50) NULL',
	'controller' => 'VARCHAR(100) NULL',
	'action' => 'VARCHAR(50) NULL',
	'url' => 'VARCHAR(1000) NULL',
	'get_data' => 'LONGTEXT NULL',
	'post_data' => 'LONGTEXT NULL',
	'header_data' => 'LONGTEXT NULL',
	'ip' => 'VARCHAR(16) NULL',
	'error_code' => 'INT(10) NULL DEFAULT \'0\'',
	'error_msg' => 'VARCHAR(1000) NULL',
	'error_data' => 'LONGTEXT NULL',
	'req_id' => 'VARCHAR(50) NULL',
	'user_agent' => 'VARCHAR(200) NULL',
	'device' => 'VARCHAR(30) NULL',
	'device_uuid' => 'VARCHAR(50) NULL',
	'device_version' => 'VARCHAR(20) NULL',
	'device_app_version' => 'VARCHAR(20) NULL',
	'status' => 'TINYINT(4) NOT NULL DEFAULT \'1\'',
	'created_at' => 'INT(10) NULL DEFAULT \'0\'',
	'updated_at' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('error_code','{{%config_functionlog}}','error_code',0);
$this->createIndex('req_id','{{%config_functionlog}}','req_id',0);
 
$this->createTable('{{%config_ipmanage}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'ip' => 'VARCHAR(255) NOT NULL DEFAULT \'1\'',
	'status' => 'INT(4) NOT NULL DEFAULT \'1\'',
	'start_time' => 'INT(11) NULL',
	'end_time' => 'INT(11) NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%config_mutillang}}', [
	'id' => 'INT(5) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(255) NOT NULL',
	'name_en' => 'VARCHAR(255) NOT NULL',
	'description' => 'VARCHAR(255) NULL',
	'searchengine' => 'INT(11) NOT NULL',
	'status' => 'TINYINT(4) NOT NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('FK_searchengine_id','{{%config_mutillang}}','searchengine',0);
 
$this->createTable('{{%config_searchengine}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(255) NOT NULL',
	'name_en' => 'VARCHAR(255) NULL',
	'description' => 'VARCHAR(255) NULL',
	'status' => 'VARCHAR(255) NOT NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%config_smslog}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'merchant_id' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'member_id' => 'INT(11) UNSIGNED NULL DEFAULT \'0\'',
	'mobile' => 'VARCHAR(20) NULL',
	'code' => 'VARCHAR(6) NULL',
	'content' => 'VARCHAR(500) NULL',
	'error_code' => 'INT(10) NULL DEFAULT \'0\'',
	'error_msg' => 'VARCHAR(200) NULL',
	'error_data' => 'LONGTEXT NULL',
	'usage' => 'VARCHAR(20) NULL',
	'used' => 'TINYINT(1) NULL DEFAULT \'0\'',
	'use_time' => 'INT(10) NULL DEFAULT \'0\'',
	'ip' => 'VARCHAR(30) NULL',
	'status' => 'TINYINT(4) NOT NULL DEFAULT \'1\'',
	'created_at' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'updated_at' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('error_code','{{%config_smslog}}','error_code',0);
 
$this->createTable('{{%config_sysinfo}}', [
	'siteid' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(30) NOT NULL',
	'dirname' => 'VARCHAR(200) NOT NULL',
	'domain' => 'VARCHAR(100) NOT NULL',
	'serveralias' => 'VARCHAR(255) NULL',
	'keywords' => 'VARCHAR(255) NOT NULL',
	'description' => 'VARCHAR(255) NOT NULL',
	'site_point' => 'VARCHAR(50) NOT NULL',
	'smarty_id' => 'TINYINT(3) UNSIGNED NULL DEFAULT \'1\'',
	'smarty_app_id' => 'TINYINT(3) UNSIGNED NULL DEFAULT \'1\'',
	'address' => 'TEXT NULL',
	'zipcode' => 'VARCHAR(50) NULL',
	'tel' => 'VARCHAR(50) NULL',
	'fax' => 'VARCHAR(50) NULL',
	'email' => 'VARCHAR(50) NOT NULL',
	'copyright' => 'VARCHAR(50) NULL',
	'logo' => 'TEXT NULL',
	'banner' => 'TEXT NULL',
	'reg_time' => 'VARCHAR(50) NULL',
	'begin_time' => 'VARCHAR(15) NULL',
	'end_time' => 'VARCHAR(15) NULL',
	'basemail' => 'VARCHAR(60) NULL',
	'mailpwd' => 'VARCHAR(60) NULL',
	'record' => 'VARCHAR(50) NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'default_style' => 'VARCHAR(50) NOT NULL',
	'contacts' => 'VARCHAR(20) NOT NULL DEFAULT \'未填写\'',
	'comp_invoice' => 'VARCHAR(100) NULL',
	'comp_bank' => 'VARCHAR(100) NULL',
	'bank_numb' => 'VARCHAR(25) NULL',
	'PRIMARY KEY (`siteid`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%config_variable}}', [
	'id' => 'INT(4) NOT NULL',
	'name' => 'VARCHAR(255) NOT NULL',
	'name_en' => 'VARCHAR(255) NOT NULL',
	'description' => 'VARCHAR(255) NULL',
	'status' => 'INT(4) NOT NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%migration}}', [
	'version' => 'VARCHAR(180) NOT NULL',
	'apply_time' => 'INT(11) NULL',
	'PRIMARY KEY (`version`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%reg_extension}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'title' => 'VARCHAR(20) NOT NULL',
	'name' => 'VARCHAR(100) NOT NULL',
	'title_initial' => 'VARCHAR(50) NOT NULL',
	'bootstrap' => 'VARCHAR(200) NULL',
	'service' => 'VARCHAR(200) NULL',
	'cover' => 'VARCHAR(200) NULL',
	'brief_introduction' => 'VARCHAR(140) NULL',
	'description' => 'VARCHAR(1000) NULL',
	'author' => 'VARCHAR(40) NULL',
	'version' => 'VARCHAR(20) NULL',
	'is_setting' => 'TINYINT(1) NULL DEFAULT \'-1\'',
	'is_rule' => 'TINYINT(4) NULL DEFAULT \'0\'',
	'is_merchant_route_map' => 'TINYINT(1) NULL DEFAULT \'0\'',
	'default_config' => 'LONGTEXT NULL',
	'console' => 'LONGTEXT NULL',
	'status' => 'TINYINT(4) NULL DEFAULT \'1\'',
	'created_at' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'updated_at' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('name','{{%reg_extension}}','name',0);
$this->createIndex('update','{{%reg_extension}}','updated_at',0);
 
$this->createTable('{{%reg_module}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'title' => 'VARCHAR(20) NOT NULL',
	'name' => 'VARCHAR(100) NOT NULL',
	'title_initial' => 'VARCHAR(50) NOT NULL',
	'bootstrap' => 'VARCHAR(200) NULL',
	'service' => 'VARCHAR(200) NULL',
	'cover' => 'VARCHAR(200) NULL',
	'brief_introduction' => 'VARCHAR(140) NULL',
	'description' => 'VARCHAR(1000) NULL',
	'author' => 'VARCHAR(40) NULL',
	'version' => 'VARCHAR(20) NULL',
	'is_setting' => 'TINYINT(1) NULL DEFAULT \'-1\'',
	'is_rule' => 'TINYINT(4) NULL DEFAULT \'0\'',
	'is_merchant_route_map' => 'TINYINT(1) NULL DEFAULT \'0\'',
	'default_config' => 'LONGTEXT NULL',
	'console' => 'LONGTEXT NULL',
	'status' => 'TINYINT(4) NULL DEFAULT \'1\'',
	'created_at' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'updated_at' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('name','{{%reg_module}}','name',0);
$this->createIndex('update','{{%reg_module}}','updated_at',0);
 
$this->createTable('{{%reg_software}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'title' => 'VARCHAR(80) NOT NULL',
	'name' => 'VARCHAR(100) NOT NULL',
	'title_initial' => 'VARCHAR(50) NOT NULL',
	'bootstrap' => 'VARCHAR(200) NULL',
	'service' => 'VARCHAR(200) NULL',
	'cover' => 'VARCHAR(200) NULL',
	'brief_introduction' => 'VARCHAR(140) NULL',
	'description' => 'VARCHAR(1000) NULL',
	'author' => 'VARCHAR(80) NULL',
	'version' => 'VARCHAR(20) NULL',
	'is_setting' => 'TINYINT(1) NULL DEFAULT \'-1\'',
	'is_rule' => 'TINYINT(4) NULL DEFAULT \'0\'',
	'parent_rule_name' => 'VARCHAR(255) NULL',
	'route_map' => 'VARCHAR(100) NULL',
	'default_config' => 'LONGTEXT NULL',
	'console' => 'LONGTEXT NULL',
	'status' => 'TINYINT(4) NULL DEFAULT \'1\'',
	'created_at' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'updated_at' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'sortOrder' => 'INT(11) NULL',
	'is_del' => 'TINYINT(1) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('name','{{%reg_software}}','name',0);
$this->createIndex('update','{{%reg_software}}','updated_at',0);
 
$this->createTable('{{%reg_widgets}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'title' => 'VARCHAR(20) NOT NULL',
	'name' => 'VARCHAR(100) NOT NULL',
	'title_initial' => 'VARCHAR(50) NOT NULL',
	'bootstrap' => 'VARCHAR(200) NULL',
	'service' => 'VARCHAR(200) NULL',
	'cover' => 'VARCHAR(200) NULL',
	'brief_introduction' => 'VARCHAR(140) NULL',
	'description' => 'VARCHAR(1000) NULL',
	'author' => 'VARCHAR(40) NULL',
	'version' => 'VARCHAR(20) NULL',
	'is_setting' => 'TINYINT(1) NULL DEFAULT \'-1\'',
	'is_rule' => 'TINYINT(4) NULL DEFAULT \'0\'',
	'is_merchant_route_map' => 'TINYINT(1) NULL DEFAULT \'0\'',
	'default_config' => 'LONGTEXT NULL',
	'console' => 'LONGTEXT NULL',
	'status' => 'TINYINT(4) NULL DEFAULT \'1\'',
	'created_at' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'updated_at' => 'INT(10) UNSIGNED NULL DEFAULT \'0\'',
	'created_id' => 'INT(11) NULL',
	'updated_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('name','{{%reg_widgets}}','name',0);
$this->createIndex('update','{{%reg_widgets}}','updated_at',0);
 
$this->createTable('{{%review_log}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'news_id' => 'INT(11) NOT NULL',
	'status' => 'VARCHAR(127) NULL',
	'changed_status' => 'VARCHAR(127) NULL',
	'content' => 'TEXT NULL',
	'user_id' => 'INT(11) NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'deleted_at' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%sw_metadata}}', [
	'workflow_id' => 'VARCHAR(32) NOT NULL',
	'status_id' => 'VARCHAR(32) NOT NULL',
	'key' => 'VARCHAR(64) NOT NULL',
	'value' => 'VARCHAR(255) NULL',
	'PRIMARY KEY (`workflow_id`,`status_id`,`key`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('workflow_status_id','{{%sw_metadata}}','workflow_id, status_id, key',1);
 
$this->createTable('{{%sw_status}}', [
	'id' => 'VARCHAR(32) NOT NULL',
	'workflow_id' => 'VARCHAR(32) NOT NULL',
	'label' => 'VARCHAR(64) NULL',
	'sort_order' => 'INT(11) NULL',
	'PRIMARY KEY (`id`,`workflow_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('workflow_id','{{%sw_status}}','workflow_id',0);
 
$this->createTable('{{%sw_transition}}', [
	'workflow_id' => 'VARCHAR(32) NOT NULL',
	'start_status_id' => 'VARCHAR(32) NOT NULL',
	'end_status_id' => 'VARCHAR(32) NOT NULL',
	'PRIMARY KEY (`workflow_id`,`start_status_id`,`end_status_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('workflow_start_status_id','{{%sw_transition}}','workflow_id, start_status_id',0);
$this->createIndex('workflow_end_status_id','{{%sw_transition}}','workflow_id, end_status_id',0);
 
$this->createTable('{{%sw_workflow}}', [
	'id' => 'VARCHAR(32) NOT NULL',
	'initial_status_id' => 'VARCHAR(32) NULL',
	'initial_status_id_cn' => 'VARCHAR(255) NULL',
	'name' => 'VARCHAR(255) NULL',
	'name_cn' => 'VARCHAR(255) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('initial_status_id','{{%sw_workflow}}','initial_status_id',0);
 
$this->createTable('{{%third_interface_key}}', [
	'id' => 'INT(10) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(50) NOT NULL DEFAULT \'1\'',
	'clientKey' => 'VARCHAR(30) NOT NULL',
	'clientSecret' => 'VARCHAR(50) NOT NULL',
	'callBackUrl' => 'VARCHAR(100) NOT NULL',
	'type' => 'ENUM(\'1\',\'2\',\'3\') NOT NULL',
	'unitId' => 'INT(10) NOT NULL',
	'is_del' => 'ENUM(\'0\',\'1\') NOT NULL',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('clientKey','{{%third_interface_key}}','clientKey',0);
 
$this->createTable('{{%wom_plan}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'title' => 'VARCHAR(100) NOT NULL',
	'desc' => 'VARCHAR(255) NOT NULL',
	'status' => 'TINYINT(1) NOT NULL DEFAULT \'0\'',
	'time_status' => 'TINYINT(1) NOT NULL DEFAULT \'0\'',
	'admin_id' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'start_at' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'end_at' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'created_at' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'created_id' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'updated_at' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'updated_id' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createIndex('status','{{%wom_plan}}','status',0);
$this->createIndex('KF_plantime_status','{{%wom_plan}}','time_status',0);
 
$this->createTable('{{%wom_plan_status}}', [
	'id' => 'TINYINT(11) NOT NULL',
	'name' => 'VARCHAR(255) NOT NULL',
	'position' => 'INT(255) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->createTable('{{%wom_plantime_status}}', [
	'id' => 'TINYINT(11) NOT NULL',
	'name' => 'VARCHAR(255) NOT NULL',
	'position' => 'INT(255) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
$this->addForeignKey('FK_admindep_status', '{{%admin_dep}}', 'd_status', '{{%admin_dep_status}}', 'id', 'CASCADE', 'NO ACTION' );
$this->addForeignKey('FK_adminteam_status', '{{%admin_team}}', 't_status', '{{%admin_team_status}}', 'id', 'CASCADE', 'NO ACTION' );
$this->addForeignKey('FK_adminunit_status', '{{%admin_unit}}', 'u_status', '{{%admin_unit_status}}', 'id', 'CASCADE', 'NO ACTION' );
$this->addForeignKey('auth_assignment_ibfk_1', '{{%auth_assignment}}', 'item_name', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE' );
$this->addForeignKey('auth_item_ibfk_1', '{{%auth_item}}', 'rule_name', '{{%auth_rule}}', 'name', 'SET NULL', 'CASCADE' );
$this->addForeignKey('auth_item_child_ibfk_1', '{{%auth_item_child}}', 'parent', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE' );
$this->addForeignKey('auth_item_child_ibfk_2', '{{%auth_item_child}}', 'child', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE' );
$this->addForeignKey('FK_searchengine_id', '{{%config_mutillang}}', 'searchengine', '{{%config_searchengine}}', 'id', 'CASCADE', 'RESTRICT' );
$this->addForeignKey('KF_plan_status', '{{%wom_plan}}', 'status', '{{%wom_plan_status}}', 'id', 'CASCADE', 'RESTRICT' );
$this->addForeignKey('KF_plantime_status', '{{%wom_plan}}', 'time_status', '{{%wom_plantime_status}}', 'id', 'CASCADE', 'RESTRICT' );
 
$this->execute('SET foreign_key_checks = 1;');
$this->execute('SET foreign_key_checks = 0');
 
/* Table admin */
$this->batchInsert('{{%admin}}',['user_id','username','real_name','password_hash','password_reset_hash','email','email_verify_token','phone','auth_key','access_token','role','role_id','status','created_at','updated_at','dep_isleader','team_leader'],[['100007','administrator','admin_test','$2y$13$4jBON5wv8gsitmaNhMCXIu0nrUzXnzHuqYhl8/HiVF71OcI9H80qa',null,'1234@qq.com',null,'18310888888','','','1','admin','10','1584353337','1584353337','0','0'],
['100008','admin1','admin1','$2y$13$ISwBCOyGSPMJsn/piYXBCu.xdkPNoK8ikbYuWGyVslZRIndWRmQzK',null,'123@qq.com',null,'18310316794','','','1',',admin,N O N O','0','1542126369','1572404255','0','0'],
['100009','admin2','张金融','$2y$13$ISwBCOyGSPMJsn/piYXBCu.xdkPNoK8ikbYuWGyVslZRIndWRmQzK',null,'123@qq.com',null,'18310318888','','','1','admin','0','1583993044','1583993044','0','0'],
['100012','admin3','王伟华','$2y$13$77sMCxtSdLhtJvW88JytK.qNSEmhvSE7QgUzEk1gmChqL9FBeEV.m',null,'1@qq.com',null,'17090034521','','','1','admin,N O N O','0','1571494446','1572403518','0','0'],
['100013','admin3','王伟华','$2y$13$u5GTa4HasifSvwVgOdxAI.M9yVf.uMiNyJyObX/8B4VfITS5waB2q',null,'1@qq.com',null,'17090034521','','','1','admin','0','1571496923','1572008718','0','0'],
['100014','admin3','王伟华','$2y$13$cdTQ9XrRV1BrXfYm0vxcD.hBGlelbEsEn5Q3kQeLuWDGpUX1AoNGK',null,'1@qq.com',null,'17090034521','','','1','admin','0','1571496925','1572008718','0','0'],
['100015','admin3','王伟华','$2y$13$oLJJ/FqzrQc8l1lDvaA0EeJ//.xd.bcpLjHlKNTSYKSiJ/wWAdwi.',null,'1@qq.com',null,'17090034521','','','1','admin','0','1571496925','1572008718','0','0'],
['100016','admin3','王伟华','$2y$13$vsQUAIftYKmsJQ5.bmP92eq3fO60YYxwG4cs/8PxabafjbdUPYJuG',null,'1@qq.com',null,'17090034521','','','1','admin','0','1571497083','1572008718','0','0'],
['100017','admin2','王伟华1','$2y$13$pZgyU5l0fvLbqSHhU.SeveZzR5mINp.Vkvy70iXPnb7zT.KDX.iYq',null,'1@qq.com',null,'18310316794','','','1','admin','0','1571500281','1572008718','0','0'],
['100018','Test','王磊','$2y$13$9F1DKBtXeKb14bR.Dtk.GOWxpstnEClFqRfPzA.32jCMohsN2ZVoq',null,'2@qq.com',null,'17098888888','','','1','Chief-executive-officer,Website-editor','10','1584170123','1584170123','0','1'],
['100019','Test2','测试','$2y$13$tvY06LxIXbqtssLBkhFtvebIoSbpzG5YNI96/fHe05nvpIblX6Fw2',null,'12@qq.com',null,'18388888888','','','1','Propaganda-minister,Website-editor','10','1584170148','1584170148','0','0'],
['100022','wang','wang','$2y$13$Pe9y.K3XjBJP.jzYm0Wcve5xKRJg/wnz3DM3PffOwvpprYAEuDO8G',null,'1@qq.copm',null,'18366666666','','','1','ordinary-staff','10','1584170173','1584170173','1','0'],
['100023','lei','lei','$2y$13$Pi9n/AEGuiwMKfOLp6kAM.2Ft4/cb/ZF/rPcTO54iC8teIlj3xb2q',null,'123456@qq.com',null,'18310316701','','','1','N O N O,admin,Chief-executive-officer','0','1574087871','1574087871','0','0'],
['100024','admin_demo','admin_demo','$2y$13$x53YZA2Hr90vCodZuflfye90/EmRi5WwYyQsSw/FoInaeQEiItDfG',null,'123@12345.com',null,'13911111111','','','1','Network-section-chief','10','1584351942','1584351942','0','0'],
]);
 
/* Table admin_auth_relation */
$this->batchInsert('{{%admin_auth_relation}}',['id','unitid','depid','teamid','appid','siteid','adminid','type','created_at','updated_at'],[['80','1010','73','0','0','0','100023','1','1576426765','1576426765'],
['81','1010','73','0','0','0','100018','1','1576426765','1576426765'],
['82','1009','72','0','0','0','100023','1','1576426787','1576426787'],
['83','1009','72','0','0','0','100022','1','1576426787','1576426787'],
['84','1009','72','0','0','0','100018','1','1576426787','1576426787'],
['85','1010','69','0','0','0','100018','1','1576426806','1576426806'],
['86','1009','0','71','0','0','100018','2','1577001018','1577001018'],
['87','1010','0','70','0','0','100018','2','1577001048','1577001048'],
['88','1009','71','0','0','0','100022','1','1583662648','1583662648'],
['89','1009','71','0','0','0','100018','1','1583662648','1583662648'],
['90','1010','0','0','0','1','100009','0','1583993044','1583993044'],
['91','1009','0','0','0','1','100018','0','1583993206','1583993206'],
['92','1009','0','0','0','1','100018','0','1583993935','1583993935'],
['93','1009','0','0','0','1','100018','0','1583993949','1583993949'],
['94','1010','0','0','0','1','100019','0','1583993988','1583993988'],
['95','1009','0','0','0','1','100022','0','1583994006','1583994006'],
['96','1010','0','0','0','1','100007','0','1584170050','1584170050'],
['97','1009','0','0','0','1','100018','0','1584170123','1584170123'],
['98','1010','0','0','0','1','100019','0','1584170148','1584170148'],
['99','1009','0','0','0','1','100022','0','1584170173','1584170173'],
['100','1010','0','0','0','1','100024','0','1584351943','1584351943'],
['101','1010','0','0','0','1','100007','0','1584352046','1584352046'],
['102','1010','0','0','0','1','100007','0','1584353337','1584353337'],
]);
 
/* Table admin_dep */
$this->batchInsert('{{%admin_dep}}',['depid','name','description','d_status','father_id','unit_id','siteid','sort_id','app_id','created_at','updated_at','is_del','auth_item_id'],[['64','测试部门','TestDep','10','1','1010','1','1',null,'1576420304','1576420304','1','Dep_64'],
['65','测试部门','TestDep','10','1','1010','1','1',null,'1576420741','1576420741','1','Dep_65'],
['66','媒体云部门','UnitDep1','10','1','1009','1','1',null,'1576420794','1576420794','0','Dep_66'],
['67','财务部','CWDep','10','1','1010','1','1',null,'1576420863','1576420863','0','Dep_67'],
['68','财务部','MTCWDep','10','1','1009','1','1',null,'1576421012','1584169881','0','Dep_68'],
['69','人力资源','RLDep','10','1','1010','1','1',null,'1576421062','1576421062','0','Dep_69'],
['70','行政办公室','OADep','10','1','1010','1','1',null,'1576421339','1576421339','0','Dep_70'],
['71','行政办公室','行政办公室','10','1','1009','1','1',null,'1576421401','1584169870','0','Dep_71'],
['72','技术运维部','技术运维部','10','1','1009','1','1',null,'1576421475','1584169859','0','Dep_72'],
['73','X技术运维部','X技术运维部','10','1','1010','1','1',null,'1576421512','1576421512','0','Dep_73'],
]);
 
/* Table admin_dep_status */
$this->batchInsert('{{%admin_dep_status}}',['id','name','position'],[['10','激活','1'],
['11','停用','2'],
]);
 
/* Table admin_status */
$this->batchInsert('{{%admin_status}}',['id','name','position'],[['1','开启','1'],
['2','停用','1'],
['3','开启','1'],
]);
 
/* Table admin_team */
$this->batchInsert('{{%admin_team}}',['teamid','name','description','father_id','t_status','unit_id','sort_id','is_del','created_at','updated_at','auth_item_id'],[['70','xwom团队','xwom团队123','1','10','1010','1','0','1577000603','1577000604','Team_70'],
['71','媒体云团队','媒体云团队媒体云团队','1','10','1009','1','0','1577000714','1577000714','Team_71'],
]);
 
/* Table admin_team_status */
$this->batchInsert('{{%admin_team_status}}',['id','name','position'],[['10','停用','1'],
['11','启用','2'],
]);
 
/* Table admin_unit */
$this->batchInsert('{{%admin_unit}}',['unitid','name','description','u_status','siteid','sort_id','app_id','created_at','updated_at','is_del','auth_item_id'],[['1009','单位2','单位2描述，这里是描述性文字','10','1','2','3','1572248902','1584169787','0','Team_1009'],
['1010','单位1','这里是单位1的描述性文字，可以多写一些','11','0','0','0','1572249105','1584169822','0','Unit_1010'],
['1011','fff','fff','10','0','0','0','1585445900','1585445900','0','Unit_1011'],
]);
 
/* Table admin_unit_status */
$this->batchInsert('{{%admin_unit_status}}',['id','name','position'],[['10','激活','1'],
['11','停用','2'],
]);
 
/* Table auth_assignment */
$this->batchInsert('{{%auth_assignment}}',['item_name','user_id','created_at'],[['admin','100007','1584353337'],
['admin','100011','1571494225'],
['admin','100020','1574073486'],
['admin','100021','1574073596'],
['Chief-executive-officer','100018','1584170123'],
['Network-section-chief','100024','1584351943'],
['ordinary-staff','100022','1584170173'],
['Propaganda-minister','100019','1584170148'],
['Website-editor','100018','1584170123'],
['Website-editor','100019','1584170148'],
]);
 
/* Table auth_item */
$this->batchInsert('{{%auth_item}}',['name','type','parent_name','description','rule_name','data','created_at','updated_at','status','order_sort','icon','is_menu'],[['admin','1',null,'超级管理员',null,null,'1542024681','1552725984','1','999',null,'0'],
['App1System','2',null,'App1 system',null,null,'1559146097','1572070022','1','100','','1'],
['Auth','2','XwomSystem','权限管理',null,null,'1559146097','1581414357','1','12','layui-icon-set','1'],
['auth/add-admin','2','auth/admin-list','添加/编辑管理员',null,null,'1572076507','1572077243','1','100','','0'],
['auth/add-auth','2','auth/auth-list','添加/编辑角色',null,null,'1572076454','1572077223','1','100','','0'],
['auth/add-menu','2','auth/menu-list','添加/编辑菜单',null,null,'1572070957','1572077205','1','100','','0'],
['auth/admin-list','2','Auth','管理员管理',null,null,'1559146097','1581415332','1','107','layui-icon-friends','1'],
['auth/auth-list','2','Auth','角色管理',null,null,'1559146097','1581415293','1','109','layui-icon-group','1'],
['auth/menu-list','2','XwomSystem','菜单管理',null,null,'1559146097','1581413449','1','110','','1'],
['auth/subsystem','2','Auth','子系统管理',null,null,'1583663046','1583667430','1','100','layui-icon-rate-half','1'],
['auth/subsystem-edit','2','auth/subsystem','添加/修改',null,null,'1583663474','1583667388','1','100','layui-icon-left','0'],
['auth/subsystem-permission','2','auth/subsystem','权限分配',null,null,'1583663542','1583667370','1','100','layui-icon-left','0'],
['Chief-executive-officer','1',null,'终审主任部长',null,null,'1545051916','1583993569','1','100','','0'],
['common/app_register','2','XwomSystem','应用注册管理',null,null,'1582188110','1582468640','1','100','layui-icon-set','1'],
['common/bpm/index','2','XwomSystem','流程管理',null,null,'1580957045','1583805254','1','100','layui-icon-set','1'],
['common/config-api/create','2','common/config-api/index','添加',null,null,'1583553541','1583553541','1','100','layui-icon-left','0'],
['common/config-api/delete','2','common/config-api/index','删除',null,null,'1583553493','1583553493','1','100','layui-icon-left','0'],
['common/config-api/index','2','common/config/allset','全栈API设置',null,null,'1580955834','1581412535','1','100','layui-icon-component','1'],
['common/config-api/update','2','common/config-api/index','修改',null,null,'1583553458','1583553458','1','100','layui-icon-left','0'],
['common/config-api/view','2','common/config-api/index','查看',null,null,'1583553563','1583553563','1','100','layui-icon-left','0'],
['common/config-base/create','2','common/config-base/index','添加',null,null,'1583378703','1583378703','1','100','layui-icon-left','0'],
['common/config-base/delete','2','common/config-base/index','删除',null,null,'1583378603','1583378603','1','100','layui-icon-left','0'],
['common/config-base/index','2','common/config/allset','全栈基础配置',null,null,'1582346531','1582346531','1','101','layui-icon-flag','1'],
['common/config-base/update','2','common/config-base/index','修改',null,null,'1583378567','1583378567','1','100','layui-icon-left','0'],
['common/config-base/view','2','common/config-base/index','查看',null,null,'1583382755','1583382755','1','100','layui-icon-left','0'],
['common/config-behavior/create','2','common/config-behavior/index','添加',null,null,'1583401126','1583401126','1','100','layui-icon-left','0'],
['common/config-behavior/delet','2','common/config-behavior/index','删除',null,null,'1583401080','1583401080','1','100','layui-icon-left','0'],
['common/config-behavior/index','2','common/config/allset','全栈行为监控',null,null,'1580956784','1583421953','1','100','layui-icon-set-sm','1'],
['common/config-behavior/update','2','common/config-behavior/index','修改',null,null,'1583401054','1583401054','1','100','layui-icon-left','0'],
['common/config-behavior/view','2','common/config-behavior/index','查看',null,null,'1583401162','1583401162','1','100','layui-icon-left','0'],
['common/config-behaviorlog/index','2','commonLog','行为日志',null,null,'1580955519','1583421669','1','100','layui-icon-right','1'],
['common/config-constant/create','2','common/config-constant/index','添加',null,null,'1583553405','1583553405','1','100','layui-icon-left','0'],
['common/config-constant/delete','2','common/config-constant/index','删除',null,null,'1583553377','1583553377','1','100','layui-icon-left','0'],
['common/config-constant/index','2','common/config/allset','全栈常量配置',null,null,'1580956175','1581412508','1','100','layui-icon-date','1'],
['common/config-constant/update','2','common/config-constant/index','修改',null,null,'1583553349','1583553349','1','100','layui-icon-left','0'],
['common/config-constant/view','2','common/config-constant/index','查看',null,null,'1583553426','1583553426','1','100','layui-icon-left','0'],
['common/config-dbengine/create','2','common/config-dbengine/index','添加',null,null,'1583553101','1583553101','1','100','layui-icon-left','0'],
['common/config-dbengine/dalete','2','common/config-dbengine/index','删除',null,null,'1583553077','1583553077','1','100','layui-icon-left','0'],
['common/config-dbengine/index','2','common/config/allset','全栈DB引擎配置',null,null,'1582347217','1582347217','1','100','layui-icon-app','1'],
['common/config-dbengine/update','2','common/config-dbengine/index','修改',null,null,'1583553052','1583553052','1','100','layui-icon-left','0'],
['common/config-dbengine/view','2','common/config-dbengine/index','查看',null,null,'1583553132','1583553132','1','100','layui-icon-left','0'],
['common/config-email/create','2','common/config-email/index','添加',null,null,'1583552652','1583552652','1','100','layui-icon-left','0'],
['common/config-email/delete','2','common/config-email/index','删除',null,null,'1583552609','1583552609','1','100','layui-icon-left','0'],
['common/config-email/index','2','common/config/allset','全栈邮件配置',null,null,'1582347554','1582347554','1','100','layui-icon-website','1'],
['common/config-email/update','2','common/config-email/index','修改',null,null,'1583552551','1583552551','1','100','layui-icon-left','0'],
['common/config-email/view','2','common/config-email/index','查看',null,null,'1583552675','1583552675','1','100','layui-icon-left','0'],
['common/config-functionlog/index','2','commonLog','全局日志',null,null,'1580955589','1583421626','1','100','layui-icon-right','1'],
['common/config-ipmanage/index','2','common/config/allset','IP黑名单',null,null,'1580956737','1583421753','1','98','layui-icon-util','1'],
['common/config-mutillang/create','2','common/config-mutillang/index','添加',null,null,'1583552813','1583552813','1','100','layui-icon-left','0'],
['common/config-mutillang/delete','2','common/config-mutillang/index','删除',null,null,'1583552750','1583552750','1','100','layui-icon-left','0'],
['common/config-mutillang/index','2','common/config/allset','全栈多语言包管理',null,null,'1582347452','1582347452','1','100','layui-icon-home','1'],
['common/config-mutillang/update','2','common/config-mutillang/index','修改',null,null,'1583552708','1583552708','1','100','layui-icon-left','0'],
['common/config-mutillang/view','2','common/config-mutillang/index','查看',null,null,'1583552834','1583552834','1','100','layui-icon-left','0'],
['common/config-searchengine/create','2','common/config-searchengine/index','添加',null,null,'1583552942','1583552942','1','100','layui-icon-left','0'],
['common/config-searchengine/dalete','2','common/config-searchengine/index','删除',null,null,'1583552901','1583552901','1','100','layui-icon-left','0'],
['common/config-searchengine/index','2','common/config/allset','全栈搜索引擎配置',null,null,'1582347329','1582347329','1','100','layui-icon-template-1','1'],
['common/config-searchengine/update','2','common/config-searchengine/index','修改',null,null,'1583552878','1583552878','1','100','layui-icon-left','0'],
['common/config-searchengine/view','2','common/config-searchengine/index','查看',null,null,'1583552981','1583552981','1','100','layui-icon-left','0'],
['common/config-smslog/index','2','commonLog','短信日志',null,null,'1580955556','1583421648','1','100','layui-icon-right','1'],
['common/config-sysinfo/create','2','common/config-sysinfo/index','添加',null,null,'1583315316','1583315316','1','100','layui-icon-left','0'],
['common/config-sysinfo/index','2','common/config/allset','系统信息',null,null,'1580874576','1583421798','1','100','layui-icon-set','1'],
['common/config-sysinfo/update','2','common/config-sysinfo/index','修改',null,null,'1583315350','1583315350','1','100','layui-icon-left','0'],
['common/config-sysinfo/view','2','common/config-sysinfo/index','浏览',null,null,'1583315391','1583315391','1','100','layui-icon-left','0'],
['common/config-variable/create','2','common/config-variable/index','添加',null,null,'1583553260','1583553260','1','100','layui-icon-left','0'],
['common/config-variable/delete','2','common/config-variable/index','删除',null,null,'1583553230','1583553230','1','100','layui-icon-left','0'],
['common/config-variable/index','2','common/config/allset','全栈变量设置',null,null,'1580956414','1581412468','1','100','layui-icon-template','1'],
['common/config-variable/update','2','common/config-variable/index','修改',null,null,'1583553195','1583553195','1','100','layui-icon-left','0'],
['common/config-variable/view','2','common/config-variable/index','查看',null,null,'1583553289','1583553289','1','100','layui-icon-left','0'],
['common/config/allset','2','XwomSystem','全栈全局设置',null,null,'1580955662','1581413630','1','100','layui-icon-set','1'],
['common/reg-extension/create','2','common/reg-extension/index','添加',null,null,'1583395607','1583395607','1','100','layui-icon-left','0'],
['common/reg-extension/delete','2','common/reg-extension/index','删除',null,null,'1583395562','1583395562','1','100','layui-icon-left','0'],
['common/reg-extension/index','2','common/app_register','扩展管理',null,null,'1582188770','1582188770','1','100','layui-icon-left','1'],
['common/reg-extension/update','2','common/reg-extension/index','修改',null,null,'1583395539','1583395539','1','100','layui-icon-left','0'],
['common/reg-extension/view','2','common/reg-extension/index','查看',null,null,'1583395582','1583395582','1','100','layui-icon-left','0'],
['common/reg-module/create','2','common/reg-module/index','添加',null,null,'1583395707','1583395707','1','100','layui-icon-left','0'],
['common/reg-module/delete','2','common/reg-module/index','删除',null,null,'1583395664','1583395664','1','100','layui-icon-left','0'],
['common/reg-module/index','2','common/app_register','模块管理',null,null,'1582188620','1582188620','1','100','layui-icon-left','1'],
['common/reg-module/update','2','common/reg-module/index','修改',null,null,'1583395639','1583395639','1','100','layui-icon-left','0'],
['common/reg-module/view','2','common/reg-module/index','查看',null,null,'1583395685','1583395685','1','100','layui-icon-left','0'],
['common/reg-software/create','2','common/reg-software/index','添加',null,null,'1583395418','1583395418','1','100','layui-icon-left','0'],
['common/reg-software/delete','2','common/reg-software/index','删除',null,null,'1583395374','1583395374','1','100','layui-icon-left','0'],
['common/reg-software/index','2','common/app_register','子应用管理',null,null,'1582191198','1582191198','1','100','layui-icon-left','1'],
['common/reg-software/update','2','common/reg-software/index','修改',null,null,'1583395347','1583395347','1','100','layui-icon-left','0'],
['common/reg-software/view','2','common/reg-software/index','查看',null,null,'1583395400','1583395400','1','100','layui-icon-left','0'],
['common/reg-widgets/crearte','2','common/reg-widgets/index','添加',null,null,'1583395509','1583395509','1','100','layui-icon-left','0'],
['common/reg-widgets/delete','2','common/reg-widgets/index','删除',null,null,'1583395469','1583395469','1','100','layui-icon-left','0'],
['common/reg-widgets/index','2','common/app_register','小部件管理',null,null,'1582189139','1582189139','1','100','layui-icon-left','1'],
['common/reg-widgets/update','2','common/reg-widgets/index','修改',null,null,'1583395445','1583395445','1','100','layui-icon-left','0'],
['common/reg-widgets/view','2','common/reg-widgets/index','查看',null,null,'1583395487','1583395487','1','100','layui-icon-left','0'],
['common/wom-plan/approve','2','common/wom-plan/index','接受任务',null,null,'1582910546','1582910562','1','100','layui-icon-left','0'],
['common/wom-plan/create','2','common/wom-plan/index','添加',null,null,'1582650607','1582650607','1','100','layui-icon-left','0'],
['common/wom-plan/delete','2','common/wom-plan/index','删除',null,null,'1582650512','1582650549','1','100','layui-icon-left','0'],
['common/wom-plan/index','2','XwomSystem','计划管理',null,null,'1582650183','1584153394','1','100','layui-icon-right','1'],
['common/wom-plan/update','2','common/wom-plan/index','更新',null,null,'1582650582','1582650582','1','100','layui-icon-left','0'],
['common/wom-plan/view','2','common/wom-plan/index','查看',null,null,'1582650538','1582650538','1','100','layui-icon-left','0'],
['commonLog','2','XwomSystem','全栈日志记录',null,null,'1580955472','1583421492','1','99','layui-icon-template','1'],
['creat','2','seeker_set','添加',null,null,'1582004286','1582004302','1','100','layui-icon-left','0'],
['Dep_65','4',null,'测试部门',null,null,'1576420741','1576420741','1','100','','0'],
['Dep_66','4',null,'媒体云部门',null,null,'1576420794','1576420794','1','100','','0'],
['Dep_67','4',null,'财务部',null,null,'1576420863','1576420863','1','100','','0'],
['Dep_68','4',null,'媒体财务部',null,null,'1576421012','1576421012','1','100','','0'],
['Dep_69','4',null,'人力资源',null,null,'1576421062','1576421062','1','100','','0'],
['Dep_70','4',null,'行政办公室',null,null,'1576421339','1576421339','1','100','','0'],
['Dep_71','4',null,'媒体行政办公室',null,null,'1576421401','1576421401','1','100','','0'],
['Dep_72','4',null,'媒体技术运维部',null,null,'1576421475','1576421475','1','100','','0'],
['Dep_73','4',null,'X技术运维部',null,null,'1576421512','1576421512','1','100','','0'],
['dep/add-dep','2','dep/dep-list','编辑部门',null,null,'1583662768','1583662768','1','100','layui-icon-left','0'],
['dep/batch-port','2','dep/dep-list','批量移除人员',null,null,'1573468136','1573468136','1','100','','0'],
['dep/dep-auth','2','dep/dep-list','权限设置',null,null,'1574775304','1574775304','1','100','','0'],
['dep/dep-edit','2','dep/dep-list','添加部门',null,null,'1572076542','1572076542','1','100','','0'],
['dep/dep-list','2','Auth','部门管理',null,null,'1559146097','1582615732','1','100','layui-icon-group','1'],
['Editor','1',null,'报社编辑',null,null,'1545050120','1583993725','1','100','','0'],
['migration/default/index','2','common/config/allset','全栈数据迁移',null,null,'1581267020','1581267020','1','100','layui-icon-cellphone','1'],
['Network-section-chief','1',null,'网络科长 复审',null,null,'1545051722','1583993584','1','100','','0'],
['News-Department','1',null,'新闻中心投+审',null,null,'1545049842','1583993788','1','100','','0'],
['ordinary-staff','1',null,'员工',null,null,'1545049569','1583993831','1','100','','0'],
['Propaganda-minister','1',null,'基层宣传部长',null,null,'1545051582','1583993626','1','100','','0'],
['readAndEdit','1',null,'编审管理中心',null,null,'1542024767','1583993877','1','100','','0'],
['Resource','1',null,'资源稿库管理中心',null,null,'1542024820','1583993855','1','100','','0'],
['Team_70','4',null,'xwom团队',null,null,'1577000604','1577000604','1','100','','0'],
['Team_71','4',null,'媒体云团队',null,null,'1577000714','1577000714','1','100','','0'],
['team/add-team','2','team/team-list','添加团队',null,null,'1572076570','1583662320','1','100','','0'],
['team/batch-port','2','team/team-list','批量移除人员',null,null,'1573468247','1573468247','1','100','','0'],
['team/team-auth','2','team/team-list','权限分配',null,null,'1583662260','1583662260','1','100','layui-icon-left','0'],
['team/team-edit','2','team/team-list','添加子团队',null,null,'1583662473','1583662473','1','100','layui-icon-left','0'],
['team/team-list','2','Auth','团队管理',null,null,'1559146097','1582615758','1','100','layui-icon-group','1'],
['third-pary-interface/index','2','Auth','第三方接口',null,null,'1576720122','1581415399','1','100','layui-icon-upload-drag','1'],
['unit/add-unit','2','unit/unit-list','添加单位',null,null,'1583662579','1583667610','1','100','layui-icon-left','0'],
['unit/unit-edit','2','unit/unit-list','编辑单位',null,null,'1572076602','1583662598','1','100','','0'],
['unit/unit-list','2','Auth','单位管理',null,null,'1559146097','1582615807','1','100','layui-icon-home','1'],
['Website-editor','1',null,'网站编辑',null,null,'1545051445','1583993744','1','100','','0'],
['workflow/default/create','2','common/bpm/index','新增工作流',null,null,'1583804732','1583804732','1','100','layui-icon-left','1'],
['workflow/default/index','2','common/bpm/index','工作流列表',null,null,'1583804691','1583804691','1','100','layui-icon-left','1'],
['workflow/default/update','2','workflow/default/index','修改工作流',null,null,'1583805202','1583810184','1','100','layui-icon-left','0'],
['workflow/default/view','2','workflow/default/index','查看工作流',null,null,'1583805098','1583810197','1','100','layui-icon-left','0'],
['workflow/status/create','2','workflow/default/view','新增状态',null,null,'1583804876','1583804876','1','100','layui-icon-left','0'],
['workflow/status/delete','2','workflow/default/view','删除状态',null,null,'1583804942','1583804942','1','100','layui-icon-left','0'],
['workflow/status/update','2','workflow/default/view','修改状态',null,null,'1583804916','1583804916','1','100','layui-icon-left','0'],
['workflow/status/view','2','workflow/default/view','查看状态',null,null,'1583804981','1583804981','1','100','layui-icon-left','0'],
['XwomSystem','2',null,'Xwom全栈管理',null,null,'1559146097','1572069500','1','110','','1'],
]);
 
/* Table auth_item_child */
$this->batchInsert('{{%auth_item_child}}',['parent','child'],[['admin','App1System'],
['Chief-executive-officer','App1System'],
['Editor','App1System'],
['Network-section-chief','App1System'],
['News-Department','App1System'],
['ordinary-staff','App1System'],
['Propaganda-minister','App1System'],
['readAndEdit','App1System'],
['Resource','App1System'],
['Website-editor','App1System'],
['admin','Auth'],
['admin','auth/add-admin'],
['admin','auth/add-auth'],
['admin','auth/add-menu'],
['admin','auth/admin-list'],
['admin','auth/auth-list'],
['admin','auth/menu-list'],
['admin','auth/subsystem'],
['admin','auth/subsystem-edit'],
['admin','auth/subsystem-permission'],
['admin','common/app_register'],
['admin','common/bpm/index'],
['admin','common/config-api/create'],
['Network-section-chief','common/config-api/create'],
['admin','common/config-api/delete'],
['Network-section-chief','common/config-api/delete'],
['admin','common/config-api/index'],
['Network-section-chief','common/config-api/index'],
['admin','common/config-api/update'],
['Network-section-chief','common/config-api/update'],
['admin','common/config-api/view'],
['Network-section-chief','common/config-api/view'],
['admin','common/config-base/create'],
['Network-section-chief','common/config-base/create'],
['admin','common/config-base/delete'],
['Network-section-chief','common/config-base/delete'],
['admin','common/config-base/index'],
['Network-section-chief','common/config-base/index'],
['admin','common/config-base/update'],
['Network-section-chief','common/config-base/update'],
['admin','common/config-base/view'],
['Network-section-chief','common/config-base/view'],
['admin','common/config-behavior/create'],
['Network-section-chief','common/config-behavior/create'],
['admin','common/config-behavior/delet'],
['Network-section-chief','common/config-behavior/delet'],
['admin','common/config-behavior/index'],
['Network-section-chief','common/config-behavior/index'],
['admin','common/config-behavior/update'],
['Network-section-chief','common/config-behavior/update'],
['admin','common/config-behavior/view'],
['Network-section-chief','common/config-behavior/view'],
['admin','common/config-behaviorlog/index'],
['Network-section-chief','common/config-behaviorlog/index'],
['admin','common/config-constant/create'],
['Network-section-chief','common/config-constant/create'],
['admin','common/config-constant/delete'],
['Network-section-chief','common/config-constant/delete'],
['admin','common/config-constant/index'],
['Network-section-chief','common/config-constant/index'],
['admin','common/config-constant/update'],
['Network-section-chief','common/config-constant/update'],
['admin','common/config-constant/view'],
['Network-section-chief','common/config-constant/view'],
['admin','common/config-dbengine/create'],
['Network-section-chief','common/config-dbengine/create'],
['admin','common/config-dbengine/dalete'],
['Network-section-chief','common/config-dbengine/dalete'],
['admin','common/config-dbengine/index'],
['Network-section-chief','common/config-dbengine/index'],
['admin','common/config-dbengine/update'],
['Network-section-chief','common/config-dbengine/update'],
['admin','common/config-dbengine/view'],
['Network-section-chief','common/config-dbengine/view'],
['admin','common/config-email/create'],
['Network-section-chief','common/config-email/create'],
['admin','common/config-email/delete'],
['Network-section-chief','common/config-email/delete'],
['admin','common/config-email/index'],
['Network-section-chief','common/config-email/index'],
['admin','common/config-email/update'],
['Network-section-chief','common/config-email/update'],
['admin','common/config-email/view'],
['Network-section-chief','common/config-email/view'],
['admin','common/config-functionlog/index'],
['Network-section-chief','common/config-functionlog/index'],
['admin','common/config-ipmanage/index'],
['Network-section-chief','common/config-ipmanage/index'],
['admin','common/config-mutillang/create'],
['Network-section-chief','common/config-mutillang/create'],
['admin','common/config-mutillang/delete'],
['Network-section-chief','common/config-mutillang/delete'],
['admin','common/config-mutillang/index'],
['Network-section-chief','common/config-mutillang/index'],
['admin','common/config-mutillang/update'],
['Network-section-chief','common/config-mutillang/update'],
['admin','common/config-mutillang/view'],
['Network-section-chief','common/config-mutillang/view'],
['admin','common/config-searchengine/create'],
['Network-section-chief','common/config-searchengine/create'],
['admin','common/config-searchengine/dalete'],
['Network-section-chief','common/config-searchengine/dalete'],
['admin','common/config-searchengine/index'],
['Network-section-chief','common/config-searchengine/index'],
['admin','common/config-searchengine/update'],
['Network-section-chief','common/config-searchengine/update'],
['admin','common/config-searchengine/view'],
['Network-section-chief','common/config-searchengine/view'],
['admin','common/config-smslog/index'],
['Network-section-chief','common/config-smslog/index'],
['admin','common/config-sysinfo/create'],
['Network-section-chief','common/config-sysinfo/create'],
['admin','common/config-sysinfo/index'],
['Network-section-chief','common/config-sysinfo/index'],
['admin','common/config-sysinfo/update'],
['Network-section-chief','common/config-sysinfo/update'],
['admin','common/config-sysinfo/view'],
['Network-section-chief','common/config-sysinfo/view'],
['admin','common/config-variable/create'],
['Network-section-chief','common/config-variable/create'],
['admin','common/config-variable/delete'],
['Network-section-chief','common/config-variable/delete'],
['admin','common/config-variable/index'],
['Network-section-chief','common/config-variable/index'],
['admin','common/config-variable/update'],
['Network-section-chief','common/config-variable/update'],
['admin','common/config-variable/view'],
['Network-section-chief','common/config-variable/view'],
['admin','common/config/allset'],
['Network-section-chief','common/config/allset'],
['admin','common/reg-extension/create'],
['admin','common/reg-extension/delete'],
['admin','common/reg-extension/index'],
['admin','common/reg-extension/update'],
['admin','common/reg-extension/view'],
['admin','common/reg-module/create'],
['admin','common/reg-module/delete'],
['admin','common/reg-module/index'],
['admin','common/reg-module/update'],
['admin','common/reg-module/view'],
['admin','common/reg-software/create'],
['admin','common/reg-software/delete'],
['admin','common/reg-software/index'],
['admin','common/reg-software/update'],
['admin','common/reg-software/view'],
['admin','common/reg-widgets/crearte'],
['admin','common/reg-widgets/delete'],
['admin','common/reg-widgets/index'],
['admin','common/reg-widgets/update'],
['admin','common/reg-widgets/view'],
['admin','common/wom-plan/approve'],
['Chief-executive-officer','common/wom-plan/approve'],
['Editor','common/wom-plan/approve'],
['ordinary-staff','common/wom-plan/approve'],
['Propaganda-minister','common/wom-plan/approve'],
['Website-editor','common/wom-plan/approve'],
['admin','common/wom-plan/create'],
['Chief-executive-officer','common/wom-plan/create'],
['Editor','common/wom-plan/create'],
['ordinary-staff','common/wom-plan/create'],
['Propaganda-minister','common/wom-plan/create'],
['Website-editor','common/wom-plan/create'],
['admin','common/wom-plan/delete'],
['Chief-executive-officer','common/wom-plan/delete'],
['Editor','common/wom-plan/delete'],
['ordinary-staff','common/wom-plan/delete'],
['Propaganda-minister','common/wom-plan/delete'],
['Website-editor','common/wom-plan/delete'],
['admin','common/wom-plan/index'],
['Chief-executive-officer','common/wom-plan/index'],
['Editor','common/wom-plan/index'],
['ordinary-staff','common/wom-plan/index'],
['Propaganda-minister','common/wom-plan/index'],
['Website-editor','common/wom-plan/index'],
['admin','common/wom-plan/update'],
['Chief-executive-officer','common/wom-plan/update'],
['Editor','common/wom-plan/update'],
['ordinary-staff','common/wom-plan/update'],
['Propaganda-minister','common/wom-plan/update'],
['Website-editor','common/wom-plan/update'],
['admin','common/wom-plan/view'],
['Chief-executive-officer','common/wom-plan/view'],
['Editor','common/wom-plan/view'],
['ordinary-staff','common/wom-plan/view'],
['Propaganda-minister','common/wom-plan/view'],
['Website-editor','common/wom-plan/view'],
['admin','commonLog'],
['Network-section-chief','commonLog'],
['admin','creat'],
['admin','dep/add-dep'],
['admin','dep/batch-port'],
['admin','dep/dep-auth'],
['admin','dep/dep-edit'],
['admin','dep/dep-list'],
['admin','migration/default/index'],
['Network-section-chief','migration/default/index'],
['admin','team/add-team'],
['admin','team/batch-port'],
['admin','team/team-auth'],
['admin','team/team-edit'],
['admin','team/team-list'],
['admin','third-pary-interface/index'],
['admin','unit/add-unit'],
['admin','unit/unit-edit'],
['admin','unit/unit-list'],
['admin','workflow/default/create'],
['admin','workflow/default/index'],
['admin','workflow/default/update'],
['admin','workflow/default/view'],
['admin','workflow/status/create'],
['admin','workflow/status/delete'],
['admin','workflow/status/update'],
['admin','workflow/status/view'],
['admin','XwomSystem'],
['Chief-executive-officer','XwomSystem'],
['Editor','XwomSystem'],
['Network-section-chief','XwomSystem'],
['ordinary-staff','XwomSystem'],
['Propaganda-minister','XwomSystem'],
['Website-editor','XwomSystem'],
]);
 
/* Table auth_rule */
$this->batchInsert('{{%auth_rule}}',['name','data','created_at','updated_at'],[]);
 
/* Table config_api */
$this->batchInsert('{{%config_api}}',['id','name','name_en','domain','route','type','value','encryption_mode','encryption_algorithm','is_sign','description','status','created_at','updated_at','created_id','updated_id'],[]);
 
/* Table config_base */
$this->batchInsert('{{%config_base}}',['id','title','name','app_id','type','extra','description','is_hide_des','default_value','sort','status','created_at','updated_at','created_id','updated_id'],[['113','运行状态','sys_status','backend','text','1:运行, 0:运维,','系统级别的启动运行或运维操作','1','1','1','1','0','1583386973',null,'100007'],
['115','会员认证','islogin','backend','text','1:开启, 0:停止,','系统级别会员认证校验的启动运行或停止','1','1','1','1','0','1583386988',null,'100007'],
['116','开启会员权限','ismempower','backend','text','1:开启, 0:停止,','系统级别会员权限校验的启动运行或停止','1','1','1','1','1583385564','1583386956','100007','100007'],
['117','缓存','iscache','backend','text','1:开启, 0:停止,','系统级别缓存的启动运行或停止','1','1','1','1','1583387121','1583387121','100007','100007'],
['118','IP黑名单','sys_ip_blacklist','backend','radioList','1:开启, 0:停止,','需要去系统工具的ip黑名单增加才可生效','1','1','1','1','1583387290','1583387345','100007','100007'],
['119','多语言包','sys_mutillang','backend','text','1:开启, 0:停止,','需要去多语言包管理增加才可生效','1','1','1','1','1583388113','1583388113','100007','100007'],
['120','全栈搜搜引擎','sys_serch','backend','text','1:开启, 0:停止,','需要去系统工具搜索引擎增加才可生效','1','1','1','1','1583388225','1583388225','100007','100007'],
['121','数据库引擎','dbengine','backend','text','1:开启, 0:停止,','系统级别的启动运行或运维操作','1','1','1','1','1583388529','1583388529','100007','100007'],
['122','行为监控','action_behavior','backend','text','1:开启, 0:停止,','系统级别的启动运行或运维操作','1','1','1','1','1583388816','1583388816','100007','100007'],
['123','变量','variable','backend','text','1:开启, 0:停止,','需要去系统工具增加才可生效','1','1','1','1','1583388900','1583388950','100007','100007'],
['124','常量','constant','backend','text','1:开启, 0:停止,','需要在常量设置中添加才生效','1','1','1','1','1583389018','1583389018','100007','100007'],
['125','API','api','backend','text','1:开启, 0:停止,','需要到API中配置才生效','1','1','1','1','1583389092','1583389092','100007','100007'],
['126','日志记录','commonLog','backend','text','1:开启, 0:停止,','系统级别的启动运行或停止','1','1','1','1','1583389150','1583389150','100007','100007'],
]);
 
/* Table config_behavior */
$this->batchInsert('{{%config_behavior}}',['id','app_id','url','method','behavior','action','level','is_record_post','is_ajax','remark','addons_name','is_addon','status','created_at','updated_at','created_id','updated_id'],[]);
 
/* Table config_behaviorlog */
$this->batchInsert('{{%config_behaviorlog}}',['id','merchant_id','app_id','user_id','behavior','method','module','controller','action','url','get_data','post_data','header_data','ip','addons_name','remark','country','provinces','city','device','status','created_at','updated_at'],[]);
 
/* Table config_constant */
$this->batchInsert('{{%config_constant}}',['id','name','name_en','description','status','created_at','updated_at','created_id','updated_id'],[]);
 
/* Table config_dbengine */
$this->batchInsert('{{%config_dbengine}}',['id','name','name_en','description','status','created_at','updated_at','created_id','updated_id'],[]);
 
/* Table config_email */
$this->batchInsert('{{%config_email}}',['id','scene','email','smtp_host','smtp_account','smtp_password','smtp_port','encryp_mode','activation_type','token_time','status','email_widget','email_viewpatch','created_at','updated_at','created_id','updated_id'],[['1','注册账户发送邮件','jinostart@126.com','smtp.126.com','jinostart','123456','587','tls','1','86400','1','services\email\widgets\customer\account\register\Body','@comon/services/email/views/customer/account/register','1583553958','1583553958','100007','100007'],
['2','忘记密码发送邮件','jinostart@126.com','smtp.126.com','jinostart','123456','587','tls','1','86400','1','servicesemailwidgetscustomeraccountforgotpasswordBody','@comon/services/email/views/customer/forgotpassword/register','1583553958','1583553958','100007','100007'],
['3','登陆账户发送邮件','jinostart@126.com','smtp.126.com','jinostart','123456','587','tls','1','86400','1','servicesemailwidgetscustomeraccountloginBody','@comon/services/email/views/customer/account/login','1583553958','1583553958','100007','100007'],
]);
 
/* Table config_functionlog */
$this->batchInsert('{{%config_functionlog}}',['id','app_id','merchant_id','user_id','method','module','controller','action','url','get_data','post_data','header_data','ip','error_code','error_msg','error_data','req_id','user_agent','device','device_uuid','device_version','device_app_version','status','created_at','updated_at'],[]);
 
/* Table config_ipmanage */
$this->batchInsert('{{%config_ipmanage}}',['id','ip','status','start_time','end_time','created_at','updated_at','created_id','updated_id'],[]);
 
/* Table config_mutillang */
$this->batchInsert('{{%config_mutillang}}',['id','name','name_en','description','searchengine','status','created_at','updated_at','created_id','updated_id'],[['1','zh-CN','zh','此处为语言编辑部分，可以添加或者编辑语言，并为每个语言指定相应的搜索引擎','1','1','1583559079','1583559079','100007','100007'],
]);
 
/* Table config_searchengine */
$this->batchInsert('{{%config_searchengine}}',['id','name','name_en','description','status','created_at','updated_at','created_id','updated_id'],[['1','MysqlSearch','MysqlSearch','开启该选项后，您可以在入口的store中配置该搜索引擎，执行shell脚本`sh fullSearchSync.sh`，同步数据到搜索引擎','1','1583558937','1583558937','100007','100007'],
]);
 
/* Table config_smslog */
$this->batchInsert('{{%config_smslog}}',['id','merchant_id','member_id','mobile','code','content','error_code','error_msg','error_data','usage','used','use_time','ip','status','created_at','updated_at'],[]);
 
/* Table config_sysinfo */
$this->batchInsert('{{%config_sysinfo}}',['siteid','name','dirname','domain','serveralias','keywords','description','site_point','smarty_id','smarty_app_id','address','zipcode','tel','fax','email','copyright','logo','banner','reg_time','begin_time','end_time','basemail','mailpwd','record','created_at','updated_at','default_style','contacts','comp_invoice','comp_bank','bank_numb'],[['1','xwom','xwom','127.0.0.148','','xwom关键词','xwom描述','xwom','1','1','北京市海淀区西三旗上奥世纪中心','100096','01057117580','010-57117580','chareler@163.com','北京金启程科技有限公司','','','','','','','','',null,null,'','','','',''],
]);
 
/* Table config_variable */
$this->batchInsert('{{%config_variable}}',['id','name','name_en','description','status','created_at','updated_at','created_id','updated_id'],[]);
 
/* Table reg_extension */
$this->batchInsert('{{%reg_extension}}',['id','title','name','title_initial','bootstrap','service','cover','brief_introduction','description','author','version','is_setting','is_rule','is_merchant_route_map','default_config','console','status','created_at','updated_at','created_id','updated_id'],[]);
 
/* Table reg_module */
$this->batchInsert('{{%reg_module}}',['id','title','name','title_initial','bootstrap','service','cover','brief_introduction','description','author','version','is_setting','is_rule','is_merchant_route_map','default_config','console','status','created_at','updated_at','created_id','updated_id'],[]);
 
/* Table reg_software */
$this->batchInsert('{{%reg_software}}',['id','title','name','title_initial','bootstrap','service','cover','brief_introduction','description','author','version','is_setting','is_rule','parent_rule_name','route_map','default_config','console','status','created_at','updated_at','created_id','updated_id','sortOrder','is_del'],[['1','xp system系统','XpSystem','xp','xp','\'xpaper\' => [\'class\' => \'backend\modules\xpaper\Index\',],','','Xp系统简单介绍','Xp系统应用描述','charleslee','1.0',null,null,'','XwomSystem','','[]','1','1584430309','1585466707','100007','100007','1','0'],
['2','xe system系统','XeSystem','xe','xe',' \'xedit\' => [ \'class\' => \'backend\modules\xedit\xedit\',],','','xe system  简单介绍','xe system  应用描述','charleslee','1.0',null,null,null,'XeditSystem','','[]','1','1584502630','1584503188','100007','100007','2','0'],
['3','xpo system系统','xposystem','xpo','xpo','\'xportal\' => [\'class\' => \'backend\modules\xportal\Xportal\',],','','xpo system 简单介绍','xportal system 应用描述','charleslee','1.0',null,null,'','XportalSystem','','[]','0','1584502769','1584503202','100007','100007','3','0'],
]);
 
/* Table reg_widgets */
$this->batchInsert('{{%reg_widgets}}',['id','title','name','title_initial','bootstrap','service','cover','brief_introduction','description','author','version','is_setting','is_rule','is_merchant_route_map','default_config','console','status','created_at','updated_at','created_id','updated_id'],[]);
 
/* Table review_log */
$this->batchInsert('{{%review_log}}',['id','news_id','status','changed_status','content','user_id','created_at','updated_at','deleted_at'],[]);
 
/* Table sw_metadata */
$this->batchInsert('{{%sw_metadata}}',['workflow_id','status_id','key','value'],[['news','final-review','news/deprecated','弃用'],
['news','final-review','news/pending','驳回'],
['news','pending','news/submit','提交'],
['news','review','news/deprecated','弃用'],
['news','review','news/final-review','通过'],
['news','review','news/pending','驳回'],
['news','submit','news/deprecated','弃用'],
['news','submit','news/final-review','直接录用'],
['news','submit','news/pending','驳回'],
['news','submit','news/review','通过'],
['testtest','test2step2','testtest/bh','驳回'],
['testtest','test2step2','testtest/js','结束'],
['testtest','test2step2','testtest/tg','通过'],
['testtest','test3end','testtest/bh','驳回'],
['testtest','test3end','testtest/js','结束'],
['testtest','test3step3','testtest/bh','驳回'],
['testtest','test3step3','testtest/js','结束'],
['testtest','test3step3','testtest/tg','通过'],
]);
 
/* Table sw_status */
$this->batchInsert('{{%sw_status}}',['id','workflow_id','label','sort_order'],[['deprecated','news','已弃用','3'],
['draft','news','草稿','0'],
['final-review','news','终审通过','7'],
['pending','news','待修稿','2'],
['review','news','初审通过','4'],
['start','test','开始','1'],
['submit','news','已投稿','1'],
['test2step1','testtest','测试流程2第1步','0'],
['test2step2','testtest','测试流程2第二步','1'],
['test3end','testtest','测试流程2完成发布','3'],
['test3pub','testtest','发布','4'],
['test3step3','testtest','测试流程2第三步','2'],
]);
 
/* Table sw_transition */
$this->batchInsert('{{%sw_transition}}',['workflow_id','start_status_id','end_status_id'],[['news','draft','submit'],
['news','final-review','deprecated'],
['news','final-review','pending'],
['news','final-review','review'],
['news','pending','submit'],
['news','review','deprecated'],
['news','review','final-review'],
['news','review','pending'],
['news','submit','deprecated'],
['news','submit','final-review'],
['news','submit','pending'],
['news','submit','review'],
['testtest','test2step1','test2step2'],
['testtest','test2step2','test2step1'],
['testtest','test2step2','test3end'],
['testtest','test2step2','test3pub'],
['testtest','test2step2','test3step3'],
['testtest','test3end','test2step1'],
['testtest','test3end','test2step2'],
['testtest','test3end','test3pub'],
['testtest','test3end','test3step3'],
['testtest','test3step3','test2step1'],
['testtest','test3step3','test2step2'],
['testtest','test3step3','test3end'],
['testtest','test3step3','test3pub'],
]);
 
/* Table sw_workflow */
$this->batchInsert('{{%sw_workflow}}',['id','initial_status_id','initial_status_id_cn','name','name_cn'],[['news','draft','草稿','news','投稿流程'],
['test','start','开始','test_en','测试'],
['testtest','test3pub','发布','testtest','测试测试'],
]);
 
/* Table third_interface_key */
$this->batchInsert('{{%third_interface_key}}',['id','name','clientKey','clientSecret','callBackUrl','type','unitId','is_del','created_at','updated_at'],[]);
 
/* Table wom_plan */
$this->batchInsert('{{%wom_plan}}',['id','title','desc','status','time_status','admin_id','start_at','end_at','created_at','created_id','updated_at','updated_id'],[['4','test333test333','<p>
<strong>test333</strong><strong>test333</strong> 
</p>
<p>
<strong>&nbsp; &nbsp;test333test333</strong> 
</p>
<p>
1、test333
</p>
<p>
2、test333test333test333test333
</p>','1','2','100007','1580486400','1584374400','1582942387','100007','1583121080','100007'],
['5','test222','<p>
test222test222test222
</p>
<p>
<br />
</p>
<p>
test222test222test222
</p>','1','2','100007','1582992000','1585584000','1583129318','100007','1583129318','100007'],
['6','test44444','<p>
test44444test44444test44444
</p>
<p>
<br />
</p>
<p>
test44444test44444test44444test44444
</p>','1','2','100007','1583251200','1585584000','1583129539','100007','1583129539','100007'],
]);
 
/* Table wom_plan_status */
$this->batchInsert('{{%wom_plan_status}}',['id','name','position'],[['1','待处理','0'],
['2','已委派 ','1'],
['3','完成 ','2'],
['4','延期','3'],
]);
 
/* Table wom_plantime_status */
$this->batchInsert('{{%wom_plantime_status}}',['id','name','position'],[['1','延缓 ','0'],
['2','正常','1'],
['3','紧急','2'],
]);
 
$this->execute('SET foreign_key_checks = 1;');    }

    public function down()
    {
    
    	        $this->execute('SET foreign_key_checks = 0');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->dropTable('{{%wom_plantime_status}}');
$this->execute('SET foreign_key_checks = 1;');		    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
