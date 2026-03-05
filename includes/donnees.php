<?php


class Plat {
    public ?int $id = null;
    public string $nom;
    public string $ingredients;
    public ?string $description = null;
    public ?string $recette = null;
    public ?string $type = null;
    public ?string $region = null;
    public ?string $nom_image = null; 
    public ?string $date_ajout = null;

    public function __construct(
        string $nom = '',
        string $ingredients = '',
        ?string $description = null,
        ?string $recette = null,
        ?string $type = null,
        ?string $region = null,
        ?string $nom_image = null,
        ?int $id = null 
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->ingredients = $ingredients;
        $this->description = $description;
        $this->recette = $recette;
        $this->type = $type;
        $this->region = $region;
        $this->nom_image = $nom_image;
    }


 
    public static function listerTous(PDO $pdo, string $orderBy = 'nom ASC'): array {
        $allowed_columns = ['id', 'nom', 'type', 'region', 'date_ajout'];
        $allowed_directions = ['ASC', 'DESC'];

        $column = 'nom';
        $direction = 'ASC';

        $parts = explode(' ', trim($orderBy));
        if (count($parts) === 2) {
            $potential_column = strtolower($parts[0]);
            $potential_direction = strtoupper($parts[1]);

            if (in_array($potential_column, $allowed_columns) && in_array($potential_direction, $allowed_directions)) {
                $column = $potential_column;
                $direction = $potential_direction;
            }
        } elseif (count($parts) === 1 && in_array(strtolower($parts[0]), $allowed_columns)) {
             $column = strtolower($parts[0]);
        }
       
        $sql = "SELECT * FROM plats ORDER BY `$column` $direction";

      
        try {
            $stmt = $pdo->query($sql); 

            return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);

        } catch (PDOException $e) {
            error_log("PDO query failed in listerTous: " . $e->getMessage());
            return []; 
        }
    } 


    
    public static function trouverParId(PDO $pdo, int $id): Plat|false {
         if ($id <= 0) {
            error_log("Tentative de trouver un plat avec ID invalide: $id");
            return false;
        }
        
        $sql = "SELECT * FROM plats WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        if (!$stmt) {
            error_log("PDO prepare failed in trouverParId: " . implode(' - ', $pdo->errorInfo()));
            return false;
        }

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        $execute_success = $stmt->execute();

        if (!$execute_success) {
             error_log("PDO execute failed in trouverParId for ID $id: " . implode(' - ', $stmt->errorInfo())); 
             return false;
        }

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);

        $result = $stmt->fetch(); 
        return $result; 
    }

    
    public static function supprimerParId(PDO $pdo, int $id): bool
    {
        if ($id <= 0) {
             error_log("Tentative de suppression d'un plat avec ID invalide: $id");
            return false;
        }

        $sql = "DELETE FROM plats WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        if (!$stmt) {
             error_log("PDO prepare failed in supprimerParId: " . implode(' - ', $pdo->errorInfo()));
             return false;
        }

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $succes = $stmt->execute();

         if (!$succes) {
             error_log("PDO execute failed in supprimerParId for ID {$id}: " . implode(' - ', $stmt->errorInfo()));
             return false; 
        }
        return $stmt->rowCount() > 0;
    }



    
    public function enregistrer(PDO $pdo): bool
    {
        $sql = "INSERT INTO plats (nom, ingredients, description, recette, type, region, nom_image)
                VALUES (:nom, :ingredients, :description, :recette, :type, :region, :nom_image)";
        $stmt = $pdo->prepare($sql);
        if (!$stmt) {
             error_log("PDO prepare failed in enregistrer: " . implode(' - ', $pdo->errorInfo()));
             return false;
        }

        $params = [
            ':nom' => $this->nom,
            ':ingredients' => $this->ingredients,
            ':description' => $this->description,
            ':recette' => $this->recette,
            ':type' => $this->type,
            ':region' => $this->region,
            ':nom_image' => $this->nom_image
        ];
        $succes = $stmt->execute($params);
        if ($succes) {
            $this->id = (int)$pdo->lastInsertId();
        } else {
            error_log("PDO execute failed in enregistrer: " . implode(' - ', $stmt->errorInfo()));
        }
        return $succes;
    }

     
    public function modifier(PDO $pdo): bool
    {
        if (!$this->id) {
            error_log("Tentative de modification d'un plat sans ID.");
            return false;
        }

        $sql = "UPDATE plats SET
                    nom = :nom, ingredients = :ingredients, description = :description,
                    recette = :recette, type = :type, region = :region, nom_image = :nom_image
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
         if (!$stmt) {
             error_log("PDO prepare failed in modifier: " . implode(' - ', $pdo->errorInfo()));
             return false;
        }

        $params = [
            ':nom' => $this->nom,
            ':ingredients' => $this->ingredients,
            ':description' => $this->description,
            ':recette' => $this->recette,
            ':type' => $this->type,
            ':region' => $this->region,
            ':nom_image' => $this->nom_image,
            ':id' => $this->id
        ];

        $succes = $stmt->execute($params);
        if (!$succes) {
             error_log("PDO execute failed in modifier for ID {$this->id}: " . implode(' - ', $stmt->errorInfo()));
        }
   
        return $succes;
    }

    
    public function __toString(): string
    {
         if ($this->id !== null) {
             return "Plat #{$this->id}: {$this->nom}";
         } else {
             return "Plat (non enregistré): {$this->nom}";
         }
    }

} 
?>
