<?php

namespace App\Domain\UserAccount;

use JsonSerializable;

class UserAccount implements JsonSerializable
{
    private int $hiveId;

    private int $nationId;

    private int $languageId;

    private int $userId;

    public function __construct($hiveId, $nationId, $languageId, $userId) {
        $this->hiveId = $hiveId;
        $this->nationId = $nationId;
        $this->languageId = $languageId;
        $this->userId = $userId;
    }

    public function jsonSerialize(): array
    {
        return [
            'hive_id' => $this->hiveId,
            'nation_id' => $this->nationId,
            'language_id' => $this->languageId,
            'user_id' => $this->userId
        ];
    }
}