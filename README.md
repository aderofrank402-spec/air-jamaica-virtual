# Air Jamaica Virtual

Production virtual-airline operations platform built and maintained by Frank Adero.

[Live application](https://airjamaicavirtual.com) · Laravel · PHP · MySQL · Blade · Vue.js · Vite

## What it does

Air Jamaica Virtual gives virtual pilots and operations staff one system for pilot accounts, fleet and route management, flight dispatch, flight reports, live operational views, training, notifications, and administration.

## Engineering work

- Rebuilt and extended the application on a modern Laravel stack.
- Developed the public experience and authenticated pilot workflows.
- Implemented role-aware operational and administrative features.
- Integrated external aviation and identity services.
- Added automated tests, deployment tooling, backups, and production diagnostics.
- Maintained the application in production at [airjamaicavirtual.com](https://airjamaicavirtual.com).

## Stack

- **Backend:** PHP 8.2+, Laravel 12, REST endpoints, queues
- **Frontend:** Blade, Vue.js, JavaScript, Vite
- **Data:** MySQL/MariaDB, Redis-compatible caching
- **Operations:** Docker/Sail, test automation, SSH deployment, automated backups

## Local setup

```bash
cp .env.example .env
composer install
php artisan key:generate
npm install
npm run build
php artisan migrate
php artisan serve
```

Configure only development credentials in `.env`. Never reuse production credentials locally.

## Security and data

This public repository intentionally excludes environment files, credentials, production data, user uploads, logs, backups, and generated dependencies. Report security issues privately rather than opening a public issue.

## Provenance and licence

Air Jamaica Virtual contains substantial organization-specific design and engineering work and also builds on third-party open-source components. Required upstream copyright and BSD-3-Clause redistribution terms are retained in [`LICENSE`](LICENSE). Third-party names are not endorsements of this deployment.
