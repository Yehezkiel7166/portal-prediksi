# SEO URL POLICY

Version: 1.0

---

# Purpose

This document defines the URL policy for Portal Prediksi CMS.

A consistent URL structure improves maintainability, user experience, crawl efficiency, and long-term SEO performance.

Every public route must comply with this policy.

---

# Objectives

The URL policy aims to:

- Maintain consistent URL structure.
- Improve readability.
- Preserve stable links.
- Simplify routing.
- Reduce duplicate content.
- Support scalable content organization.

---

# URL Principles

Every URL should be:

- Stable
- Predictable
- Human-readable
- Hierarchical
- Lowercase
- Hyphen-separated

URLs should avoid unnecessary complexity.

---

# URL Structure

Recommended format:

```
/resource

/resource/category

/resource/category/item
```

Hierarchy should reflect the logical organization of the content.

---

# Naming Standards

URL segments should:

- Use lowercase letters.
- Use hyphens between words.
- Avoid spaces.
- Avoid underscores.
- Avoid special characters.
- Avoid encoded characters whenever possible.

---

# Slug Standards

Slugs should be:

- Unique
- Descriptive
- Stable
- Derived from the resource title when appropriate

Changing slugs should be avoided after publication.

---

# Reserved Paths

The following paths are reserved for platform use:

- /admin
- /login
- /logout
- /dashboard
- /api
- /storage
- /assets
- /system

Business modules must not redefine reserved paths.

---

# Redirect Policy

Redirects should:

- Preserve user intent.
- Minimize redirect chains.
- Use permanent redirects when appropriate.
- Maintain canonical consistency.

Broken URLs should be redirected whenever possible.

---

# Canonical Consistency

Every indexable URL should have:

- One canonical URL.
- One preferred location.
- One authoritative version.

Duplicate URLs should not compete in search results.

---

# Pagination

Paginated URLs should:

- Follow consistent naming.
- Preserve canonical rules.
- Avoid duplicate content.
- Support crawl efficiency.

---

# Internationalization Readiness

Future localization should support structured URL prefixes without changing existing routing architecture.

Example:

```
/en/
/id/
/ja/
```

Localization should remain compatible with existing routing standards.

---

# Validation Checklist

Before publishing verify:

- URL format is valid.
- Slug is unique.
- Canonical URL exists.
- Reserved paths are respected.
- Redirect rules are valid.
- URL hierarchy is logical.

---

# Governance

This policy is governed by:

- SEO Architecture
- SEO Standards
- Master Architecture
- Platform Layers

All routing and URL generation must comply with this policy.
