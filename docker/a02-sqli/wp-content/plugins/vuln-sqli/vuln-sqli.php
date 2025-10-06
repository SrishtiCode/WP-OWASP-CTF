<?php
/*
Plugin Name: Vuln SQLi Demo
Description: SQLi demo — intentionally uses unprepared queries (lab only).
*/
defined('ABSPATH') or die('No script kiddies please.');


add_action('admin_menu', function(){
add_menu_page('SQLi Demo', 'SQLi Demo', 'read', 'vuln-sqli', 'vuln_sqli_page');
});


function vuln_sqli_page(){
global $wpdb;
echo '<h1>Search users</h1>';
$user = isset($_GET['user']) ? $_GET['user'] : '';
if($user){
// VULNERABLE: concatenated query without prepare
$row = $wpdb->get_row("SELECT ID, user_login, user_email FROM {$wpdb->users} WHERE user_login = '$user'");
if($row){
echo 'User: ' . esc_html($row->user_login) . '<br>Email: ' . esc_html($row->user_email);
} else {
echo 'No user found.';
}
}
echo '<form method="get"><input name="user" placeholder="username"/><button>Search</button></form>';


// place a flag as an option for demonstration
add_option('vuln_sqli_flag', 'FLAG{sqli-example-REPLACE}');
}
