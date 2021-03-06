<?php

namespace App\Infrastructure\Persistence\Book;

use App\Domain\Book\BookPrize;
use App\Exception\Book\BookPrizeDBException;
use App\Exception\Book\BookPrizeNotExistsBetweenCountException;
use App\Exception\Book\BookPrizeNotExistsException;
use App\Infrastructure\Persistence\Base\BaseDBRepository;
use PDOException;

class BookPrizeDBRepository extends BaseDBRepository
{
    public function findByBookPrizeId($bookPrizeId) {
        $query = "select * from book_prize where book_prize_id = :book_prize_id";
        $result = false;

        try {
            $sth = $this->db->prepare($query);
            $sth->bindParam(':book_prize_id', $bookPrizeId);
            $sth->execute();

            $result = $sth->fetch();

            if($result) {
                $result = new BookPrize($result['book_prize_id'], $result['fish_count'], $result['asset_id'], $result['cost']);
            } else {
                throw new BookPrizeNotExistsException();
            }
        } catch(BookPrizeNotExistsException $e) {
            throw $e;
        } catch(PDOException $exception) {
            throw new BookPrizeDBException();
        }
        return $result;
    }

    public function findBetweenCount($previousCount, $currentCount) {
        $query = "select * from book_prize where fish_count between :previous_count and :current_count";
        $result = -1;

        try {
            $sth = $this->db->prepare($query);
            $sth->bindParam(':previous_count', $previousCount);
            $sth->bindParam(':current_count', $currentCount);
            $sth->execute();

            $bookPrizes = $sth->fetchAll();

            if($bookPrizes) {
                foreach($bookPrizes as &$bookPrize) {
                    $result = new BookPrize($bookPrize['book_prize_id'], $bookPrize['fish_count'], $bookPrize['asset_id'], $bookPrize['cost']);
                }
            }
        } catch(PDOException $exception) {
            throw new BookPrizeDBException();
        }
        return $result;
    }
}