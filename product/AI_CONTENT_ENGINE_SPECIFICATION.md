# AI CONTENT ENGINE SPECIFICATION

## Status

This document defines optional AI-assisted content operations.

AI features MUST NOT be treated as a prerequisite for safe manual operation.

## Objective

Reduce repetitive work while preserving administrator control, accuracy,
brand consistency, SEO governance, and publication safety.

## Supported Assistance

AI MAY assist with:

- content outline suggestions;
- draft descriptions;
- prediction-format drafting from verified input;
- promotion copy drafts;
- guide summaries;
- metadata suggestions;
- alternative-text suggestions;
- internal-link suggestions;
- structured-data field suggestions;
- duplicate-content warnings;
- content-quality checks.

## Prohibited Autonomous Behavior

AI MUST NOT automatically:

- publish content without an approved policy;
- fabricate results;
- fabricate dates;
- fabricate market data;
- fabricate indexing status;
- promise ranking;
- overwrite locked fields;
- rewrite approved titles continuously;
- create doorway pages;
- create keyword-stuffed content;
- add unsupported schema;
- move content between brands;
- create redirects from guesses;
- delete administrator content.

## Source Grounding

Generated output MUST distinguish:

- verified structured data;
- administrator input;
- repository configuration;
- inferred suggestion;
- unknown information.

When required facts are unavailable, AI MUST leave a placeholder, return a
warning, or require review rather than inventing data.

## Human Approval

AI-generated content SHOULD use states such as:

- suggested;
- draft;
- reviewed;
- approved;
- published;
- rejected.

Publication MUST follow existing authorization and publication rules.

## Locked Values

AI MUST preserve all valid locked values, including:

- title;
- SEO title;
- description;
- slug;
- canonical;
- social metadata;
- alternative text;
- structured-data override.

## SEO Integration

AI output MUST comply with the canonical SEO Engine.

AI MAY suggest improvements but MUST NOT bypass:

- evergreen-title rules;
- canonical rules;
- robots rules;
- sitemap rules;
- schema rules;
- brand isolation;
- publication state.

## Auditability

Every accepted AI operation SHOULD record:

- user;
- brand;
- target record;
- operation type;
- input source;
- model or provider identifier where allowed;
- generated suggestion;
- accepted value;
- rejected value where retained;
- timestamp.

Sensitive prompts or credentials MUST NOT be exposed in public logs.

## Provider Abstraction

AI integrations SHOULD use an application contract rather than direct provider
calls throughout business logic.

The abstraction SHOULD support:

- provider replacement;
- timeout;
- retry;
- rate limiting;
- failure handling;
- cost controls;
- test fakes;
- feature disablement.

## Queue Behavior

Long-running AI operations SHOULD use queues.

Jobs MUST be:

- brand-aware;
- idempotent where possible;
- retry-safe;
- permission-aware;
- auditable.

## Failure Behavior

AI failure MUST NOT block core CMS operation.

The last confirmed valid content MUST remain active.

## Administration

Administration SHOULD provide:

- request suggestion;
- preview;
- diff;
- accept;
- reject;
- regenerate;
- lock;
- source warning;
- confidence or validation warning where appropriate;
- audit history.

## Testing Requirements

Tests MUST cover:

- locked-value preservation;
- no automatic publication;
- brand isolation;
- provider failure;
- queue retry;
- unsupported-fact rejection;
- SEO-governance compliance;
- audit creation;
- permission enforcement;
- core operation without AI provider.

## Completion Gate

The engine is complete only when:

- AI is optional;
- AI output requires appropriate review;
- verified data is not fabricated;
- locked values are preserved;
- provider failures do not break CMS operation;
- auditability exists;
- tests pass.
