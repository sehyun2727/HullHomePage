# HULL Homepage Renewal — Handover Package

> Full handover of the **HULL Corporation homepage renewal** (`hull-inc.jp`) as of **2026-08-27**.
> `git clone` + one archive restore = a working local site, ready to continue development.

---

## Table of contents

- [What this is](#what-this-is)
- [Quick start — get the site running locally in ~10 minutes](#quick-start)
- [Repository layout](#repository-layout)
- [Development history](#development-history)
- [What's left to do](#whats-left-to-do)
- [Working principles](#working-principles)
- [Where to look next](#where-to-look-next)
- [Access and credentials](#access-and-credentials)

---

## What this is

- **Project:** IA-level rewrite of `hull-inc.jp`, the corporate site of HULL Corporation (Japan) — a digital-signage, sign, space-construction, and reform contractor.
- **Reason:** Company-wide reorganization takes effect **2026-09**. The existing IA does not fit the new org structure, so the site is being restructured before the reorg lands.
- **Current stage:** The local site is functionally complete. Design/CSS on `/space/`, `/sign/`, `/reform-business/` is finished (as of 2026-08-24). What remains is edge polish and legacy-page cleanup — see [What's left](#whats-left-to-do).
- **Deployment target:** Cutover between **2026-08-20~25**, public launch **2026-08-26** (see `CLAUDE.md` §1).

Full narrative — motivation, constraints, IA rationale, historical context, working principles — is in **[CLAUDE.md](CLAUDE.md)**. Read it before touching anything nontrivial.

---

## Quick start

### Requirements
- Windows 10/11 or macOS
- Git
- [Local by Flywheel](https://localwp.com/) (free)
- ~5 GB free disk space
- ~10 minutes of your time

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

3. **Install Local by Flywheel** if you don't have it, then create a new WordPress site.
   - Site name: `hull-homepage-renewal` (matches paths in `CLAUDE.md`)
   - Environment: Preferred (PHP 8.x, MySQL 8.x)

4. **Install the "All-in-One WP Migration" plugin** on that site (Plugins → Add New → search "All-in-One WP Migration" → Install → Activate).

5. **Import** the reassembled `hull-site.wpress` via **All-in-One WP Migration → Import → File**. Wait 5–15 min.

6. **Log in** with the admin credentials from the previous owner (see [Access and credentials](#access-and-credentials)).

7. **Re-save permalinks:** Settings → Permalinks → **Save Changes** (no changes needed — regenerates rewrite rules; without this `/column/*` and `/works/*` will 404).

8. **Verify** every top-level route renders:
   `/`, `/space/`, `/vision/` (or `/digital-signage/`), `/sign/`, `/reform-business/`, `/philosophy/`, `/company/`, `/column/`, `/works/`, `/archives/category/news/`.

If styles look off, **hard-refresh** (Ctrl+Shift+R). Lightning theme caches CSS via a hardcoded version number.

---

## Repository layout

```
hull-homepage-handover/
├── README.md                    ← you are here
├── CLAUDE.md                    project brief: goals, IA, principles, history (read first)
├── .gitignore
│
├── docs/                        operational documentation
│   ├── setup.md                 local setup procedure (companion to Quick start above)
│   ├── deployment.md            how to push the local site to hull-inc.jp
│   ├── architecture.md          IA tree, page inventory (post IDs), URL policy, CSS conventions
│   ├── file-naming-rules.md     English-only kebab-case naming, with rationale
│   └── decisions-log.md         D-001..D-009 — each design decision + its "why"
│
├── spec/                        original v3 planning documents (docx)
│   ├── hull-homepage-renewal-plan-v3.ja.docx
│   ├── hull-homepage-renewal-plan-v3.ko.docx
│   └── README.md                filename mapping + when to open which doc
│
├── theme-source/
│   ├── lightning-child/         mirror of the live child theme for edit-history tracking
│   └── README.md                editing workflow + parent theme note
│
├── assets/
│   ├── photos/                  28 pre-upload source photos (kebab-case)
│   │   └── legacy-green-logo/   22 files — older green logo + WP-generated sizes
│   ├── design-guidelines/       5 design reference mocks (never uploaded to the site)
│   └── README.md                filename mapping (Korean/Japanese → English)
│
└── site-backup/
    ├── parts/
    │   ├── hull-site.wpress.part.000..015  16 chunks, ~90 MB each
    │   └── SHA256SUMS                      per-part integrity checksums
    ├── wpress-original.sha256              reassembled-file checksum
    └── README.md                           restore procedure (verify → reassemble → import)
```

Everything the next developer needs is inside this folder. Nothing important lives outside it except **credentials** (delivered separately) and the **Local by Flywheel install** (created fresh on the new laptop).

---

## Development history

Full narrative in [CLAUDE.md](CLAUDE.md). Key milestones:

### The two prior states
- **`hull-column-dev`** (`C:\Users\sehyu\Local Sites\hull-column-dev`) — earlier column-only feature work. Frozen. Not the master workspace.
- **`hull-homepage-renewal`** (`C:\Users\sehyu\Local Sites\hull-homepage-renewal`) — the **master local site**. A 100% mirror of live `hull-inc.jp` as of the 2026-07-31 export, plus all renewal work since.

Both were merged forward: this handover carries **only the renewal workspace state**.

### Renewal milestones (chronological)

| When | Milestone |
|---|---|
| 2026-07-31 | Import of live `hull-inc.jp` into the renewal workspace (100% mirror baseline) |
| 2026-07-31 | Column CPT / taxonomy / 20 articles / thumbnails migrated in; `lightning-child` merged in |
| Early Aug | Menu IA rebuilt to match spec v3 (11 items across the finalized tree) |
| Early Aug | HULL VISION menu → single `/digital-signage/` link |
| Early Aug | HULL SPACE sub-pages (`/sign/`, `/reform-business/`) created |
| Early Aug | Column public URL restored to `/column/` (with_front=false); `/archives/column/` regression resolved |
| Early Aug | `会社情報` → `企業概要` / `企業理念` two-page consolidation completed (2961 + 3080, message page 3077 legacy) |
| Early Aug | Top-level menu placeholders wired to real page links (`/digital-signage/`, `/sign/`, `/archives/category/news/`, `/company/`) |
| 2026-08-17 | HULL SPACE label rename: `サイン事業` / `空間施工事業` (URLs unchanged, labels updated on H1/menu/AIOSEO) |
| 2026-08-17 | Footer widget bug fix: HULL SPACE row was linking `/sign/` with wrong JP label → now `/space/`, JP label removed |
| 2026-08-17 | HULL SPACE parent landing (post 7005, `/space/`) full visual redesign — hero, about, works, strengths, CTA; CSS all scoped `body.page-id-7005` |
| 2026-08-24 | T3 v2 in-page redesign for `/sign/` (3094) and `/reform-business/` (6834); global components `sec-head__ja` / `sec-head__en` / `num-list__num` landed with `!important` to defeat Lightning legacy |
| 2026-08-26 | `/works/` page (post 7213) created |
| 2026-08-27 | This handover package built (12-phase build documented via git log) |

### Handover package build history (this repo)

Every commit is prefixed `Phase N:`. Walk `git log --oneline` for the sequence:

| Phase | What was added |
|---|---|
| 1 | Repo scaffold (.gitignore, README skeleton, CLAUDE.md) |
| 2 | `docs/` skeleton (5 files) |
| 3 | `spec/` docx (JP + KO), renamed to kebab-case |
| 4 | `theme-source/lightning-child/` full copy |
| 5 | `assets/photos/` 28 + `legacy-green-logo/` 22 + `design-guidelines/` 5, all kebab-cased |
| 6 | (Local Sites work — fresh `.wpress` export; no commit) |
| 7+9 | TOP hero URL normalized to English + fresh `.wpress` reflecting that state + split into 16 parts (committed as 1/3 docs, 2/3 parts 000-007, 3/3 parts 008-015 to keep individual pushes under ~720 MB after a prior 1.4 GB push hit HTTP 408) |
| 8 | This README |
| 10 | End-to-end restore verification |
| 11 | USB handover .zip build |
| 12 | Final push confirmation |

---

## What's left to do

### Explicitly out of scope for this handover, but tracked for the next owner

- **TOP page (post 2659, `/`) HULL Space card link mismatch** — card title is `空間施工事業` but the link points to `/sign/` (a `サイン事業` page). Same bug shape as the footer fix from 2026-08-17, but TOP page redesign was out of the prior scope. Recorded 2026-08-17, still pending. See `CLAUDE.md` §5.
- **Legacy page cleanup** — `旧会社概要` (slug: `about`, private), `/services_old`, `/signdisplay` etc. should be tidied. 301 redirects need to be added for any URL that gets deprecated.
- **Message page (`/message/`, post 3077)** — content already migrated into `/philosophy/`, but the page itself is still `publish`. Duplicate content, not linked from any menu. Decision on delete/private/redirect is pending — flagged as a destructive action that needs owner approval before executing.
- **HULL VISION landing (`/digital-signage/`)** — internal navigation to DokoDemo signage / TopBoard signage sub-pages was explicitly deferred by the user ("나중에"). Structural change to that page, not a bugfix.
- **Reform page real content (PDF-based layout)** — deferrable to post-launch. Yoshizawa-san can edit copy/images from wp-admin.

### Post-launch, but before public announcement

- **Full pre-cutover backup** of the live site (see `docs/deployment.md` step 1).
- **Search-replace of any surviving local URLs** (`hull-homepage-renewal.local`) after import to live — plugin: Better Search Replace, or ai1wm's built-in.
- **Cache/CDN flush** across whatever the live server uses.
- **Real-device mobile check** (not just responsive mode in a browser).

### Deferrable, low priority

- **Image optimization plugin** (Smush or similar) — recommended before launch, not strictly required.
- **Consolidate unused themes** — the local `wp-content/themes/` still ships with `affinger5×3`, `cocoon×2`, several `twentytwenty*`, etc. Kept intact per the delete-scope memory ("never touch WP files under `Local Sites/`"). Consider pruning after cutover if disk becomes tight.

---

## Working principles

Full list in [CLAUDE.md](CLAUDE.md) §6-§7. The load-bearing ones:

1. **Method approval before deviating.** If the instructed method is blocked, report first — do not switch on your own, especially not to something flagged as risky. (This rule exists because of a past incident.)
2. **"Investigation only" means don't execute.** Content merges and structural edits are separate steps from research.
3. **Ground truth is the live DB, not backups.** Any backup zip/json in this repo is a moment-in-time snapshot. For "what does the site actually look like right now", query via WP-CLI.
4. **Verify environment-specific configs.** `wpcli-php.ini` / port numbers / DB names are per-site; do not copy across without checking. (Past incident: wrong port pointed at a different site's DB.)
5. **Destructive actions require prior report.** Deletes, mass updates, DB schema changes.
6. **Reports frame status against the confirmed spec, not "what I did".** For each item: complete / partial / not done, verified by hitting the actual URL.
7. **Never delete pages.** Set to `private` instead. Recoverability is the reason.

---

## Where to look next

| Question | File |
|---|---|
| "What is this project? What's the goal?" | [CLAUDE.md](CLAUDE.md) |
| "How do I set this up locally?" | Quick start above → [docs/setup.md](docs/setup.md) → [site-backup/README.md](site-backup/README.md) |
| "How does the site get deployed to hull-inc.jp?" | [docs/deployment.md](docs/deployment.md) |
| "What are the pages? What are the post IDs?" | [docs/architecture.md](docs/architecture.md) |
| "Why is this like this?" (any specific decision) | [docs/decisions-log.md](docs/decisions-log.md) |
| "How do I name a new file?" | [docs/file-naming-rules.md](docs/file-naming-rules.md) |
| "Where are the source photos I might need?" | [assets/README.md](assets/README.md) |
| "What was the original plan (v3)?" | [spec/README.md](spec/README.md) → the two docx files |
| "Where is the child theme?" | [theme-source/README.md](theme-source/README.md) (mirror) + `~/Local Sites/.../lightning-child/` (actual) |

---

## Access and credentials

**None of the following live in this repo.** All are delivered through a separate secure channel by the previous owner:

- WordPress admin username & password (both `hull-inc.jp` live and any test accounts)
- Any hosting-panel access if it exists
- GitHub personal access token, if the outgoing developer's credential is being retired
- Domain registrar login (only if the handover includes DNS ownership — usually stays with the internal team)
- Yoshizawa-san's contact for structural questions

If a credential you need is missing from the handover packet, contact the previous owner first, then the internal team (Yoshizawa-san).

---

## Housekeeping notes for the next owner

- **This repo will drift** every time the site changes and a new `.wpress` is exported. When you re-export, replace `site-backup/parts/*`, regenerate `SHA256SUMS`, update `wpress-original.sha256`, and bump the "Backup metadata" table in `site-backup/README.md`.
- **Child theme edits** live at `~/Local Sites/hull-homepage-renewal/app/public/wp-content/themes/lightning-child/`. After you edit a file there, copy it back into `theme-source/lightning-child/` in this repo and commit — that keeps git history honest.
- **Global page CSS** is not on disk — it lives in Additional CSS (post 2641), edited via wp-admin. See [docs/architecture.md](docs/architecture.md) for the section-marker convention (`/* ===== [TOP] START/END ===== */`, etc.).
- **Push size** — 16 × 90 MB parts push cleanly if you split each push into 3 commits (docs + parts 000-007 + parts 008-015). A single monolithic 1.4 GB push will time out (HTTP 408). This is what the "Phase 7+9 (1/3, 2/3, 3/3)" commits in the history look like.
