<?php

namespace App\Services;

class GeofencingService
{
   public function checkDistance($userLat, $userLon, $targetLat, $targetLon, $radius) {
        $earthRadius = 6371000;
        $dLat = deg2rad($targetLat - $userLat);
        $dLon = deg2rad($targetLon - $userLon);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($userLat)) * cos(deg2rad($targetLat)) *
             sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return [
            'is_within_range' => $distance <= $radius,
            'distance' => round($distance)
        ];
    }
}
