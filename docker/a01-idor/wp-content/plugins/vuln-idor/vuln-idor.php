<?php
/*
Plugin Name: Vuln IDOR Demo
Description: Simple IDOR demo (lab only).
*/


defined('ABSPATH') or die('No script kiddies please.');


add_action('init', function(){
// simple endpoint: /?idor_file=ID
if(isset($_GET['idor_file'])){
$id = intval($_GET['idor_file']);
$path = ABSPATH . 'uploads/private/' . $id . '.txt';
if(file_exists($path)){
header('Content-Type: text/plain');
echo file_get_contents($path);
exit;
} else {
echo 'Not found.';
exit;
}
}
});


// Create admin menu to seed two sample private files
add_action('admin_menu', function(){
add_menu_page('IDOR Seed', 'IDOR Seed', 'manage_options', 'idor-seed', 'vuln_idor_seed_page');
});


function vuln_idor_seed_page(){
$dir = ABSPATH . 'uploads/private';
if(!is_dir($dir)) mkdir($dir, 0755, true);
file_put_contents($dir . '/1.txt', 'This is user1 private file.');
file_put_contents($dir . '/2.txt', 'This is user2 private file.');
// flag file
file_put_contents(ABSPATH . 'flags/idor-flag.txt', 'FLAG{idor-example-REPLACE}');
echo '<div class="wrap"><h1>IDOR seed created</h1><p>Files created: 1.txt, 2.txt</p></div>';
}
