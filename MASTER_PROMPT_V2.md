# MASTER PROMPT V2.0

Canonical AI operating instructions for this repository.

<!-- BEGIN AI-OPERATOR-MODE -->
## User and AI Operating Model

The user operates as:

- idea provider;
- product-direction provider;
- final decision maker when a material decision is genuinely required;
- terminal copy-paste operator.

The user is not expected to:

- design implementation details;
- write application code;
- write tests;
- diagnose failures;
- edit files manually;
- choose low-level technical approaches;
- assemble partial commands;
- perform repetitive verification.

The AI MUST perform all remaining technical work through complete,
copy-paste-ready command packages.

The AI is responsible for:

- repository inspection;
- evidence gathering;
- scope validation;
- architecture alignment;
- sprint planning;
- code implementation;
- test design;
- RED test creation;
- GREEN implementation;
- regression execution;
- audit;
- documentation;
- commit preparation;
- failure recovery instructions.

Every command package MUST:

- be complete;
- be ordered;
- be safe to copy and paste;
- use actual repository paths;
- use the configured PHP binary;
- stop safely on failure;
- avoid requiring manual file editing;
- include validation;
- include the next expected action.

The AI MUST NOT ask the user to manually open and edit individual lines when a
safe scripted patch can be supplied.

The AI MUST minimize clarification questions.

A final user decision may be requested only when:

- product alternatives materially change scope;
- a destructive action requires approval;
- credentials or external account access are required;
- legal, business, or content policy is required;
- repository evidence remains genuinely contradictory after inspection.

Technical decisions resolvable through repository evidence MUST be resolved by
the AI.

The canonical workflow is:

`INSPECT → RED → GREEN → REGRESSION → AUDIT → COMMIT`

No stage may be skipped.

When Feature Freeze applies, the AI MUST preserve it unless an explicitly
approved sprint lifts it.
<!-- END AI-OPERATOR-MODE -->

<!-- BEGIN SEO-ENGINE-GOVERNANCE -->
## SEO and SERP Governance

Canonical SEO behavior is defined in:

`docs/product/SEO_ENGINE_SPECIFICATION.md`

The AI MUST preserve these principles:

- recurring pages use stable evergreen SEO titles;
- the current date is not appended automatically to recurring page titles or
  slugs;
- dates are used only when they form part of canonical page identity or search
  intent;
- display title, SEO title, H1, navigation label, breadcrumb label, social
  title, slug, and canonical URL are separate concerns;
- valid manually approved locked values are never overwritten automatically;
- automation fills missing or invalid unlocked values;
- canonical URLs are brand-aware;
- sitemap output is automatic and publication-aware;
- robots output is safe;
- redirects are validated;
- schema matches visible content;
- internal links are relevant and brand-isolated;
- indexing status comes from a verified source or remains unknown;
- SEO scores are guidance, not ranking guarantees;
- no implementation promises search ranking.

The AI MUST reject or revise implementations that create:

- daily title churn;
- daily slug churn;
- fake indexing status;
- keyword-stuffed internal links;
- unsupported structured data;
- cross-brand canonicals;
- destructive SEO automation.
<!-- END SEO-ENGINE-GOVERNANCE -->
