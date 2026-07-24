# MEDIA ENGINE SPECIFICATION

## Status

This document defines the canonical media-processing architecture for Brand 1
and future brands.

## Objective

Provide secure, reusable, SEO-aware, brand-isolated image and media management.

## Supported Responsibilities

The engine SHOULD support:

- image upload;
- file validation;
- metadata extraction;
- responsive derivatives;
- thumbnail generation;
- WebP generation;
- AVIF generation where supported;
- original-file preservation policy;
- crop variants;
- social-image variants;
- banner variants;
- image alternative text;
- captions;
- focal point;
- media ownership;
- media reuse;
- safe deletion;
- cache invalidation.

## Security

Uploads MUST validate:

- MIME type;
- extension;
- file signature where practical;
- maximum size;
- pixel dimensions;
- decompression risk;
- filename normalization;
- storage destination;
- authorization.

Executable content MUST NOT be accepted as an image.

User-supplied SVG MUST be rejected or sanitized using an explicitly approved
sanitization process.

## Brand Isolation

Every media record MUST belong to the correct brand unless explicitly marked as
a protected shared system asset.

Cross-brand media usage MUST be rejected by default.

## Image Variants

The engine SHOULD produce only required variants.

Possible variants include:

- original;
- desktop banner;
- mobile banner;
- card;
- list;
- thumbnail;
- Open Graph;
- square social;
- portrait social;
- favicon;
- compact logo.

Variant generation MUST be:

- deterministic;
- idempotent;
- queue-safe;
- retry-safe;
- auditable.

## Responsive Images

Frontend rendering SHOULD support:

- `srcset`;
- `sizes`;
- width and height attributes;
- modern formats;
- safe fallback format;
- lazy loading where appropriate;
- eager loading for critical above-the-fold images.

## Image SEO

The engine SHOULD support:

- administrator-written alternative text;
- context-derived suggestion;
- manual lock;
- filename normalization;
- caption;
- image sitemap eligibility;
- canonical media URL where applicable.

Automated alternative text MUST NOT invent unsupported details.

Decorative images SHOULD use empty alternative text.

## Banner Processing

A single approved source image MAY generate:

- desktop banner;
- tablet banner;
- mobile banner;
- card thumbnail;
- Open Graph image.

Automatic cropping SHOULD use:

- configured focal point;
- safe-area rules;
- module-specific aspect ratios.

Administrators MUST be able to preview generated variants.

## Storage

Storage architecture MUST support:

- private temporary upload area;
- public processed-media area;
- predictable brand-aware paths;
- collision-safe filenames;
- controlled deletion;
- orphan cleanup;
- CDN-compatible URLs where configured.

## Watermark

Watermarking MAY be supported.

Watermark behavior MUST be:

- brand-aware;
- configurable;
- previewable;
- non-destructive to the original;
- disabled by default for logos, icons, and unsuitable assets.

## Cache and CDN

Media URLs SHOULD support stable cache behavior.

Replacing a file SHOULD produce a new versioned URL or reliable cache
invalidation.

The system MUST avoid serving stale media after approved replacement.

## Failure Behavior

When processing fails:

- the original valid upload SHOULD remain recoverable;
- broken derivatives MUST NOT be published;
- the previous confirmed valid media SHOULD remain active;
- failure details MUST be recorded;
- retry MUST remain safe.

## Administration

Administration SHOULD include:

- upload;
- variant preview;
- focal-point control;
- alternative text;
- caption;
- usage references;
- replacement;
- activation;
- archive;
- deletion warnings;
- processing state;
- failure state.

## Testing Requirements

Tests MUST cover:

- MIME validation;
- malicious-extension rejection;
- brand isolation;
- derivative generation;
- idempotent retries;
- focal-point behavior;
- safe replacement;
- cache versioning;
- locked alternative text;
- failure fallback;
- orphan prevention.

## Completion Gate

The engine is complete only when:

- uploads are validated;
- variants are generated reliably;
- brand isolation is enforced;
- responsive output works;
- SEO metadata is available;
- replacement does not serve stale files;
- queue retry is safe;
- tests pass.
