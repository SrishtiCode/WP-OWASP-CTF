# WP-OWASP-CTF
Dockerized WordPress CTF lab covering 5 OWASP-style challenges (IDOR, SQLi, Path Traversal, Authentication Bypass, CSRF). Designed for CTFd integration (per-instance flags via CTF_FLAG) and safe local testing.

### Purpose
Educational CTF platform for learning common web vulnerabilities in WordPress environments. Each challenge is provided as a small WordPress plugin or intentionally-misconfigured endpoint and packaged to run in Docker. Intended for isolated labs only.

## What you'll find in this repo
```
wordpress-owasp10-ctf/
├─ README.md # (this file)
├─ SECURITY.md
├─ infrastructure/
│ └─ docker-compose.yml 
├─ docker/ 
│ ├─ a01-idor/
│ │ ├─ wp-content/plugins/vuln-idor/vuln-idor.php
│ ├─ a02-sqli/
│ │ └─ wp-content/plugins/vuln-sqli/vuln-sqli.php
│ ├─ a03-path-traversal/
│ │ └─ wp-content/plugins/vuln-path/vuln-path.php
│ ├─ a04-auth-bypass/
│ │ └─ wp-content/plugins/vuln-auth/vuln-auth.php
│ └─ a05-csrf/
│ │ └─ wp-content/plugins/vuln-csrf/vuln-csrf.php
├─ wp-content/ 
├─ flags/ 
├─ scripts/
│ ├─ generate_flag.sh
│ └─ reset.sh
```

These commands are for local testing/development only. Do not expose to the public internet.

1. Clone the repo
   `git clone https://github.com/yourusername/wordpress-owasp10-ctf.git`
   `cd wordpress-owasp10-ctf`

2. Copy the `infra/docker-compose.yml` to your working directory and bring up the stack:
   #from repo root
   `docker compose -f infra/docker-compose.yml up -d`

3. Visit http://localhost:8000 and run the WordPress installer to create an admin account.

4. Activate the vulnerable plugins (WP Admin → Plugins). Create pages if a plugin requires a shortcode (see challenge README in docker/*/*).
5. Play the challenges. When you want a fresh start, run: `./scripts/reset.sh`
   
