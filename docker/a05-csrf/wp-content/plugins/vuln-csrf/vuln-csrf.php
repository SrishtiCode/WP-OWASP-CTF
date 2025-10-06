<?php
/*
Plugin Name: Vuln CSRF Demo
Description: Demonstration of a CSRF (Cross-Site Request Forgery) vulnerability. (LAB ONLY)
Version: 1.0
Author: Srii
*/

defined('ABSPATH') or die('No script kiddies please.');

/*
 * WARNING (lab only):
 * This plugin intentionally lacks nonce/capability checks on a state-changing endpoint
 * so you can demonstrate CSRF exploitation. Do not use in production.
 *
 * Challenge idea:
 *  - The POST endpoint at /wp-admin/admin-post.php?action=make_csrf_flag will create the flag file
 *    when called with POST parameter make_flag=1, without any nonce / auth verification.
 *
 * Mitigation taught: Always use nonces (check_admin_referer) and proper capability checks.
 */

/**
 * Register handler for admin-post (both logged-in and non-logged-in variants)
 * Endpoint: POST /wp-admin/admin-post.php?action=make_csrf_flag
 */
add_action('admin_post_nopriv_make_csrf_flag', 'vuln_csrf_handle');
add_action('admin_post_make_csrf_flag', 'vuln_csrf_handle');

function vuln_csrf_handle() {
    // Intentionally no nonce or capability checks
    if (isset($_POST['make_flag']) && $_POST['make_flag'] === '1') {
        $flags_dir = ABSPATH . 'flags';
        if (!is_dir($flags_dir)) {
            wp_mkdir_p($flags_dir);
        }

        // Prefer CTF_FLAG env var if present (useful for CTFd/container flows)
        $env_flag = getenv('CTF_FLAG');
        $flag_value = $env_flag ? $env_flag : 'FLAG{csrf-example-REPLACE}';

        file_put_contents($flags_dir . '/csrf-flag.txt', $flag_value);

        // Redirect back to admin or homepage for UX
        if (is_user_logged_in() && current_user_can('manage_options')) {
            wp_redirect(admin_url('admin.php?page=csrf-demo'));
        } else {
            // For non-authenticated POST (typical CSRF case), redirect to home
            wp_redirect(home_url());
        }
        exit;
    }

    // If payload not present, show nothing
    wp_die('Invalid request');
}

/**
 * Add an admin menu so instructors can seed/inspect the flag from the dashboard
 */
add_action('admin_menu', function() {
    add_menu_page('CSRF Demo', 'CSRF Demo', 'manage_options', 'csrf-demo', 'vuln_csrf_admin_page');
});

function vuln_csrf_admin_page() {
    $flags_dir = ABSPATH . 'flags';
    $flag_path = $flags_dir . '/csrf-flag.txt';
    $exists = file_exists($flag_path);
    $env_flag = getenv('CTF_FLAG');

    echo '<div class="wrap"><h1>CSRF Demo</h1>';
    echo '<p>This demo creates a flag when a POST is sent to <code>/wp-admin/admin-post.php?action=make_csrf_flag</code> with <code>make_flag=1</code>.</p>';

    if ($exists) {
        $val = esc_html(file_get_contents($flag_path));
        echo '<p><strong>Current flag:</strong> <code>' . $val . '</code></p>';
    } else {
        echo '<p><strong>No flag found yet.</strong> Use the form below (or a CSRF page) to create one.</p>';
    }

    // Provide an easy admin-only form to create the flag (for instructors)
    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
    echo '<input type="hidden" name="action" value="make_csrf_flag" />';
    echo '<input type="hidden" name="make_flag" value="1" />';
    submit_button('Create Flag (Admin Only)');
    echo '</form>';

    if ($env_flag) {
        echo '<p><em>Note: CTF_FLAG env var detected and will be used when creating the flag: ' . esc_html($env_flag) . '</em></p>';
    }

    echo '</div>';
}
