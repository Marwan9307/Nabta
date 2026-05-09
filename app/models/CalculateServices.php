<?php

class CalculateServices {
    
    public function calculateTrustScore($user_Registered_ID) {
        $score = mt_rand(10, 50) / 10; // Dummy calculation out of 5
        return $score;
    }

    public function calculateEcoPoints($user_Registered_ID) {
        return mt_rand(100, 1000); // Dummy points
    }

    public function calculateCO2Equation($transaction_ID) {
        // Logic reading transaction item weight and returning float
        return 12.5; // Dummy float
    }

    public function calculateWaterEquTransaction($transaction_ID) {
        return 45.2; // Dummy float
    }

    public function calculatePlatformFees($transaction_ID) {
        return 2; // Dummy int
    }

    public function calculateShippingFees($transaction_ID) {
        return 5; // Dummy int
    }

    public function calculateBundleDiscount($transaction_ID) {
        return 10; // Dummy int percentage
    }

    public function calculateTotal($transaction_ID) {
        return 150; // Dummy int total
    }
}
