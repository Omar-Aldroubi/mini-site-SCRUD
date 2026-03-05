DROP TABLE IF EXISTS plats;

CREATE TABLE plats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,       
    description TEXT,                
    ingredients TEXT NOT NULL,      
    recette TEXT,                     
    type VARCHAR(50),                 
    region VARCHAR(100),              
    nom_image VARCHAR(255) NULL,      
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

