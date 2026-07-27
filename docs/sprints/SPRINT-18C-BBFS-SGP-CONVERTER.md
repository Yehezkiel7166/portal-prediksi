# Sprint 18C — BBFS Generator and Konversi Angka SGP

## Status

Implementation package prepared. Completion requires guarded PHP 8.3 syntax validation, targeted regression, full regression, governance audit, CTO crosscheck, commit, push, and remote verification.

## Baseline

- Branch: `feat/domain-management-foundation`
- Commit: `53968bc1084fadd1e695b23cbe99088567cf551a`
- Tree: `79e519d3ce06b51c4d5818c6bf03f2773d70fe3b`

## Objective

Complete two deterministic Brand 1 lottery-tool capabilities without introducing database persistence or external dependencies.

## BBFS Rules

- accepts numeric input with simple separators;
- preserves the first occurrence order of each digit;
- requires 2–7 unique digits by default;
- supports 2D, 3D, and 4D output;
- creates ordered permutations without repeating a digit inside one output;
- produces the same ordered output for the same normalized input and length;
- does not persist visitor input.

## SGP Conversion Rules

For four-digit input `ABCD`:

- AS = `A`;
- KOP = `B`;
- KEPALA = `C`;
- EKOR = `D`;
- 3D = `BCD`;
- 2D = `CD`.

Leading zeroes are preserved. Input must contain exactly four digits and is not persisted.

## Public Scope

- GET and POST `/alat-togel/bbfs-generator`;
- GET and POST `/alat-togel/konversi-angka-sgp`;
- CSRF validation and request throttling;
- canonical and Open Graph metadata;
- responsive output views;
- Alat Togel navigation links.

## Exclusions

- Buku Mimpi;
- Paito Togel Warna;
- database persistence;
- AI-generated output;
- external APIs;
- Owner Panel and Brand 2–5.
