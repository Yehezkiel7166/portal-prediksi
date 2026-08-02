# Sprint 20J — Public Frontend Defect Remediation

## Status

**COMPLETE — pending commit push and remote verification**

## Baseline

- Branch: `main`
- Baseline: `f52e347f79b5f88e3734bb6ebdebccb2735f8bda`
- PHP: `8.3.30`
- Database migration: not required

## RED evidence

- Homepage exposed `Modul Publik Brand 1`.
- Homepage exposed an internal placeholder.
- Jadwal Togel displayed timezone publicly.
- Jadwal Togel used the wrong public column order.
- Jadwal Togel had no public module links.
- Market status treated open, close, and result times as one same-day window.
- Generated Shio banner existed in public storage but was not rendered.

## GREEN

- Internal homepage labels removed.
- Jadwal columns changed to Tutup, Hasil, Buka, Status, and Link.
- Public timezone column removed.
- Prediction, Result, Live Draw, and Paito links added.
- Overnight market cycle calculation implemented.
- Existing `live` status contract retained.
- Public label for `live` state changed to `Menunggu Hasil`.
- Generated Shio banner is resolved from public storage and rendered with alt text.
- No database mutation or migration introduced.

## Verification

- Targeted tests: `22`
- Targeted assertions: `115`
- Full tests: `513`
- Full assertions: `1505`
- PHP syntax validation: PASS
- Git diff check: PASS

## Deferred bounded work

### Sprint 20K

`Automated Prediction and SEO Engine`

### Later foundation

Theme presets and configurable homepage banners require a dedicated Site Configuration and responsive media foundation.
