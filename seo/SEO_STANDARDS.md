# SEO STANDARDS

Version: 1.0

---

# Purpose

This document defines the SEO implementation standards used throughout Portal Prediksi CMS.

Every module that produces public-facing pages must comply with these standards.

The objective is to ensure consistency, scalability, and maintainability across the entire platform.

---

# Objectives

SEO standards ensure:

- Consistent metadata.
- Uniform page structure.
- Predictable indexing.
- High content quality.
- Reusable implementation.
- Repository-wide consistency.

---

# Page Standards

Every public page should include:

- Unique Title
- Unique Meta Description
- Canonical URL
- Primary Heading (H1)
- Logical Heading Hierarchy
- Internal Navigation
- Structured Metadata

---

# Title Standards

Titles should:

- Be unique.
- Clearly describe the page.
- Remain concise.
- Match page intent.
- Avoid duplication.

Title generation should remain centralized whenever possible.

---

# Meta Description Standards

Descriptions should:

- Summarize page content.
- Be unique.
- Match user intent.
- Avoid duplication.
- Remain human-readable.

Descriptions should not be generated independently by each module.

---

# Heading Standards

Pages should follow:

H1

↓

H2

↓

H3

↓

H4

Headings should reflect logical document structure.

Multiple H1 elements should be avoided.

---

# URL Standards

URLs should:

- Use lowercase characters.
- Use hyphens.
- Avoid unnecessary parameters.
- Remain stable.
- Reflect resource hierarchy.

URL generation should follow centralized routing policies.

---

# Internal Linking

Internal links should:

- Connect related content.
- Use descriptive anchor text.
- Avoid broken links.
- Preserve logical navigation.

Internal linking should support both users and search engines.

---

# Image Standards

Images should include:

- Descriptive filenames.
- Alternative text.
- Appropriate dimensions.
- Optimized file size.
- Lazy loading where appropriate.

Image optimization should not reduce usability.

---

# Structured Data Standards

Structured data should:

- Follow Schema.org specifications.
- Be centrally maintained.
- Remain valid.
- Match visible content.
- Avoid duplication.

Validation should occur before production release.

---

# Canonical Standards

Every indexable page should define:

- Canonical URL
- Indexation status
- Preferred location

Canonical URLs should never form loops.

---

# Validation Checklist

Before publication verify:

- Title present.
- Description present.
- Canonical present.
- Headings valid.
- URLs consistent.
- Internal links working.
- Structured data valid.
- Images optimized.

---

# Governance

These standards are governed by:

- SEO Architecture
- Master Architecture
- Platform Layers
- Documentation Governance

All SEO implementations must comply with these standards before deployment.
