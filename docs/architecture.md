# Site Architecture

> Confirmed information architecture, page inventory, URL policy, and component conventions.

**Status:** Skeleton. Filled in by Phase 8. Canonical source is `CLAUDE.md` §3.

---

## Confirmed IA (frozen, per spec v3)

```
HULL
├── HULL VISION                        → /digital-signage/
├── HULL SPACE                         → /space/
│   ├── サイン事業                     → /sign/
│   └── 空間施工事業                   → /reform-business/
├── トピックス
│   ├── ニュース                       → /archives/category/news/
│   └── コラム                         → /column/
├── 会社情報
│   ├── 企業概要                       → /company/
│   └── 企業理念                       → /philosophy/
└── お問い合わせ
```

**Explicitly out of scope** (must not appear in the menu):
- 輸入雑貨販売・EC事業
- 実績紹介
- Q&A

Full context, rationale, and traps (e.g. the two meanings of "リフォーム") are in `CLAUDE.md` §3.

---

## Page inventory (post IDs)

| Page | Slug | Post ID |
|---|---|---|
| Top | `/` | 2659 |
| HULL SPACE | `/space/` | 7005 |
| サイン事業 | `/sign/` | 3094 |
| 空間施工事業 | `/reform-business/` | 6834 |
| HULL VISION | `/digital-signage/` | 3090 |
| 企業概要 | `/company/` | 2961 |
| 企業理念 | `/philosophy/` | 3080 |
| Message (legacy, unlinked) | `/message/` | 3077 |
| Works | `/works/` | 7213 |
| Footer widget block | — | 16 |
| Additional CSS (global) | — | 2641 |

---

## URL policy

- **Never change a live URL** without explicit approval — external SEO documents, manuals, and the frozen KR/JP guides all reference these paths
- Column archive is `/column/` (not `/archives/column/`). Enforced via `with_front => false` on the CPT rewrite
- Live's permalink structure is the source of truth; any local divergence must be reconciled to match live

---

## CSS conventions (Additional CSS, post 2641)

- Every non-global rule is scoped by `body.page-id-<N>` — no global side-effects
- Section markers used inside post 2641: `/* ===== [TOP] START/END ===== */`, `[SPACE]`, `[VISION]`, `[SIGN]`, `[REFORM]` — do not remove; imports rely on them
- Global components (v3): `.sec-head__ja`, `.sec-head__en`, `.num-list__num` — all with `!important` to override Lightning legacy

---

## Breakpoints (Lightning convention)

`600px` / `781px` / `782px` — mobile / tablet-max / desktop-min.

---

## Related

- `CLAUDE.md` — full narrative and history
- `docs/decisions-log.md` — why each choice was made
- `theme-source/lightning-child/style.css` — the child theme's own styles (separate from post 2641)
