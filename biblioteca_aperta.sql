CREATE DATABASE biblioteca_aperta; USE biblioteca_aperta;

CREATE TABLE categoria (
	id_categoria INT AUTO_INCREMENT PRIMARY KEY,
	nome_categoria VARCHAR(50) NOT NULL
);

CREATE TABLE libro (
    id_libro INT AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR(20) NOT NULL,
    titolo VARCHAR(100) NOT NULL,
    autore VARCHAR(100) NOT NULL,
    stato BOOLEAN DEFAULT TRUE, 
    id_categoria INT NOT NULL,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria) ON DELETE CASCADE
);

CREATE TABLE prestito (
    id_prestito INT AUTO_INCREMENT PRIMARY KEY,
    nome_utente VARCHAR(50) NOT NULL,
    codice_ritiro VARCHAR(10) NOT NULL,
    data_prestito TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_libro INT NOT NULL,
    FOREIGN KEY (id_libro) REFERENCES libro(id_libro) ON DELETE CASCADE
);