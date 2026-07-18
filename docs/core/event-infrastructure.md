# Event Infrastructure

Portal Prediksi CMS uses Laravel native events for reusable domain workflows.

## Rules

- Register application events and listeners explicitly.
- Use Laravel native events instead of a custom dispatcher.
- Use after-commit events for side effects that depend on successful writes.
- Keep primary writes in domain actions and secondary work in listeners.

## Next Step

Add a Shio after-commit event and listener for automatic banner regeneration.

## Shio Event Foundation

`ShioChanged` carries the affected period and dispatches only after a
successful database commit.

`GenerateShioBannerListener` delegates banner creation to the existing
`GenerateShioBannerAction` and skips periods without a banner template.
