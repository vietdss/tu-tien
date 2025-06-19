<?php

namespace App\Services;

class CultivationService{
    public function cultivate($userId, $cultivationId){
        return [
            'status' => 'success',
            'message' => 'Cultivation started successfully.',
            'user_id' => $userId,
            'cultivation_id' => $cultivationId,
        ];
    }
}