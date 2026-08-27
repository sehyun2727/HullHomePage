# Local Development Setup

> How to bring the HULL homepage renewal project up on a fresh laptop and start editing.

**Status:** Skeleton. Full procedure lands in Phase 8.

---

## Requirements

- Windows 10/11 or macOS
- [Local by Flywheel](https://localwp.com/) — free WordPress dev environment
- Git
- ~5 GB free disk space (site backup + Local's WP core/plugins/uploads)

---

## Steps (high level)

1. **Clone this repo** to somewhere convenient (not inside the Local Sites folder).
2. **Reassemble the site backup**
   - Follow `site-backup/README.md` — concatenates the split `.wpress` parts into one file and verifies SHA256.
3. **Install Local by Flywheel** (if not already installed).
4. **Create a new site in Local**
   - Site name: `hull-homepage-renewal` (any name works, but this matches the paths in `CLAUDE.md`)
   - Environment: Preferred (PHP 8.x, MySQL 8.x recommended — must match the export)
5. **Install the *All-in-One WP Migration* plugin** on the new site.
6. **Import** the reassembled `.wpress` file via *All-in-One WP Migration → Import → File*.
   - This overwrites the fresh install with the full HULL site (DB, media, themes, plugins).
7. **Verify** — `/`, `/space/`, `/vision/` (or `/digital-signage/`), `/sign/`, `/reform-business/`, `/philosophy/`, `/company/` all render.
8. **Hard-refresh** (Ctrl+Shift+R) if styles look off.
9. **Re-save permalinks** — WP Admin → Settings → Permalinks → Save (no changes). Rewrites regenerate. Required after any import.

---

## First-time login

- Admin URL: `<local-site-url>/wp-admin`
- Credentials: **not stored in this repo.** Get them from the previous owner via a secure channel.

---

## Troubleshooting

*To be expanded in Phase 8.*
- Import fails with "413 Request Entity Too Large" → increase upload limit in Local's PHP config.
- 404 on `/column/`, `/column/category/*` after import → re-save permalinks (step 9).
- Missing images / broken CSS → hard refresh, then check `wp-content/uploads/` was fully imported.

---

## Where the site actually lives after setup

`~/Local Sites/hull-homepage-renewal/app/public/` (macOS) or `C:\Users\<you>\Local Sites\hull-homepage-renewal\app\public\` (Windows).

The child theme is at `wp-content/themes/lightning-child/`. Compare it against `theme-source/lightning-child/` in this repo if you need to track edits.
