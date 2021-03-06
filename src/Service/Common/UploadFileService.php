<?php

namespace App\Service\Common;

use App\Infrastructure\Persistence\Common\CommonDBRepository;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use function App\Service\count;

class UploadFileService
{
    private CommonDBRepository $commonRepository;

    public function __construct(CommonDBRepository $commonRepository)
    {
        $this->commonRepository = $commonRepository;
    }

    public function loadFile($file): string
    {
        $response = '';
        $uploadedFile = $file['file'];

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {

            $filename = $uploadedFile->getClientFilename();

            $directory = '/game/uruk/app/uploads' . DIRECTORY_SEPARATOR . $filename;
            $uploadedFile->moveTo($directory);

            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $tableName = pathinfo($filename, PATHINFO_FILENAME);

            if ($extension == 'csv') {
                $response = $response . $tableName . '<br/>';
                $reader = new Csv();

                $spreadsheet = $reader->load($directory);
                $spreadData = $spreadsheet->getActiveSheet()->toArray();

                // connecting db
                $this->commonRepository->loadData($directory, $tableName);

            } else {
                $response = $response . '처리할 수 있는 포맷의 파일이 아닙니다.';
            }
        }
        return $response;
    }
}