# HULL Homepage Renewal — Handover Package

> **Status: WORK IN PROGRESS (Phase 1 of 12).** This README is a skeleton. It will be filled in as the handover package is built out. See the roadmap at the bottom.

This repository is a complete handover package for the **HULL Corporation homepage renewal** project (`hull-inc.jp`). One `git clone` + one archive restore should be enough to bring the entire site up on a fresh laptop and continue development.

---

## What is in this repo

```
hull-homepage-handover/
├── README.md                    ← you are here
├── CLAUDE.md                    ← full project brief (goals, scope, principles, history)
├── .gitignore
├── docs/                        ← operational docs (setup, deployment, architecture, decisions)
├── spec/                        ← original planning documents (docx)
├── theme-source/
│   └── lightning-child/         ← the child theme source, mirrored for edit-history tracking
├── assets/
│   ├── photos/                  ← original photo assets used on the live site
│   └── design-guidelines/       ← design-guideline reference images
└── site-backup/
    ├── parts/                   ← the live site exported as a .wpress, split into <100 MB chunks
    ├── SHA256SUMS               ← integrity checksums for the chunks
    └── README.md                ← reassemble + import procedure
```

Every folder above is populated over Phases 2–7. Until then, the folder is either empty or missing.

---

## Quick start (for the next developer)

> Complete instructions land in Phase 8. Until then, use this as a rough map.

1. **Clone this repo.**
2. **Install [Local by Flywheel](https://localwp.com/).**
3. **Reassemble the site backup** — follow `site-backup/README.md`.
4. **Import the reassembled `.wpress` into Local** via the *All-in-One WP Migration* plugin.
5. **Read `CLAUDE.md`** end-to-end. It is the single source of truth for scope, architecture, decisions, and traps.
6. **Read `docs/decisions-log.md`** for what was chosen and why.

---

## Where things live outside this repo

| Thing | Location | Notes |
|---|---|---|
| Live production site | `https://hull-inc.jp` | wp-admin only, no SSH/FTP |
| Local development site | `C:\Users\sehyu\Local Sites\hull-homepage-renewal\` | The master workspace. Full mirror of live as of 2026-07-31. |
| Credentials (WP admin, GitHub, DB) | **Not in this repo.** | Transmitted through a separate secure channel by the previous owner. |

---

## Development history (short version)

*To be written in Phase 8. Long version is already in `CLAUDE.md`.*

---

## Handover build roadmap

| Phase | Deliverable | Status |
|---:|---|:---:|
| 1 | Scaffold repo (`.gitignore`, README skeleton, `CLAUDE.md`) | 🔨 in progress |
| 2 | `docs/` skeleton (setup / deployment / architecture / naming / decisions) | ⏳ |
| 3 | Import project spec (`spec/*.docx`) | ⏳ |
| 4 | Import `lightning-child` theme source | ⏳ |
| 5 | Import source photos (kebab-case, only those actually used) | ⏳ |
| 6 | Prepare Local Sites + fresh `.wpress` export | ⏳ |
| 7 | Split `.wpress` + SHA256 + restore instructions | ⏳ |
| 8 | Fill in this README (full handover doc) | ⏳ |
| 9 | Replace TOP hero image + refresh site backup | ⏳ |
| 10 | Verify end-to-end restore on a fresh copy | ⏳ |
| 11 | Build USB handover `.zip` (repo + un-split `.wpress`) | ⏳ |
| 12 | Push to GitHub, confirm remote | ⏳ |

Each phase is one commit. Watch `git log --oneline` to see progress.
