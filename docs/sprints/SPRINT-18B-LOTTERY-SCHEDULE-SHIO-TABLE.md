# Sprint 18B — Jadwal Togel and Tabel Shio

## Status

Implementation package prepared. Completion requires guarded server migration, targeted regression, full regression, governance audit, CTO crosscheck, commit, push, and remote verification.

## Baseline

- Branch: `feat/domain-management-foundation`
- Commit: `de2ac4da66cddb760c3d1c679d09ec737b5c94b5`

## Objective

Complete two Brand 1 lottery-tool capabilities by exposing Market-backed Jadwal Togel and the current published Tabel Shio.

## Scope

- add canonical schedule configuration to Market;
- calculate upcoming, open, live, closed, holiday, inactive, and result-available states;
- public `/alat-togel/jadwal-togel` page;
- public `/alat-togel/tabel-shio` page using published Shio data;
- Filament Market schedule controls;
- Alat Togel navigation integration;
- canonical and Open Graph metadata;
- targeted automated tests;
- canonical repository state synchronization.

## Source-of-truth rules

- Jadwal Togel uses Market configuration and confirmed Result records.
- Tabel Shio uses the existing ShioPeriod and ShioNumber records.
- No duplicate schedule or shio persistence is introduced.

## Exclusions

- BBFS Generator;
- Buku Mimpi;
- Paito Togel Warna;
- Konversi Angka SGP;
- centralized sitemap and cache engines;
- Owner Panel and Brand 2–5.
