# Sprint 20J-C — Sunday Holiday and Running Clock Hotfix

## Status

**COMPLETE — pending remote verification**

## Changes

- PCSO returns `Libur` on Sunday.
- Non-operational calendar days return `holiday`.
- Short previous-day overnight cycles remain compatible.
- Long inactive-day cycles no longer remain open.
- Public header displays a running Jakarta clock.
- Locale: `id-ID`.
- Timezone: `Asia/Jakarta`.
- Refresh interval: one second.
- Format: `Minggu, 2 Agu 2026 (18:48:55)`.

## Verification

- Baseline: `ca324e1a232907ff385d774756a8337f72c67dd0`
- Targeted tests: `17`
- Targeted assertions: `53`
- Full tests: `522`
- Full assertions: `1531`
- Production PCSO Sunday acceptance: PASS
