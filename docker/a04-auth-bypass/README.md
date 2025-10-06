## Authentication / Logic Bypass

### Objective
Bypass application logic that trusts client-supplied role/flags and access admin-only content to retrieve the flag.

### Exploit surface (URL / form)
Frontend parameter: ?role=admin&showflag=1
Example: http://<host>/index.php?role=admin&showflag=1
(The plugin incorrectly trusts role=admin to simulate admin context.)

### Flag path (where it’s written)
/var/www/html/flags/auth-flag.txt (seeded via admin menu or CTF_FLAG)

### Hints (3-tier)

Hint 1 (nudge): Look for URL parameters that affect access or role behavior.
Hint 2 (clear): The site exposes a role parameter — try setting role=admin and see what happens.
Hint 3 (explicit): Visit ?role=admin&showflag=1 to display the admin-only flag page (this simulates trusting client-provided roles).

### Mitigation

Never trust client-controlled parameters for authentication or role elevation.
Use server-side capability checks like current_user_can('manage_options') and proper session-based auth.
Enforce least privilege and avoid exposing admin-only endpoints without authentication.
