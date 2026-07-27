# EVENT REGISTRY

Version: 1.0

---

# Purpose

The Event Registry is the authoritative catalog of all domain events used by Portal Prediksi CMS.

Every published event must be registered before implementation.

No undocumented event may be introduced into the repository.

---

# Objectives

The Event Registry ensures:

- Explicit event ownership.
- Consistent event naming.
- Loose coupling between domains.
- Traceable event consumers.
- Stable event evolution.
- Backward compatibility.

---

# Event Naming Convention

Event names should:

- Represent completed business facts.
- Use PascalCase.
- Be expressed in the past tense.

Examples:

- PredictionPublished
- PredictionUpdated
- ResultPublished
- MarketOpened
- MarketClosed
- PromotionActivated

Avoid technical names such as:

- SavePrediction
- UpdateDatabase
- ExecuteJob

---

# Registry Structure

Every event must define:

- Event Name
- Publisher
- Consumers
- Trigger
- Payload
- Version
- Status
- Documentation

---

# Event Registry

| Event | Publisher | Consumers | Status | Version |
|--------|-----------|-----------|---------|---------|
| PredictionPublished | Prediction | Public Website, Notifications | Planned | v1 |
| PredictionUpdated | Prediction | Public Website | Planned | v1 |
| ResultPublished | Result | Live Draw, Public Website | Planned | v1 |
| ResultCorrected | Result | Live Draw | Planned | v1 |
| MarketOpened | Market | Prediction | Planned | v1 |
| MarketClosed | Market | Prediction | Planned | v1 |
| PromotionActivated | Promotion | Public Website | Planned | v1 |
| PromotionExpired | Promotion | Public Website | Planned | v1 |

---

# Publisher Rules

Each event has exactly one publisher.

Only the owning domain may publish the event.

Ownership cannot be shared.

---

# Consumer Rules

Consumers may:

- Read event payloads.
- Execute independent workflows.
- Trigger asynchronous processing.

Consumers must never modify the publisher's internal state.

---

# Payload Guidelines

Payloads should contain only business data.

Payloads should never expose:

- Database implementation
- Framework objects
- Infrastructure services
- ORM models
- Internal repository structures

Payloads should remain stable whenever practical.

---

# Versioning

Events evolve through versioning.

Changes requiring a new version include:

- Payload changes
- Semantic meaning changes
- Contract changes

Minor documentation updates do not require a new version.

---

# Event Lifecycle

Draft

↓

Reviewed

↓

Approved

↓

Published

↓

Consumed

↓

Deprecated

↓

Removed

Deprecated events should remain available until all consumers migrate.

---

# Validation Checklist

Every event should define:

- Publisher
- Consumers
- Trigger
- Payload
- Version
- Documentation
- Test Coverage

---

# Governance

The Event Registry must remain synchronized with:

- Domain Registry
- Module Registry
- Domain Map
- Master Architecture
- Implementation Strategy

No implementation may publish undocumented events.
