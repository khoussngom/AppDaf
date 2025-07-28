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
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['nci'])) {
            $nci = $_GET['nci'];
            $info = $this->citoyenService->findByNci($nci);

            header('Content-Type: application/json');

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
