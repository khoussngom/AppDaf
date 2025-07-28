<?php

namespace App\Entity;

use Exception;

class Citoyen
{
    private ?int $id = null;
    private string $prenom;
    private string $nom;
    private ?string $date_naissance = null;
    private ?string $adresse = null;
    private string $nci;
    private ?string $photo = null;
    private ?string $created_at = null;
    private ?string $updated_at = null;

    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new Exception("La propriété '$name' n'existe pas dans la classe " . __CLASS__);
    }

    public function __set(string $name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new Exception("La propriété '$name' n'existe pas dans la classe " . __CLASS__);
        }
    }
}
