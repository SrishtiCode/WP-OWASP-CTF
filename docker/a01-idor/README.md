## IDOR (Insecure Direct Object Reference)

### Objective
Access another user's private resource (file) by manipulating an identifier and retrieve the flag.

Exploit surface (URL / form)
Endpoint: /?idor_file=<id>
Example: http://<host>/index.php?idor_file=1
(The plugin serves files from wp-content/uploads/private/<id>.txt without checking ownership.)

### Flag path (where it’s written)
/var/www/html/flags/idor-flag.txt
(Seeded by admin via WP Admin → IDOR Seed or injected via CTF_FLAG at container start.)

### Hints (3-tier)

Hint 1 (nudge): Try changing the numeric idor_file parameter in the URL.
Hint 2 (clear): The endpoint reads files from wp-content/uploads/private/ using the id value — guess other IDs.
Hint 3 (explicit): Request /index.php?idor_file=2 (or iterate IDs 1..10) to find a file containing the flag.

### Mitigation

Authorize access on the server side: verify the current user's ownership of the requested resource before returning file contents.
Use unguessable file identifiers (random UUIDs) or map requests to permitted user IDs only.
Never expose files by direct user-supplied filenames/IDs without an ownership/capability check.
