<?php

namespace App\Service\Book;

use App\Infrastructure\Persistence\Book\UserBookDBRepository;

class UpdateUserBookService
{
    private UserBookDBRepository $userBookDBRepository;

    public function __construct(UserBookDBRepository $userBookDBRepository) {
        $this->userBookDBRepository = $userBookDBRepository;
    }

    public function addFish($userId, $fishes) {
        foreach($fishes as &$fish)
            $this->userBookDBRepository->insertFish($userId, $fish);
    }
}