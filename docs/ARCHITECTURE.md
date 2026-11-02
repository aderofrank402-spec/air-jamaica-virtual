# Architecture

Air Jamaica Virtual is organized around the lifecycle of a flight and the operational records surrounding it. The application uses Laravel's HTTP, domain, event, queue, and persistence layers rather than placing business rules directly in templates.

## Request path

```text
HTTP request
  → route and middleware
  → request validation and authorization
  → controller
  → domain service or repository
  → Eloquent models and transaction
  → event, notification, or queued follow-up
  → Blade response or JSON resource
```

## Major domains

### Identity and pilots

Pilot accounts connect authentication, profile data, ranks, roles, permissions, awards, activity, notifications, and identity-provider links. Administrative resources provide reviewed roster and account-management workflows.

### Schedules and dispatch

Airlines, airports, routes, fares, subfleets, and aircraft form the schedule. Search and bidding add pilot intent; dispatch and flight-planning services turn a scheduled flight into an operational brief.

### Flight execution and reporting

Live-flight and tracking inputs feed operational maps and status views. Completed flights become structured pilot reports. Report state changes trigger downstream finance, awards, statistics, and notification behavior through events and listeners.

### Fleet and availability

Aircraft records belong to types and subfleets, maintain operational location and status, and participate in availability checks. Documentation and maintenance-oriented administration support staff decisions.

### Administration and governance

The Filament administration layer exposes role-aware resources for pilots, reports, schedules, fleet, finance, content, training, activity, backups, and settings. Permissions remain server-enforced; hiding navigation is not treated as authorization.

## Data and consistency

- Eloquent models represent the operational domain.
- Service and repository classes concentrate multi-record behavior.
- Database transactions protect changes that must succeed together.
- Events and listeners decouple downstream statistics, awards, finance, and notifications.
- Queues move slow or external work out of request-response paths.
- Scheduled commands handle recurring cleanup, recalculation, backups, and synchronization.

## External boundaries

External services are reached through dedicated clients or framework adapters. The application includes boundaries for aviation weather, flight planning, geographic data, OAuth identity, email delivery, object storage, and flight-tracking clients. Credentials belong in the environment and are never committed.

## Frontend composition

Blade templates provide server-rendered pages. JavaScript modules power maps, requests, storage, bidding, and interactive operational views. Vite compiles JavaScript, Sass, fonts, icons, and theme assets for production.

## Operational posture

The repository includes configuration for queues, caching, backups, scheduled work, logs, and multiple deployment environments. Production reliability still depends on environment-specific infrastructure: database backups, queue workers, cron or scheduler supervision, TLS, mail delivery, storage permissions, and alerting must be operated outside the source tree.
