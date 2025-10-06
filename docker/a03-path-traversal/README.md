## Path Traversal

### Objective
Read arbitrary files on the server (within permitted/intentional scope) using directory traversal to locate the flag.

### Exploit surface (URL / form)
Endpoint: /?read=<relative_path>
Example: http://<host>/index.php?read=flags/path-flag.txt or use traversal: ?read=../../flags/path-flag.txt

### Flag path (where it’s written)
/var/www/html/flags/path-flag.txt (the challenge places the flag here — intended to be read via traversal)

### Hints (3-tier)

Hint 1 (nudge): The read parameter is used to open files — try printable file names.
Hint 2 (clear): Try ../ sequences to move out of webroot directories (e.g., ?read=../flags/path-flag.txt).
Hint 3 (explicit): Request ?read=../../flags/path-flag.txt (adjust traversal depth if needed) to reach /var/www/html/flags/path-flag.txt.

### Mitigation

Normalize and validate requested paths; restrict reads to a specific base directory.
Use realpath() and confirm the resolved path starts with the allowed base path.
Disallow user-supplied file names with ../ sequences or reject unsafe characters; use whitelists.
