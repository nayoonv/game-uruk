<?php

namespace App\Service\UserEquip;

use App\Exception\Base\UrukException;
use App\Exception\Book\EquipNotExistsException;
use App\Exception\Equip\EquipDBException;
use App\Exception\Equip\UserEquipDBException;
use App\Infrastructure\Persistence\Equip\UserEquipDBRepository;
use App\Service\Equip\GetEquipService;

class AddUserEquipService
{
    private UserEquipDBRepository $userEquipDBRepository;
    private GetEquipService $getEquipService;

    public function __construct(UserEquipDBRepository $userEquipDBRepository, GetEquipService $getEquipService) {
        $this->userEquipDBRepository = $userEquipDBRepository;
        $this->getEquipService = $getEquipService;
    }

    public function addEquipUpgradeAvailable($userId, $equipId, $level, $equipType) {
        try {
            $preparationId = $this->getEquipService->getPreparation($equipId)->getPreparationId();
            switch($equipType) {
                case 1:
                    return $this->userEquipDBRepository->insertRod($userId, $equipId, $level, $preparationId);
                case 2:
                    return $this->userEquipDBRepository->insertLine($userId, $equipId, $level, $preparationId);
                case 3:
                    return $this->userEquipDBRepository->insertReel($userId, $equipId, $level, $preparationId);
            }
        } catch(UrukException $e) {
            throw $e;
        }
    }

    public function addEquipUpgradeUnavailable($userId, $equipId) {
        try {
            return $this->userEquipDBRepository->insertEquipUpgradeUnavailable($userId, $equipId);
        } catch (UrukException $e) {
            throw $e;
        }
    }
}