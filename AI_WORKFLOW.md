# AI Workflow Log

| Step | Goal | Prompt Strategy | Tool Used | Result | Adjustment |
|---|---|---|---|---|---|
| 1 | Fix broken column behavior | Locate where grid columns are controlled and why selector does not apply | Cursor Agent + code search | Found CSS enqueue path mismatch | Updated enqueue path to load actual stylesheet |
| 2 | Make content client-editable | Convert hardcoded cards into Elementor controls | Cursor Agent iterative prompts | Added repeater-based plan controls initially | Later aligned with trial requirement to prioritize JSON endpoint controls |
| 3 | Build trial-required architecture | Add endpoint, caching, popular index, currency, title toggle controls | Cursor Agent refactor prompts | Implemented dedicated data service + widget integration | Split fetch/normalize logic into `DWL_Pricing_Data` for testability |
| 4 | Security hardening | Ensure sanitized inputs and escaped outputs throughout render flow | Cursor Agent security checklist prompt | Added `esc_html`, `esc_attr`, `esc_url`, sanitization pipeline | Introduced normalization helper with fallback sanitization for tests |
| 5 | Performance alignment | Reduce repeated API requests | Cursor Agent optimization prompt | Added transient caching with TTL control | Cache key uses endpoint + TTL for per-config safety |
| 6 | QA/test requirement | Add one automated test | Cursor Agent test generation prompt | Added PHPUnit test for payload normalization | Added minimal bootstrap + phpunit config for local run |
| 7 | Documentation | Create submission-ready docs | Cursor Agent document drafting prompt | Added `README.md`, `AI_WORKFLOW.md`, `PERFORMANCE.md` | Kept docs concise and checklist-oriented |

## What failed and how it was fixed

- Initial implementation focused too much on static/repeater content.  
  Fixed by re-centering on remote JSON-first architecture and trial rubric.
- CSS path mismatch prevented expected layout behavior.  
  Fixed by correcting stylesheet enqueue path.
- Direct testing against WP functions is difficult in isolated PHPUnit runs.  
  Fixed by adding safe fallback sanitization for non-WP test runtime.
