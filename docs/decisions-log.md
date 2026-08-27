# Decisions Log

> Why each non-obvious choice was made. Read this before rethinking any of them.

**Status:** Skeleton. Grows as decisions accumulate.

---

## D-001. IA is frozen per spec v3

- **What:** The IA tree in `docs/architecture.md` and `CLAUDE.md` §3 is not open to revision without spec-holder approval
- **Why:** Multiple SEO docs, printed manuals, and external references depend on it
- **Consequence:** Menu additions/renames require going back to the spec, not just editing wp-admin

---

## D-002. Column URL stays `/column/`, not `/archives/column/`

- **What:** `with_front => false` on the `column` CPT rewrite
- **Why:** Live permalink is `/column/`; frozen manuals (JP/KR) and SEO strategy docs all reference this URL
- **Consequence:** Never "match the site to the code"; match the code to the site

---

## D-003. HULL SPACE (`/space/`) redesigned 2026-08-17

- **Scope:** `/space/` only. `/sign/`, `/reform-business/`, and TOP are separately scoped
- **Why:** SPACE landing was designed as parent-hub; children come later
- **Consequence:** CSS lives under `body.page-id-7005` scope in Additional CSS (post 2641); do not lift these rules to global

---

## D-004. `/sign/` and `/reform-business/` in-page redesign (T3, 2026-08-24)

- **Scope IN** as of 2026-08-24 (T3 v2 directive)
- Global components (`sec-head__ja`, `sec-head__en`, `num-list__num`) landed with `!important` to defeat Lightning legacy overrides — see `docs/architecture.md`

---

## D-005. TOP page redesign is out of scope

- Except for the hero image swap in Phase 9 of this handover build
- Any TOP-page structural work is a separate project

---

## D-006. Whole-site replacement as the deploy model

- Not diff-based, not partial. The local site is exported as one `.wpress` and imported over live
- **Why:** Live only has wp-admin (no SSH/FTP). The plugin path is the only practical channel for a change this large
- **Consequence:** Local must remain a faithful mirror of live, always

---

## D-007. Never delete pages; unpublish (`private`) instead

- Legacy pages (`旧会社概要`, `/services_old`, `/signdisplay`, `/message/`) get their status changed, not their row removed
- **Why:** Recoverability. Also, some carry historical inbound links
- **Consequence:** DB may look cluttered but is reversible

---

## D-008. Naming: `kebab-case`, English only

- **Why:** WP-CLI, PHP `require`, and CI runners choke on CJK filenames
- The previous workspace's Korean filenames are renamed on entry to this repo
- Full rules: `docs/file-naming-rules.md`

---

## D-009. Handover distribution: split `.wpress` in Git + one un-split copy on USB

- **Why:** GitHub file limit is 100MB; `.wpress` is >1GB. Splitting into ~90MB parts keeps a single-clone experience while staying free (no LFS). USB carries the un-split original as insurance against split-reassembly bugs
- Trade-off documented in `README.md` and `site-backup/README.md`

---

## Traps carried over from `CLAUDE.md`

- **`position: sticky` does not work** on this site — parent Lightning theme sets `html { overflow-x: hidden }`. Use `position: fixed` for floating UI
- **Two things called "リフォーム"** exist and are different: the column category `reform` (5 articles) vs. the SPACE sub-page `/reform-business/`
- **Backup files older than 2026-07-31** are pre-import snapshots and must not be used as ground truth. Always query the live DB via WP-CLI
