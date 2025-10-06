## SQL Injection

### Objective
Exploit an unprepared SQL query to read sensitive data from the database (the flag).

### Exploit surface (URL / form)
Admin page: WP Admin → SQLi Demo or direct GET: /wp-admin/admin.php?page=vuln-sqli&user=<username>
Example injection point: ?user=admin' OR '1'='1

### Flag path (where it’s written)
Flag stored as an option or DB row, e.g. /var/www/html/flags/sqli-flag.txt or in wp_options. The SQLi challenge returns DB rows via the vulnerable query.

### Hints (3-tier)

Hint 1 (nudge): Try special characters in the user parameter (e.g., a single quote ').
Hint 2 (clear): The plugin builds the SQL query by concatenating input; try payloads like: admin' OR '1'='1-- to bypass filters.
Hint 3 (explicit): Use ?user=' OR '1'='1' -- (URL-encoded) to return results that include the flag row or to extract the option_value containing the flag.

### Mitigation

Use parameterized queries / $wpdb->prepare() for all DB access.
Validate and sanitize input, use least privilege DB accounts, and avoid echoing raw DB output.
Implement proper error handling and hide detailed DB errors from users.
