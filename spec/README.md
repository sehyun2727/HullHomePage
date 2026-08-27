# Project Spec

The frozen v3 renewal plan, in both languages. **This is the requirements document — do not modify.** Version bumps are new files (`-v4.*.docx`), not edits.

## Files

| File | Language | Original filename (previous workspace) |
|---|---|---|
| `hull-homepage-renewal-plan-v3.ja.docx` | Japanese | `HULL_ホームページリニューアル企画書_v3.docx` |
| `hull-homepage-renewal-plan-v3.ko.docx` | Korean | `HULL_홈페이지_리뉴얼_기획서_v3.docx` |

Both files are byte-equivalent to their originals; only the filename was normalized (CJK → English kebab-case, per `docs/file-naming-rules.md`).

## What v3 defines

- The IA tree (final, frozen — see `docs/architecture.md` and `CLAUDE.md` §3)
- Page-by-page content requirements
- Design direction and design guidelines (guideline reference images live at `assets/design-guidelines/`)
- Deployment target: 2026-08-20~25 for cutover, 2026-08-26 public launch

## If you're deciding whether to open this

- **You want the "why" behind a page's structure** → this doc
- **You want the current implementation status** → `CLAUDE.md`, `docs/decisions-log.md`
- **You want to know what changed since v3** → `docs/decisions-log.md`
