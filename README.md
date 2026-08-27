# HULL Homepage Renewal — Handover Package

> Full handover of the **HULL Corporation homepage renewal** (`hull-inc.jp`) as of **2026-08-27**.
> `git clone` + one archive restore = a working local site, ready to continue development.

---

## Table of contents

- [What this is](#what-this-is)
- [Quick start — get the site running locally in ~10 minutes](#quick-start)
- [Repository layout](#repository-layout)
- [Roadmap — what to work on next](#roadmap--what-to-work-on-next)
- [Deployment to production](#deployment-to-production)
- [Working principles](#working-principles)
- [Where to look next](#where-to-look-next)
- [Access and credentials](#access-and-credentials)

---

## What this is

- **Project:** IA-level rewrite of `hull-inc.jp`, the corporate site of HULL Corporation (Japan) — a digital-signage, sign, space-construction, and reform contractor.
- **Reason:** Company-wide reorganization takes effect **2026-09**. The existing IA does not fit the new org structure, so the site is being restructured before the reorg lands.
- **Current stage:** The local site is functionally complete. Design/CSS on `/space/`, `/sign/`, `/reform-business/` is finished (2026-08-24). What remains is one page-level bug fix, legacy-page cleanup, then production cutover.
- **Deployment target:** Cutover between **2026-08-20~25**, public launch **2026-08-26**.

For the full narrative — motivation, constraints, IA rationale, chronological history, all past decisions — see **[CLAUDE.md](CLAUDE.md)**. Read it before touching anything nontrivial.

---

## Quick start

Get from `git clone` to a working local site in ~10 minutes.

### Requirements
- Windows 10/11 or macOS
- Git
- [Local by Flywheel](https://localwp.com/) (free)
- ~5 GB free disk space

### Steps

1. **Clone this repo** to somewhere convenient (NOT inside `~/Local Sites/`).
   ```bash
   git clone https://github.com/sehyun2727/HullHomePage.git hull-homepage-handover
   cd hull-homepage-handover
   ```

2. **Reassemble the site backup** — combines 16 split `.wpress` parts into one file.
   ```bash
   cd site-backup
   cat parts/hull-site.wpress.part.* > hull-site.wpress
   sha256sum -c wpress-original.sha256   # should say "OK"
   ```
   Detailed procedure incl. Windows PowerShell version: [`site-backup/README.md`](site-backup/README.md).

3. **Install Local by Flywheel**, then create a new WordPress site.
   - Site name: `hull-homepage-renewal` (matches paths used in `CLAUDE.md`)
   - Environment: Preferred (PHP 8.x, MySQL 8.x)

4. **Install the "All-in-One WP Migration" plugin** on the site (Plugins → Add New → search → Install → Activate).

5. **Import** the reassembled `hull-site.wpress` via **All-in-One WP Migration → Import → File**. Wait 5–15 min.

6. **Log in** with the admin credentials from the previous owner (see [Access and credentials](#access-and-credentials)).

7. **Re-save permalinks:** Settings → Permalinks → **Save Changes**. Without this step `/column/*` and `/works/*` return 404.

8. **Verify** every top-level route renders:
   `/`, `/space/`, `/vision/` (or `/digital-signage/`), `/sign/`, `/reform-business/`, `/philosophy/`, `/company/`, `/column/`, `/works/`, `/archives/category/news/`.

Hard-refresh (Ctrl+Shift+R) if styles look off — Lightning caches CSS via a hardcoded version number.

---

## Repository layout

```
hull-homepage-handover/
├── README.md                    ← you are here
├── CLAUDE.md                    project brief: goals, IA, principles, full history
├── .gitignore
│
├── docs/                        operational documentation
│   ├── setup.md                 local setup (companion to Quick start above)
│   ├── deployment.md            how to push the local site to hull-inc.jp
│   ├── architecture.md          IA tree, page inventory (post IDs), URL policy, CSS conventions
│   ├── file-naming-rules.md     English-only kebab-case naming
│   └── decisions-log.md         D-001..D-009 — each design decision + its "why"
│
├── spec/                        original v3 planning documents (docx)
│   ├── hull-homepage-renewal-plan-v3.ja.docx
│   ├── hull-homepage-renewal-plan-v3.ko.docx
│   └── README.md
│
├── theme-source/
│   ├── lightning-child/         mirror of the live child theme for edit-history tracking
│   └── README.md                editing workflow + parent theme note
│
├── assets/
│   ├── photos/                  28 pre-upload source photos (kebab-case)
│   │   └── legacy-green-logo/   22 files — older green logo + WP sizes
│   ├── design-guidelines/       5 design reference mocks
│   └── README.md
│
└── site-backup/
    ├── parts/                   hull-site.wpress.part.000..015 (16 chunks, ~90 MB each)
    │   └── SHA256SUMS
    ├── wpress-original.sha256
    └── README.md                restore procedure
```

Everything needed is inside this folder. Credentials are delivered separately (see below).

---

## Roadmap — what to work on next

Ordered by urgency. Every item includes what to change, why, how to verify, and time estimate.

### 🔴 Priority 1 — before public launch (target: 2026-08-26)

#### [1] Full backup of live `hull-inc.jp` before cutover
- **Why:** Insurance. If cutover breaks something, you need to be able to roll back within minutes.
- **How:** Log into `hull-inc.jp/wp-admin` → All-in-One WP Migration → Export → File. Save the `.wpress` locally *and* to an external drive.
- **Retain for:** at least 30 days post-launch.
- **Verify:** `.wpress` file downloaded, size sanity-checked (should be similar to the local one, ~1.5 GB).
- **Est:** 15 min (5 min click + 10 min download).

#### [2] TOP page HULL Space card link fix (post 2659)
- **Symptom:** On the TOP page, the "About HULL" section's HULL Space card has the title `空間施工事業` but its link points to `/sign/` (a `サイン事業` page). This is the same shape of bug that was fixed in the footer widget on 2026-08-17 (`CLAUDE.md` §5, footer bug fix).
- **Why:** The TOP page redesign was out of scope for the prior work, so this was recorded but not fixed. It's a wrong click destination for real users.
- **Fix:** Change the card's `href` from `/sign/` to `/space/`. In wp-admin, edit post 2659 → find the About HULL card block → update the button link.
- **Verify:** Hover the card in TOP, click, confirm it lands on `/space/`.
- **Est:** 15 min.

#### [3] Deploy local site to production
- **Full procedure:** [`docs/deployment.md`](docs/deployment.md)
- **Short version:** Export the local site's `.wpress` → import over `hull-inc.jp` via All-in-One WP Migration → re-save permalinks → verify every route → search-replace any surviving `hull-homepage-renewal.local` URLs → cache flush.
- **Est:** 60–90 min total (mostly waiting on file transfers).
- **⚠️ Only the previous owner or the incoming developer with wp-admin access can do this.** No SSH, no FTP.

### 🟡 Priority 2 — cleanup, within 2 weeks post-launch

#### [4] Legacy page cleanup
- **Pages to handle:** `旧会社概要` (slug: `about`, currently private), `/services_old`, `/signdisplay`, and anything else the old IA left behind.
- **Rule:** Never delete pages. Set to `private` and add 301 redirect if there's an inbound link expectation.
- **Plugin to use:** Redirection (already installed).
- **Verify:** All old URLs return 301 to the correct new page; no 404 in Search Console for known old URLs.
- **Est:** 30–60 min depending on how many URLs.

#### [5] Message page (`/message/`, post 3077) decision
- **Situation:** Content was migrated into `/philosophy/` (企業理念) but the page itself is still `publish` and creates duplicate content. Not linked from any menu.
- **Options:** (a) unpublish → private, (b) delete-and-301 to `/philosophy/`, (c) leave as-is if you find any inbound link.
- **⚠️ This is destructive — get owner approval before executing.**
- **Est:** 15 min after decision.

#### [6] Search Console + Analytics sanity check
- **Why:** After a big IA change, new URLs need to be picked up by Google. Old URLs need to redirect (see [4]).
- **How:** Submit new sitemap, verify no unexpected 404s, check crawl coverage after 3-7 days.
- **Est:** 15 min setup, then monitoring for a week.

### 🟢 Priority 3 — enhancement, no fixed deadline

#### [7] HULL VISION landing internal signage navigation
- **What:** Add internal links from `/digital-signage/` down to DokoDemo signage and TopBoard signage sub-pages.
- **Why deferred:** The previous owner marked this "나중에" (later). Structural change, not a bug fix.
- **Est:** 2–3 hours (page structure + content coordination with Yoshizawa-san).

#### [8] Reform page real content
- **What:** `/reform-business/` (post 6834) has scaffolding but the PDF-based real content layout hasn't been finalized.
- **Who:** Yoshizawa-san can edit copy/images from wp-admin. Structural layout may need dev help.
- **Est:** Variable, depends on final PDF spec.

#### [9] Image optimization + SEO plugins
- **Recommended:** Smush (image auto-optimization), Rank Math or Yoast (SEO metadata).
- **Why not now:** Not blocking launch. Add after cutover.
- **Est:** 30 min each.

#### [10] Unused-theme cleanup in Local
- **What:** `wp-content/themes/` ships with 15+ unused themes (`affinger5×3`, `cocoon×2`, several `twentytwenty*`).
- **Warning:** Per delete-scope rule, never touch `Local Sites/` files without explicit confirmation. If disk becomes tight, get user approval first.

---

## Deployment to production

Detailed procedure is [`docs/deployment.md`](docs/deployment.md). Load-bearing summary:

- **Model:** Whole-site replacement. The local site is exported as one `.wpress` and imported over live. No diff-based sync, no file-by-file transfer.
- **Access constraint:** `hull-inc.jp` is **wp-admin only** — no SSH, no FTP, no direct DB. The `All-in-One WP Migration` plugin's Import path is the only practical channel for a change this large.
- **Bottleneck:** The developer with wp-admin credentials is the sole person who can push. Plan accordingly.
- **Rollback:** Keep the pre-cutover live backup ([Priority 1 item 1]) accessible for 30+ days.

---

## Working principles

Full list in [CLAUDE.md](CLAUDE.md) §6-§7. The ones that will bite you if ignored:

1. **Method approval before deviating.** If the instructed method is blocked, report first — do not switch on your own, especially not to something flagged as risky.
2. **"Investigation only" means don't execute.** Content merges and structural edits are separate steps from research.
3. **Ground truth is the live DB, not backups.** Any backup file in this repo is a moment-in-time snapshot. For "what does the site actually look like right now?", query via WP-CLI.
4. **Verify environment-specific configs.** `wpcli-php.ini` / port numbers / DB names are per-site; do not copy across sites without checking.
5. **Destructive actions require prior report.** Deletes, mass updates, DB schema changes.
6. **Never delete pages.** Set to `private` instead. Recoverability is the reason.
7. **Never delete files under `~/Local Sites/`.** That's the WordPress install itself. Other cleanup is fine on request.

---

## Where to look next

| Question | File |
|---|---|
| "What is this project? What's the goal? What's the full history?" | [CLAUDE.md](CLAUDE.md) |
| "How do I set this up locally?" | [Quick start](#quick-start) → [docs/setup.md](docs/setup.md) → [site-backup/README.md](site-backup/README.md) |
| "How does the site get deployed to `hull-inc.jp`?" | [docs/deployment.md](docs/deployment.md) |
| "What are the pages? What are the post IDs?" | [docs/architecture.md](docs/architecture.md) |
| "Why is this like this?" (any specific decision) | [docs/decisions-log.md](docs/decisions-log.md) |
| "How do I name a new file?" | [docs/file-naming-rules.md](docs/file-naming-rules.md) |
| "Where are the source photos?" | [assets/README.md](assets/README.md) |
| "What was the original plan (v3)?" | [spec/README.md](spec/README.md) → the two docx files |
| "Where is the child theme?" | [theme-source/README.md](theme-source/README.md) + `~/Local Sites/.../lightning-child/` |

---

## Access and credentials

**None of the following live in this repo.** All are delivered through a separate secure channel by the previous owner:

- WordPress admin username & password (both `hull-inc.jp` live and local test accounts)
- Any hosting-panel access if it exists
- GitHub personal access token (if the outgoing developer's credential is being retired)
- Domain registrar login (usually stays with the internal team, but confirm)
- Yoshizawa-san's contact for structural questions

If a credential you need is missing from the handover packet, contact the previous owner first, then the internal team (Yoshizawa-san).

---

## Housekeeping — keeping this repo honest

- **After editing the child theme** (`~/Local Sites/hull-homepage-renewal/.../lightning-child/`): copy the changed file back into `theme-source/lightning-child/` in this repo and commit. Otherwise git history and the deployed theme drift.
- **After every `.wpress` re-export:** replace `site-backup/parts/*`, regenerate `SHA256SUMS`, update `wpress-original.sha256`, bump the "Backup metadata" table in `site-backup/README.md`.
- **Push size limits:** the 16 × 90 MB parts pushed cleanly when the push was split into 3 commits (docs + parts 000-007 + parts 008-015). A monolithic 1.4 GB push times out (HTTP 408). Keep the 3-commit pattern.
- **Global page CSS is not on disk** — it lives in Additional CSS (post 2641), edited via wp-admin. Section markers (`/* ===== [TOP] START/END ===== */`, `[SPACE]`, `[VISION]`, etc.) must be preserved; imports rely on them. See [`docs/architecture.md`](docs/architecture.md).
