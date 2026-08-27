# assets/

Source images kept **outside** the WordPress install. The site's own uploads live inside the `.wpress` backup (see `site-backup/`); the files here are the pre-upload originals or reference material that would otherwise be lost.

```
assets/
├── photos/                 pre-upload originals (used or once-used on the site)
│   └── legacy-green-logo/  older green logo + its WP-generated sizes
└── design-guidelines/      design reference/mock images (never uploaded to the site)
```

---

## `photos/` — source photos (28 files)

Filenames normalized to English kebab-case per `docs/file-naming-rules.md`.

| Repo filename | Original (CJK) | Notes |
|---|---|---|
| about-hull.png | about HULL.png | |
| construction.png | 시공.png | SPACE 4-step imagery |
| content-production.png | 콘텐츠 제작.png | |
| creation.png | 창조.png | |
| hull-logo-from-yoshizawa.jpg | HULL logo form yosizawa.jpg | Original logo from Yoshizawa |
| hull-logo-k90-w150.jpg | HULL_logo(k90)w150.jpg | 150px web logo, K=90 |
| hull-space-hero.png | HULL-SPACE-HERO.png | |
| hull-space.png | HULL SPACE.png | |
| hull-strength-01-understanding.png | (same) | SPACE strengths — 1 of 4 |
| hull-strength-02-proposal.png | (same) | SPACE strengths — 2 of 4 |
| hull-strength-03-realization.png | (same) | SPACE strengths — 3 of 4 |
| hull-strength-04-creation.png | (same) | SPACE strengths — 4 of 4 |
| hull-vision.png | HULL VISION (1).png | |
| japan-map.jpg | JapanMap_145901.jpg | |
| maintenance-operation.png | 유지운영.png | SPACE 4-step imagery |
| proposal.png | 제안.png | SPACE 4-step imagery |
| realization.png | 실현.png | SPACE 4-step imagery |
| reform-division-main.png | 리폼부서 메인사진.png | |
| sign-airport-public-facility.png | 空港・公共施設.png | SIGN category |
| sign-station-railway.png | 駅・鉄道施設.png | SIGN category |
| space-construction-hero.png | 공간시공 hero.png | |
| space-our-approach.png | (same) | |
| top-landing-hero.png | top 랜딩페이지 hero.png | Not matched in current uploads — possible TOP hero replacement candidate |
| top-news.jpg | TOP-News.jpg | |
| understanding.png | 이해.png | SPACE 4-step imagery |
| vision-proposal.png | 제안(vision).png | VISION variant |
| vision-signage-by-purpose.png | 用途・目的に合わせたデジタルサイネージ.png | |
| vision-understanding.png | 이해(vision).png | VISION variant |

### Note on the SPACE/VISION 4-step pairs
The photos were provided in two variants:
- **VISION variant** (labeled `(vision)` in original): `vision-understanding.png`, `vision-proposal.png`
- **SPACE variant** (unlabeled originally): `understanding.png`, `proposal.png`, `realization.png`, `creation.png`, `construction.png`, `maintenance-operation.png`, `content-production.png`

Which page each ends up on is decided in the CMS — treat these as raw assets, not final mappings.

---

## `photos/legacy-green-logo/` (22 files)

The **older green logo** that was replaced with the current one. Kept for historical reference in case rollback or brand-comparison is needed. Files include WP-generated size variants (`-150x150`, `-300x101`, `-768x259`, `-1024x346`, `-1536x518`, `-2048x819`).

---

## `design-guidelines/` — design reference images (5 files)

Design mocks / reference layouts from the planning phase. **Never uploaded to the site.** Kept so a new developer can see the intended design intent.

| Repo filename | Original |
|---|---|
| hull-top.jpg | HULL TOP.jpg |
| hull-space.jpg | HULLSPACE.jpg |
| hull-vision.jpg | HULLVISION.jpg |
| space-kukan.jpg | SPACE_KUKAN.jpg |
| space-sign.jpg | SPACE_SIGN.jpg |

---

## How to use these

- **Editing a live page and need the source PNG?** Check `photos/` first — the pre-upload master is usually higher quality than the auto-resized WP version
- **Adding a new page with existing brand imagery?** Reuse from `photos/`, don't re-shoot
- **Wondering what the site was supposed to look like?** Compare current page vs `design-guidelines/`
