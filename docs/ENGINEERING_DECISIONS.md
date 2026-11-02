# Engineering decisions

## ADR-001 · Model the airline as connected domains

**Decision:** represent pilots, schedules, airports, fleet, reports, finance, training, and content as related domain records rather than independent pages.

**Reason:** a flight changes several operational views. Connected records let authorization, availability, statistics, and reporting use the same source of truth.

**Trade-off:** the data model is broad and migrations require care. The benefit is consistent operational behavior across pilot and staff workflows.

## ADR-002 · Keep authorization on the server

**Decision:** use roles, permissions, policies, middleware, and resource-level controls for administrative actions.

**Reason:** navigation visibility is a user-experience concern, not a security boundary. Every protected mutation must be authorized by the backend.

**Trade-off:** permission changes touch several layers, but the resulting controls remain enforceable for both browser and API requests.

## ADR-003 · Use events for flight-state consequences

**Decision:** publish domain events when reports, pilots, awards, expenses, fares, or operational states change.

**Reason:** acceptance of a flight report may affect statistics, finance, awards, notifications, and activity records. Events keep those consequences from becoming one oversized controller operation.

**Trade-off:** event-driven behavior requires traceable listeners and tests. It reduces direct coupling and makes additional consequences easier to add.

## ADR-004 · Isolate external aviation services

**Decision:** keep weather, planning, mapping, identity, mail, and storage behavior behind dedicated services or adapters.

**Reason:** provider failures and credentials should not leak into core domain logic. Tests can replace network behavior with fixtures or mocks.

**Trade-off:** adapters add code, but they create a replaceable and observable integration boundary.

## ADR-005 · Build assets deterministically

**Decision:** lock Composer and npm dependencies and compile production assets with Vite.

**Reason:** a reproducible dependency graph and build command are essential for deployment and incident recovery.

**Trade-off:** the current Sass stack emits deprecation warnings that should be addressed in a future styling migration; the warnings do not block the verified build today.

## ADR-006 · Keep production evidence outside source code

**Decision:** publish screenshots and summarized operational totals only after manual review and redaction.

**Reason:** live dashboards can contain pilot identities, emails, identifiers, financial data, or operational details. Evidence should prove the product without exposing private records.

**Trade-off:** evidence updates are a deliberate publication step rather than an automatic database export.
