<?php
/*
Plugin Name: Vuln Auth Bypass Demo
Description: Demonstration of an authentication bypass / logic flaw (LAB ONLY). 
Version: 1.0
Author: Srii
*/

defined('ABSPATH') or die('No script kiddies please.');

/*
 * WARNING (lab only):
 * This plugin intentionally contains insecure logic for teaching purposes.
 * DO NOT run this on production or public-facing servers.
 *
 * Intended behavior for challenge:
 *  - Visiting ?role=admin simulates an attacker-controlled parameter that the app trusts.
 *  - Visiting ?role=admin&showflag=1 will display the flag (if the flag file exists).
 *
 * Mitigation taught: use server-side capability checks such as current_user_can('manage_options')
 * and never trust client-supplied role or authentication parameters.
 */

/**
 * Frontend handler: trust a ?role=admin parameter (intentionally unsafe).
 * Example: https://example.local/?role=admin&showflag=1
 */
add_action('init', function() {
    if (isset($_GET['role']) && $_GET['role'] === 'admin') {
        // Simulated "admin view" reachable if the app incorrectly trusts the role param.
        if (isset($_GET['showflag']) && $_GET['showflag'] === '1') {
            $flag_file = ABSPATH . 'flags/auth-flag.txt';
            header('Content-Type: text/plain');
            if (file_exists($flag_file)) {
                echo file_get_contents($flag_file);
            } else {
                echo "Flag not found. Ask admin to run the seeder in WP Admin.";
            }
            exit;
        }

        // Basic informational page
        echo '<!doctype html><html><head><meta charset="utf-8"><title>Admin View (simulated)</title></head><body>';
        echo '<h1>Simulated Admin View</h1>';
        echo '<p>This site incorrectly trusts <code>?role=admin</code>. Use <code>?showflag=1</code> to view the flag.</p>';
        echo '</body></html>';
        exit;
    }
});

/**
 * Admin menu: seed the flag and sample message files.
 * Only accessible to actual admins (so instructors can create flags safely).
 */
add_action('admin_menu', function() {
    add_menu_page('Auth Bypass Seed', 'Auth Bypass Seed', 'manage_options', 'auth-bypass-seed', 'vuln_auth_seed_page');
});

function vuln_auth_seed_page() {
    // Ensure flags directory exists
    $flags_dir = ABSPATH . 'flags';
    if (!is_dir($flags_dir)) {
        wp_mkdir_p($flags_dir);
    }

    // If CTF_FLAG is present as an environment-level convenience, use it for seeding
    // (Useful when running in Docker/CTFd; set CTF_FLAG before starting container)
    $env_flag = getenv('CTF_FLAG');
    $flag_value = $env_flag ? $env_flag : 'FLAG{auth-example-REPLACE}';

    file_put_contents($flags_dir . '/auth-flag.txt', $flag_value);

    // Provide simple feedback in admin UI
    echo '<div class="wrap"><h1>Auth Bypass Seed</h1>';
    echo '<p>Flag written to: <code>flags/auth-flag.txt</code></p>';
    if ($env_flag) {
        echo '<p><strong>CTF_FLAG was used:</strong> ' . esc_html($env_flag) . '</p>';
    } else {
        echo '<p><strong>Default flag used:</strong> ' . esc_html($flag_value) . '</p>';
    }
    echo '<p>Files created: <code>uploads/private/1.txt</code> (example)</p>';
    echo '</div>';
}
