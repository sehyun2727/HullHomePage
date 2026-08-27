# HULL Homepage Renewal

Complete snapshot of the **HULL Corporation homepage renewal** (`hull-inc.jp`) as of **2026-08-27**, ready for a new developer to clone and continue.

For the project background, IA, and decision history, read **[CLAUDE.md](CLAUDE.md)**.

---

## What is in this repo

Everything you need to bring the site up locally in one place:

```
hull-homepage-handover/
├── site-backup/                complete WordPress site (DB + media + themes + plugins)
│   ├── parts/                  split into 16 × ~90 MB chunks so GitHub accepts them
│   │   ├── hull-site.wpress.part.000..015
│   │   └── SHA256SUMS
│   ├── wpress-original.sha256
│   └── README.md               reassemble + import procedure
├── theme-source/lightning-child/   the child theme, mirrored for edit-history tracking
├── assets/                     source photos, design guidelines, filename mapping
├── spec/                       original v3 plan documents (JA + KO docx)
├── docs/                       setup, deployment, architecture, decisions log
├── CLAUDE.md                   project brief (goals, IA, working history)
└── README.md                   you are here
```

The site itself is the `.wpress` file split under `site-backup/parts/`. Everything else is context, source, or reference material.

---

## How the site was developed

- **Local dev environment:** [Local by Flywheel](https://localwp.com/) running a site named `hull-homepage-renewal.local` at `~/Local Sites/hull-homepage-renewal/`
- **Base:** The 2026-07-31 export of live `hull-inc.jp`, imported as the starting point. All renewal work happened on top of that mirror.
- **Theme:** Lightning (parent, `15.20.2`) + `lightning-child` (child, our code)
- **Where site-wide CSS lives:** In **Additional CSS (post 2641)** in the WP database, scoped per page via `body.page-id-<N>`. Not on disk. Edited through wp-admin.
- **Where template code lives:** In the child theme at `wp-content/themes/lightning-child/` — column CPT templates, works CPT templates, taxonomy templates, `functions.php`, `style.css`.
- **How changes were verified:** `wp-cli` via `wp.sh` (bash wrapper around Local's PHP + WP-CLI) to query the live local DB, plus browser inspection of the local site.
- **How the handover was packaged:** The whole site was exported as one `.wpress` via **All-in-One WP Migration** (~1.5 GB), then `split` into 90 MB chunks so GitHub would accept them (100 MB per-file limit), and committed in 3 pieces (docs, first 8 parts, last 8 parts) because a single 1.4 GB push times out.

---

## Clone → running site (~10 minutes)

### 1. Clone this repo
```bash
git clone https://github.com/sehyun2727/HullHomePage.git hull-homepage-handover
cd hull-homepage-handover
```

### 2. Reassemble the `.wpress` from the split parts

**macOS / Linux / Git Bash / WSL:**
```bash
cd site-backup
cat parts/hull-site.wpress.part.* > hull-site.wpress
sha256sum -c wpress-original.sha256   # must print "OK"
```

**Windows PowerShell / cmd:** see the full procedure in [`site-backup/README.md`](site-backup/README.md).

If SHA verification fails on any part, re-run `git pull`. If a single reassembly attempt keeps failing, the USB handover `.zip` (if delivered) contains the un-split `hull-site.wpress` as insurance.

### 3. Install Local by Flywheel and create a new WP site
- Download: https://localwp.com/ (free)
- Site name: `hull-homepage-renewal` (matches paths used throughout the docs)
- Environment: Preferred (PHP 8.x, MySQL 8.x)

### 4. Install the All-in-One WP Migration plugin on that site
Open the site in Local → click "WP Admin" → Plugins → Add New → search "All-in-One WP Migration" → Install → Activate.

### 5. Import the reassembled `.wpress`
WP Admin → **All-in-One WP Migration → Import → File** → select `hull-site.wpress`. Takes 5–15 min depending on machine (file is ~1.5 GB).

### 6. Log in with the credentials from the previous owner
Credentials are delivered separately, not in this repo. If missing, contact the previous owner.

### 7. Re-save permalinks
Settings → Permalinks → **Save Changes** (no changes needed — this regenerates rewrite rules). **Without this step `/column/*` and `/works/*` return 404.**

### 8. Verify each route renders
`/`, `/space/`, `/vision/` (or `/digital-signage/`), `/sign/`, `/reform-business/`, `/philosophy/`, `/company/`, `/column/`, `/works/`, `/archives/category/news/`

Hard-refresh (Ctrl+Shift+R) if styles look off — Lightning caches CSS aggressively.

---

## Where you'll actually be working

Once imported, the site lives here on your machine:

```
~/Local Sites/hull-homepage-renewal/app/public/
├── wp-content/
│   ├── themes/
│   │   ├── lightning/              parent theme (do not edit)
│   │   └── lightning-child/        ← child theme — edit these files
│   ├── plugins/
│   └── uploads/                    media library
├── wp-config.php
└── ...
```

The **child theme** at `wp-content/themes/lightning-child/` is where template and function code lives. When you change a file there:

1. Edit and verify on the local site
2. **Copy the changed file back into `theme-source/lightning-child/` in this repo**
3. Commit — that keeps this repo's history honest

**Site-wide page CSS is NOT in the child theme.** It lives in the WP database as Additional CSS (post 2641) and is edited via **wp-admin → Appearance → Customize → Additional CSS**. Section markers like `/* ===== [TOP] START/END ===== */`, `[SPACE]`, `[VISION]` divide it into per-page blocks — do not delete these markers.

The Local site's WP-CLI wrapper is at `~/Local Sites/hull-homepage-renewal/wp.sh`; invoke as `bash wp.sh <wp-cli command>`.

---

## When you re-export the site backup

Once the local site drifts from what's in this repo (you edit content, upload images, tweak CSS), refresh the site backup:

1. **Export** — WP Admin → All-in-One WP Migration → Export → File. Saves a new `.wpress` under `wp-content/ai1wm-backups/`.
2. **Copy the new `.wpress`** into this repo as `hull-site.wpress` (root of the handover folder, ignored by `.gitignore`).
3. **Delete the old parts** in `site-backup/parts/`, then re-split:
   ```bash
   split -b 90M -d --suffix-length=3 hull-site.wpress site-backup/parts/hull-site.wpress.part.
   ```
4. **Regenerate checksums:**
   ```bash
   cd site-backup/parts && sha256sum hull-site.wpress.part.* > SHA256SUMS
   cd .. && sha256sum ../hull-site.wpress > wpress-original.sha256
   ```
5. **Update `site-backup/README.md`** — bump the "Backup metadata" table (SHA, export date, source filename).
6. **Commit in 3 pieces** — docs + parts 000-007 + parts 008-015. Single monolithic push (>1 GB) times out with HTTP 408.

---

## Deploying to production (`hull-inc.jp`)

Full procedure in [`docs/deployment.md`](docs/deployment.md). One-line summary:

Export the local site as a `.wpress`, log into `hull-inc.jp/wp-admin`, use All-in-One WP Migration to import it over live, then re-save permalinks and verify. Live has no SSH/FTP — the plugin path is the only channel.

**Before cutting over, back up live first** (`docs/deployment.md` step 1). Rollback = re-importing that backup.

---

## Credentials

Not in this repo. Delivered separately by the previous owner. Includes: WP admin (live + local), any hosting-panel access, GitHub token if the outgoing developer's is being retired, Yoshizawa-san's contact for structural questions.
