# HOMEPAGE, WIDGET, SLOT GACOR, AND RTP BLUEPRINT

Status: APPROVED TARGET ARCHITECTURE
Target: 90 DAYS

## Rendering Flow

Owner Theme
→ Owner Homepage Template
→ Owner Widget Definitions
→ Brand Widget Instances
→ Brand Content
→ Public Rendering

## Widget Registry

- Hero
- Navigation
- Banner
- Promotion
- Market
- Prediction
- Result
- Live Draw
- Slot Gacor
- RTP
- Shio
- BBFS
- Buku Mimpi
- Kode Alam
- Paito
- Latest Blog
- Guide
- Complaint CTA
- FAQ
- Internal Links
- SEO Content
- HTML
- Advertisement
- Countdown

## Global Owner Entities

- themes
- theme_versions
- homepage_templates
- homepage_template_sections
- widget_definitions
- widget_definition_versions
- slot_providers
- slot_games
- slot_game_media
- slot_game_categories

## Brand Entities

- brand_homepages
- brand_homepage_sections
- brand_widget_instances
- brand_widget_payloads
- brand_slot_games
- brand_slot_rtp_snapshots
- brand_slot_patterns
- brand_slot_schedules
- brand_slot_badges
- brand_slot_publication_rules

## Brand Slot Fields

- brand_id
- slot_game_id
- display_name
- status
- badge
- rtp_value
- rtp_source_type
- rtp_source_reference
- rtp_updated_at
- hot_start_at
- hot_end_at
- manual_spin_pattern
- auto_spin_pattern
- recommendation
- display_order
- published_at
- expires_at
- created_by
- updated_by

## Audit

Setiap perubahan RTP dan pola menyimpan:

- nilai lama;
- nilai baru;
- sumber;
- actor;
- alasan;
- timestamp.
