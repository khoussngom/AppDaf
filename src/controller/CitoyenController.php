<?php
namespace App\Controller;

use App\Services\CitoyenService;

class CitoyenController
{
    private CitoyenService $citoyenService;

    public function __construct(CitoyenService $citoyenService)
    {
        $this->citoyenService = $citoyenService;
    }

    public function searchCitoyen()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type');
            http_response_code(200);
            exit;
        }

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['nci'])) {
            $nci = $_GET['nci'];
            $info = $this->citoyenService->findByNci($nci);

            if ($info !== null) {
                echo json_encode($info);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Citoyen non trouvé']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Requête invalide ou NCI manquant']);
        }
    }

}

