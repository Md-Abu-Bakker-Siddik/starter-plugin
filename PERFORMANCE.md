# Performance Notes

## Baseline

- Initial widget render depended on static output and had no remote data caching.
- Potential repeated remote requests per page load for dynamic endpoints.

## Optimizations Implemented

1. Added transient caching in `DWL_Pricing_Data::get_plans()`.
2. Exposed cache duration (`cache_ttl`) in Elementor controls.
3. Kept CSS lightweight and enqueued as a single file.
4. Avoided render-blocking scripts (no frontend JS dependency).
5. Added fallback plans for resilient rendering when API fails.

## Caching Strategy

- Cache key: `dwl_pricing_` + `md5(endpoint|ttl)`
- Cache storage: WordPress transients
- Minimum TTL: 60 seconds
- Benefit: repeated views reuse cached normalized plan data

## Accessibility & UX Fixes

- Added focus-visible state for button keyboard navigation.
- Improved semantic structure by rendering each card as an `article`.
- Added button `aria-label` including selected plan name.

## Lighthouse / GTmetrix Logging Template

Use this section to record local measurements:

- URL tested:
- Device/profile:
- Before: Performance __ / Accessibility __
- After: Performance __ / Accessibility __
- Key improvements observed:
