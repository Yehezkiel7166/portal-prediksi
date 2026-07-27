# Project Brain

Status: Canonical
Version: 1.0
Effective date: 2026-07-24

Project Brain is the permanent knowledge system for Portal Prediksi CMS. It records the product vision, engineering model, approved ideas, architecture direction, delivery priorities, operating model, security posture, and long-term roadmap.

## Authority and precedence

When documents disagree, use this order:

1. Approved ADRs.
2. `PROJECT_CONSTITUTION.md`.
3. `docs/project-brain/PROJECT_DECISIONS.md`.
4. `docs/project-brain/MASTER_VISION.md`.
5. `docs/project-brain/FEATURE_CATALOG.md`.
6. `docs/delivery/BRAND-1-30-DAY-PLAN.md`.
7. Existing specialized architecture, product, security, testing, and operations documents.
8. Historical sprint records.

Code is evidence of current implementation, but it does not silently override approved architecture. Conflicts must be documented and resolved.

## Canonical documents

- [Master Vision](MASTER_VISION.md)
- [Working Model](WORKING_MODEL.md)
- [Project Decisions](PROJECT_DECISIONS.md)
- [System Blueprint](SYSTEM_BLUEPRINT.md)
- [Feature Catalog](FEATURE_CATALOG.md)
- [Idea and Future Backlog](IDEA_BACKLOG.md)
- [Knowledge Maintenance](KNOWLEDGE_MAINTENANCE.md)
- [Brand 1 30-Day Plan](../delivery/BRAND-1-30-DAY-PLAN.md)
- [Brand 1 Production Gate](../delivery/BRAND-1-PRODUCTION-GATE.md)
- [Security Threat Model](../security/THREAT_MODEL.md)
- [Security Control Matrix](../security/SECURITY_CONTROL_MATRIX.md)

## Product delivery order

1. Brand 1 production readiness.
2. Brand 1 optimization and hardening.
3. Owner Panel.
4. Brand 2 through Brand 5 activation.
5. Enterprise expansion.

The platform is designed for multiple brands, but only Brand 1 is released first. The operating principle is:

> Build for many, release for one.
