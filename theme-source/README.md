# theme-source/lightning-child

Mirror of the child theme running on the live local site. **This copy exists for edit-history tracking, not for direct deployment.**

The actual site loads its theme from `wp-content/themes/lightning-child/` inside the Local by Flywheel install. This folder is a snapshot for git-diffing changes over time.

---

## Where the real thing lives

```
~/Local Sites/hull-homepage-renewal/app/public/wp-content/themes/lightning-child/
```

Any edit made in Local is done there. When a phase captures a fresh `.wpress` (Phase 6, Phase 9), this snapshot should be updated to match — otherwise the git history and the deployed theme drift apart.

---

## Theme structure

```
lightning-child/
├── archive-column.php              /column/ archive template
├── archive-works.php               /works/ archive template
├── single-column.php               individual column article
├── single-works.php                individual works entry
├── taxonomy-column_category.php    /column/category/{slug}/
├── taxonomy-column_tag.php         /column/tag/{slug}/
├── taxonomy-works_category.php     /works/category/{slug}/
├── taxonomy-works_tag.php          /works/tag/{slug}/
├── functions.php                   CPT/taxonomy registration, ACF wiring, helpers
├── style.css                       theme header + child overrides (~1000 lines)
├── screenshot.jpg                  theme thumbnail shown in wp-admin
├── template-parts/                 partials pulled into templates
├── assets/                         JS (column-search, column-tag-float) + local CSS
└── images/                         theme-owned images (e.g. column-archive-hero.jpg)
```

Global site-wide styles for pages live in **Additional CSS (post 2641)**, not in `style.css`. See `docs/architecture.md`.

---

## Editing workflow

1. Edit the file in `~/Local Sites/hull-homepage-renewal/.../lightning-child/<file>`
2. Verify on the local site (hard-refresh)
3. Copy the changed file back into `theme-source/lightning-child/<file>` in this repo
4. Commit — the diff shows exactly what changed
5. When you're ready to deploy: re-export `.wpress` (Phase 6/9 procedure) → the new theme travels inside the wpress along with everything else

---

## What is NOT in this folder

- Parent theme (`lightning/`) — track it via version, not source. Current: **15.20.2**
- Other unused themes bundled in the Local install (`affinger5`, `cocoon-*`, `twentytwenty*`, etc.) — pruned before the wpress export in Phase 6

---

## Related

- `docs/architecture.md` — how templates and pages relate
- `docs/decisions-log.md` — theme-related decisions
- `CLAUDE.md` §3 — IA the templates render
