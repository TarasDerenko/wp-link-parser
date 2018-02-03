<?php
/*
* Plugin Name: Links parser
* Description: Links parser
* Version: 1.0.1
* Author: tarasid23@gmail.com
*/
require_once __DIR__."/system/parser-init.php";

register_activation_hook(__FILE__, 'parser_set_options');
register_deactivation_hook(__FILE__, 'parser_unset_options');