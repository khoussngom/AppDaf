<?php
namespace App\Repository;

use Reflection;
use \ReflectionClass;
use App\Entity\Citoyen;
use App\Repository\BaseRepository;

class CitoyenRepository extends BaseRepository
{
    private Citoyen $citoyen;
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

public function findByNci(string $nci): ?Citoyen
{
    $stmt = $this->pdo->prepare("SELECT * FROM citoyen WHERE nci = :nci");
    $stmt->execute(['nci' => $nci]);
    $data = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $data ? $this->reflect($data) : null;
}


    public function reflect(array $citoyen)
    {
        $refClass = new ReflectionClass(Citoyen::class);
        $instance = $refClass->newInstanceWithoutConstructor();

        foreach($citoyen as $key => $value)
        {
            if($refClass->hasProperty($key))
                
                {
                    $prop = $refClass->getProperty($key);
                    $prop->setAccessible(true);
                    $prop->setValue($instance, $value);
                }
        }
        return $instance;
    }
}
