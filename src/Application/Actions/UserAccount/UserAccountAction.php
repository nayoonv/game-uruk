<?php

namespace App\Application\Actions\UserAccount;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\UserAccount\UserAccountRepository;
use Psr\Log\LoggerInterface;

abstract class UserAccountAction extends Action
{
    protected UserAccountRepository $userAccountRepository;

    public function __construct(LoggerInterface $logger, UserAccountRepository $userAccountRepository) {
        parent::__construct($logger);
        $this->userAccountRepository = $userAccountRepository;
    }
}