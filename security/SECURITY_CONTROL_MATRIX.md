# Security Control Matrix

Status: Active baseline

| Control | Priority | Required evidence |
|---|---:|---|
| Explicit Brand Context | P0 | HTTP/admin/job tests |
| Brand-scoped policies | P0 | Authorization tests |
| Cross-brand query isolation | P0 | Negative regression suite |
| Brand-aware cache/storage | P0 | Namespace and leakage tests |
| Guard privileged attributes | P0 | Mass-assignment tests |
| Login rate limiting | P0 | Feature test/config evidence |
| Secure production sessions | P0 | Environment/config verification |
| `APP_DEBUG=false` | P0 | Deployment verification |
| CSRF and output escaping | P0 | Framework/config and tests |
| Upload validation | P0 | Invalid-file tests |
| Remote media SSRF protection | P0 when enabled | URL/IP/redirect tests |
| Secrets absent from Git | P0 | secret scan and review |
| Audit sensitive actions | P0 | Audit integration tests |
| Backup automation | P0 | backup artifact and schedule |
| Restore rehearsal | P0 | documented successful restore |
| Dependency audit | P0 | Composer/npm review |
| Queue failure visibility | P0 | failed-job operational check |
| Scheduler heartbeat | P0 | current heartbeat evidence |
| Security headers | P1 | response validation |
| 2FA for privileged users | P1 | implementation or documented rollout |
| Session timeout/reauthentication | P1 | tests for sensitive actions |
| Centralized error monitoring | P1 | monitoring evidence |
| Incident response runbook | P1 | reviewed runbook |
| Key and credential rotation procedure | P1 | documented procedure |

## Transitional finding

The existing broad `is_admin` access pattern is acceptable only as a temporary development control. It is not sufficient for production multi-brand administration. Production requires policies and brand-scoped role/permission assignments.
