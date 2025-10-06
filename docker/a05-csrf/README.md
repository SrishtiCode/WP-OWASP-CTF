## Cross-Site Request Forgery (CSRF)

### Objective
Trigger a state-changing action (create the flag) by tricking an authenticated admin to perform a POST request without a valid CSRF token.

### Exploit surface (URL / form)
CSRF endpoint: POST /wp-admin/admin-post.php?action=make_csrf_flag with form field make_flag=1
Attack model: craft an external HTML page that auto-submits a form or uses JS fetch() to POST to the endpoint while the victim is authenticated.

### Flag path (where it’s written)
/var/www/html/flags/csrf-flag.txt (created when the vulnerable POST endpoint is invoked)

### Hints (3-tier)

Hint 1 (nudge): Look for admin-post or form endpoints that change server state.
Hint 2 (clear): The endpoint requires a POST with make_flag=1 — try submitting a POST to /wp-admin/admin-post.php?action=make_csrf_flag.
Hint 3 (explicit): Host a simple HTML page with a form that posts to /wp-admin/admin-post.php?action=make_csrf_flag and auto-submits (or use fetch() from the victim’s browser) to create the flag.

### Mitigation

Use WordPress nonces (wp_nonce_field() and check_admin_referer()) for state-changing actions.
Verify user capabilities (current_user_can()) before performing sensitive operations.
Implement same-site cookies and recommend strict CSRF protections for admin APIs.
