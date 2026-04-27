# Dynamic Web Lab Pricing Widget

A lightweight custom Elementor widget that renders a dynamic pricing table from a public JSON endpoint.

## Features

- Dynamic pricing plans fetched from remote JSON
- Elementor controls for:
  - title toggle + table title
  - currency symbol
  - popular plan index
  - cache TTL
  - responsive columns + gap
- Transient-based caching for faster repeat loads
- Secure output escaping and sanitized data normalization
- Responsive and accessible frontend markup

## Local Installation

1. Copy this plugin folder into `wp-content/plugins/starter-plugin`.
2. Activate **Dynamic Web Lab Pricing Widget** from WordPress admin.
3. Open Elementor editor and drag **DWL Pricing Table** widget onto the page.
4. Set `JSON Endpoint URL` and configure controls from the widget panel.

## JSON Shape

The widget supports these payload styles:

```json
{
  "plans": [
    { "name": "Starter", "price": "29", "features": ["1 Website", "Basic Support"] }
  ]
}
```

or:

```json
{
  "record": {
    "plans": [
      { "name": "Starter", "price": "29", "features": ["1 Website", "Basic Support"] }
    ]
  }
}
```

## Testing

This repo includes one PHPUnit test for payload normalization:

- `tests/PricingDataTest.php`

Run:

```bash
phpunit
```

## Notes

- If remote JSON fails, widget uses safe fallback plans.
- Cache key includes endpoint + TTL to support different widget instances.
