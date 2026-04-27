<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class DWL_Pricing_Data {

    /**
     * Fetch plans from remote endpoint with transient caching.
     *
     * @param string $url JSON endpoint URL.
     * @param int    $ttl Cache TTL in seconds.
     * @return array<int, array<string, mixed>>
     */
    public static function get_plans( $url, $ttl = 300 ) {
        $url = esc_url_raw( (string) $url );
        $ttl = absint( $ttl );
        if ( $ttl < 60 ) {
            $ttl = 60;
        }

        $cache_key = 'dwl_pricing_' . md5( $url . '|' . $ttl );
        $cached = get_transient( $cache_key );
        if ( false !== $cached && is_array( $cached ) ) {
            return $cached;
        }

        $response = wp_remote_get(
            $url,
            [
                'timeout'   => 15,
                'sslverify' => true,
            ]
        );

        if ( is_wp_error( $response ) ) {
            return self::fallback_plans();
        }

        $body = wp_remote_retrieve_body( $response );
        $decoded = json_decode( $body, true );
        $plans = self::normalize_payload( $decoded );

        if ( empty( $plans ) ) {
            return self::fallback_plans();
        }

        set_transient( $cache_key, $plans, $ttl );
        return $plans;
    }

    /**
     * Normalize payload into plan list.
     *
     * @param mixed $decoded Decoded JSON payload.
     * @return array<int, array<string, mixed>>
     */
    public static function normalize_payload( $decoded ) {
        if ( ! is_array( $decoded ) ) {
            return [];
        }

        $raw = [];
        if ( isset( $decoded['plans'] ) && is_array( $decoded['plans'] ) ) {
            $raw = $decoded['plans'];
        } elseif ( isset( $decoded['record'] ) && is_array( $decoded['record'] ) ) {
            $raw = isset( $decoded['record']['plans'] ) && is_array( $decoded['record']['plans'] ) ? $decoded['record']['plans'] : $decoded['record'];
        } else {
            $raw = $decoded;
        }

        if ( ! is_array( $raw ) ) {
            return [];
        }

        $plans = [];
        foreach ( $raw as $plan ) {
            if ( ! is_array( $plan ) ) {
                continue;
            }

            $name = self::sanitize_text( isset( $plan['name'] ) ? $plan['name'] : '' );
            $price = self::sanitize_text( isset( $plan['price'] ) ? $plan['price'] : '' );

            $features = [];
            if ( isset( $plan['features'] ) && is_array( $plan['features'] ) ) {
                foreach ( $plan['features'] as $feature ) {
                    $feature_text = self::sanitize_text( $feature );
                    if ( '' !== $feature_text ) {
                        $features[] = $feature_text;
                    }
                }
            }

            if ( '' === $name && '' === $price ) {
                continue;
            }

            $plans[] = [
                'name'     => $name,
                'price'    => $price,
                'features' => $features,
            ];
        }

        return $plans;
    }

    /**
     * Fallback plans used when remote request fails.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function fallback_plans() {
        return [
            [
                'name'     => 'Starter',
                'price'    => '29',
                'features' => [ '1 Website', 'Basic Support' ],
            ],
            [
                'name'     => 'Professional',
                'price'    => '59',
                'features' => [ '10 Websites', 'Priority Support' ],
            ],
            [
                'name'     => 'Business',
                'price'    => '99',
                'features' => [ 'Unlimited Sites', '24/7 Support' ],
            ],
        ];
    }

    /**
     * Sanitize text in both WP and non-WP test runtimes.
     *
     * @param mixed $value Input value.
     * @return string
     */
    private static function sanitize_text( $value ) {
        $value = is_scalar( $value ) ? (string) $value : '';
        if ( function_exists( 'sanitize_text_field' ) ) {
            return sanitize_text_field( $value );
        }

        return trim( strip_tags( $value ) );
    }
}
