# ADR-0003 - Core Test Isolation

## Status

Accepted

## Context

PHPUnit harus selalu menggunakan SQLite in-memory agar test tidak pernah
mengakses database development maupun production.

## Decision

- Tambahkan global database guard.
- Semua Feature Test menggunakan Tests\TestCase.
- Database testing menggunakan SQLite :memory:.
- Test environment harus terisolasi sepenuhnya.

## Consequences

- Test aman dijalankan.
- CI lebih konsisten.
- Production terlindungi.
