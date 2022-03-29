<?php

namespace App\Service\Inventory;

use App\Exception\Fish\InvenInsertFishException;
use App\Exception\Store\InvenInsertEquipException;
use App\Infrastructure\Persistence\Inventory\InvenDBRepository;

class InsertInvenService
{
    private InvenDBRepository $invenDBRepository;

    public function __construct(InvenDBRepository $invenDBRepository) {
        $this->invenDBRepository = $invenDBRepository;
    }

    public function insertInvenFish($userFishId, $userId, $datetime) {
        try {
            $this->invenDBRepository->insertUserFish($userFishId, $userId, $datetime);
        } catch (InvenInsertFishException $e) {
            throw $e;
        }
    }
    public function insertInvenEquip($userEquipId, $userId) {
        try {
            return $this->invenDBRepository->insertUserEquip($userEquipId, $userId);
        } catch (InvenInsertEquipException $e) {
            throw $e;
        }
    }
}