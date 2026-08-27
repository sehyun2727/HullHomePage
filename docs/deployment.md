# Deployment to Production (`hull-inc.jp`)

> How the finished local site gets pushed to the live server.

**Status:** Skeleton. Full procedure lands in Phase 8.

---

## Deployment model

The plan is **whole-site replacement**, not piecemeal file/DB sync.

- The local site at `hull-homepage-renewal` is a 100% mirror of production as of the 2026-07-31 import
- All renewal work happened locally
- At cutover, the local site is exported and imported over the live site

This is the reason `.wpress` is the release artifact: one file = one site.

---

## Access constraint

- `hull-inc.jp` is **wp-admin only**. No SSH, no FTP, no direct DB.
- Server-side migrations are impossible; everything happens through WP Admin UI + plugins.
- **Only the previous owner can push to live** — this is the single bottleneck in the project.

---

## Steps (high level, subject to expansion)

1. **Full backup of the live site** — WP Admin → All-in-One WP Migration → Export → File. Save this before touching anything.
2. **Freeze local changes** — decide the cutover snapshot; export a fresh `.wpress` from local.
3. **Import the local `.wpress` into live** via All-in-One WP Migration → Import → File.
4. **Re-save permalinks** — Settings → Permalinks → Save. Required.
5. **Search-replace URLs** if any local URLs (`hull-homepage-renewal.local`) survived — use the WP Admin plugin *Better Search Replace* or equivalent.
6. **Verify every top-level route**: `/`, `/space/`, `/vision/` or `/digital-signage/`, `/sign/`, `/reform-business/`, `/philosophy/`, `/company/`, `/column/`, `/works/`, `/archives/category/news/`.
7. **Cache flush** — clear any caching plugin, hard-refresh, check on real mobile.

---

## Rollback plan

If the import breaks live:
1. Import the pre-cutover backup from step 1.
2. Re-save permalinks.
3. Verify homepage renders.

Keep the pre-cutover `.wpress` accessible for **at least 30 days** post-launch.

---

## Post-launch handoff to internal team

After cutover, day-to-day content edits (copy, images, small CSS) can be done in wp-admin by the internal team (see `CLAUDE.md` §1). Structural changes and DB migrations still require a developer.

---

## Related

- `docs/architecture.md` — what the site is supposed to look like when it works
- `docs/decisions-log.md` — why the deployment model is whole-site replacement
- `site-backup/README.md` — how the `.wpress` gets built and verified
