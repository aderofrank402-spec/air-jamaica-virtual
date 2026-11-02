# Operations and deployment

This document describes the operational responsibilities visible in the repository. It is not a claim that every optional integration is enabled in the live environment.

## Deployment inputs

- PHP runtime and required extensions
- Composer dependencies installed from the lockfile
- Production assets compiled from the npm lockfile
- Environment-specific application key, database, cache, queue, mail, storage, and integration settings
- Writable framework cache and storage directories
- Database migrations applied under an explicit deployment step

## Long-running work

Queue workers are required for asynchronous tasks. The scheduler or cron runner is required for recurring cleanup, recalculation, synchronization, and backup work. Both need process supervision and restart policies in production.

## Backups

Backup configuration and administration surfaces are present in the application. A complete production practice also requires:

1. Off-host or separate-account storage.
2. Retention and encryption appropriate to the data.
3. Alerting when a scheduled backup fails.
4. Periodic restore tests—not only successful backup-job logs.
5. Restricted access to files that may contain pilot or operational data.

## Deployment sequence

```text
fetch reviewed release
  → install locked PHP dependencies
  → install locked frontend dependencies
  → build production assets
  → place application in maintenance mode when required
  → apply database migrations
  → refresh caches
  → restart queue workers
  → run health and route checks
  → return traffic
```

## Observability

The application includes structured application logs, activity records, diagnostic tooling, and queue-aware operations. Production alerting should cover HTTP availability, database connectivity, queue delay, failed jobs, scheduler freshness, mail failures, storage failures, and backup age.

## Recovery boundaries

- Source control restores code, not production data.
- Database backups restore relational state, not necessarily uploaded files.
- Object-storage or filesystem backups must match database recovery points.
- Credentials must be restored through the deployment secret store, not from the repository.
- A successful local build does not prove production connectivity to external providers.
