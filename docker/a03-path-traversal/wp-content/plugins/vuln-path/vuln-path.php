<?php
/*
Plugin Name: Vuln Path Traversal Demo
*/
defined('ABSPATH') or die('No script kiddies please.');


add_action('init', function(){
if(isset($_GET['read'])){
$file = $_GET['read'];
// VULNERABLE: no normalization or base path restriction
$content = @file_get_contents(ABSPATH . $file);
if($content === false) echo 'Error reading file.';
else { header('Content-Type: text/plain'); echo $content; }
exit;
}
});


add_action('admin_menu', function(){
add_menu_page('Path Flag', 'Path Flag', 'manage_options', 'path-flag', 'path_flag_page');
});


function path_flag_page(){
$flag_path = ABSPATH . 'flags/path-flag.txt';
if(!file_exists($flag_path)) file_put_contents($flag_path, 'FLAG{path-example-REPLACE}');
echo '<div class="wrap"><h1>Path Flag</h1><p>Flag created.</p></div>';
}
