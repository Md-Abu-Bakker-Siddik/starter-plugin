<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/class-pricing-data.php';

final class PricingDataTest extends TestCase {

    public function test_normalize_payload_extracts_valid_plans() {
        $payload = [
            'plans' => [
                [
                    'name' => 'Starter',
                    'price' => '29',
                    'features' => [ 'One website', 'Email support' ],
                ],
                'invalid-item',
                [
                    'name' => '',
                    'price' => '',
                ],
            ],
        ];

        $plans = DWL_Pricing_Data::normalize_payload( $payload );

        $this->assertCount( 1, $plans );
        $this->assertSame( 'Starter', $plans[0]['name'] );
        $this->assertSame( '29', $plans[0]['price'] );
        $this->assertSame( [ 'One website', 'Email support' ], $plans[0]['features'] );
    }
}
