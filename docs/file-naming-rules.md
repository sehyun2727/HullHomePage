# File Naming Rules

> All new files added to this repo follow these rules. Consistency is the whole point.

---

## Language

**English only.** No Korean, no Japanese, no CJK characters in filenames — CJK breaks WP-CLI, PHP `require`, some CI runners, and creates URL-encoding surprises.

The previous workspace (`hull-homepage/`) had Korean filenames (`기획서/`, `사진/`, `홈페이지 가이드라인/`) — these are deliberately renamed on the way into this repo.

---

## Case

**`kebab-case`** for everything (files, folders, image slugs).

```
✅ hull-space-hero.png
✅ deployment.md
✅ file-naming-rules.md
❌ HULLSpaceHero.png
❌ hull_space_hero.png
❌ HULL SPACE HERO.png
```

Exceptions:
- Markdown documents may use conventional casing for `README.md`, `CLAUDE.md`, `LICENSE`
- Language variants use dotted suffixes: `README.ja.md`, `plan.ko.docx`

---

## Image filenames

Pattern: `<subject>-<context>-<variant>.<ext>`

| Subject | Context | Variant |
|---|---|---|
| top, space, vision, sign, reform, works | hero, about, works, cta, footer | pc, sp, tb, 375, 768, 1024, 1440 |

Examples:
```
top-hero-pc.png
space-about-sp.png
reform-cta-1440.png
```

---

## Snapshot / backup files (kept out of this repo)

Any `backup_`, `dump_`, `_ss_`, `_shot_`, `_diag_`, `_verify_`, `_measure_`, `_crop_`, `_t2/t3/t4_`, `_v3/v6/v7_` etc. from the previous workspace **stays out of the repo**. Rationale:

- They're moment-in-time working snapshots, not source of truth
- Git already gives you snapshots (via commits)
- Reintroducing them defeats the point of cleaning up

If you truly need a working scratchpad, use a `scratch/` folder in your local checkout — it's in `.gitignore`.

---

## Document filenames

- Operational docs live in `docs/` and are lowercase kebab-case: `docs/setup.md`, `docs/deployment.md`
- Original planning documents (typically docx) live in `spec/` and preserve their versioning: `HULL_homepage_renewal_plan_v3.ja.docx`

---

## Commit messages

- Subject line ≤ 72 chars, imperative present tense: `Add lightning-child theme source`
- Prefix with phase when it's a build-package commit: `Phase 4: Import lightning-child theme source`
- Body explains **why**, not what

---

## Related

- `docs/decisions-log.md` — records specific naming decisions (e.g. why kebab-case over snake_case)
