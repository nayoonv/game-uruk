<?php

namespace App\Service\Weather;

use App\Domain\Weather\Weather;
use App\Exception\Base\UrukException;
use App\Exception\Weather\NotReadWeatherException;
use App\Exception\Weather\TideDBException;
use App\Exception\Weather\WeatherDBException;
use App\Infrastructure\Persistence\Weather\TideDBRepository;
use App\Infrastructure\Persistence\Weather\WeatherDBRepository;
use App\Util\SuccessResponseManager;

class ReadWeatherService
{
    private WeatherDBRepository $weatherDBRepository;
    private TideDBRepository $tideDBRepository;

    public function __construct(WeatherDBRepository $weatherDBRepository,TideDBRepository $tideDBRepository) {
        $this->weatherDBRepository = $weatherDBRepository;
        $this->tideDBRepository = $tideDBRepository;
    }
    public function getWeather($userId) {
        try {
            return SuccessResponseManager::response($this->getWeatherInfo($userId));
        } catch (UrukException $e) {
            return $e->response();
        }
    }

    public function getWeatherInfo($userId) {

        try {
            // 먼저 weather 검색
            $result = $this->weatherDBRepository->findWeatherByUserId($userId);

            // 주어진 날짜에 정보가 없으면 여기서 데이터를 만든다.
            if ($result == null || !$result) {
                // 온도 랜덤으로 지정, 풍향 랜덤으로 지정,
                $result = $this->makeWeatherInfo();
                // 데이터를 만들어서 insert한다.
                $this->weatherDBRepository->insertWeatherByUserId($userId, $result);
            }

            if (!$result)
                throw new NotReadWeatherException;

            return $result;
        } catch (UrukException $e) {
            throw $e;
        }
    }

    public function makeWeatherInfo() {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("Y-m-d H:i:s", time());
        $date = date("j");
        $time = date("G");

        $temperature = rand(0, 30);
        $windDirectionId = rand(1, 8);
        $windSpeed = rand(0, 20);
        try {
            $tideInfo = $this->tideDBRepository->findTideByDateAndTime($date, $time);
            return new Weather($datetime, $temperature, $windDirectionId, $windSpeed, $tideInfo->getTideTime(), $tideInfo->getTideType(), $tideInfo->getTidePower());
        } catch (UrukException $e) {
            throw $e;
        }
    }
}