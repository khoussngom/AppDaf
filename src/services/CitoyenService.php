<?php

namespace App\Services;

use App\Repository\CitoyenRepository;

class CitoyenService
{
    private CitoyenRepository $repository;

    public function __construct(CitoyenRepository $repository)
    {
        $this->repository = $repository;
    }


    public function findByNci(string $nci): ?array
    {
        $citoyen = $this->repository->findByNci($nci);
        
        if ($citoyen === null) {
            return null;
        }
        
        return [
            'id' => $citoyen->id,
            'prenom' => $citoyen->prenom,
            'nom' => $citoyen->nom,
            'date_naissance' => $citoyen->date_naissance,
            'adresse' => $citoyen->adresse,
            'nci' => $citoyen->nci,
            'photo' => $citoyen->photo
        ];
    }
}
