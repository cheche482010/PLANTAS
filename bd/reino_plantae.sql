CREATE TABLE plantas (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    nombre_comun TEXT,
    nombre_cientifico TEXT,
    descripcion TEXT
);
CREATE TABLE caracteristicas (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    nombre TEXT
);
CREATE TABLE relaciones (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    planta_id INTEGER,
    caracteristica_id INTEGER,
    FOREIGN KEY(planta_id) REFERENCES plantas(id),
    FOREIGN KEY(caracteristica_id) REFERENCES caracteristicas(id)
);
CREATE TABLE habitats (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    nombre TEXT,
    descripcion TEXT
);
CREATE TABLE plantas_habitats (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    planta_id INTEGER,
    habitat_id INTEGER,
    FOREIGN KEY(planta_id) REFERENCES plantas(id),
    FOREIGN KEY(habitat_id) REFERENCES habitats(id)
);
-- plantas
INSERT INTO plantas (nombre_comun, nombre_cientifico, descripcion) VALUES ('Orquídea', 'Orchidaceae', 'Familia de plantas con flores muy diversas y ampliamente distribuidas en todo el mundo');
INSERT INTO plantas (nombre_comun, nombre_cientifico, descripcion) VALUES ('Rosa', 'Rosa spp.', 'Género de plantas con flores que se cultivan en todo el mundo por su belleza y aroma');
INSERT INTO plantas (nombre_comun, nombre_cientifico, descripcion) VALUES ('Aloe vera', 'Aloe vera', 'Planta suculenta originaria de África y Arabia que se utiliza en la medicina natural y la cosmética');
INSERT INTO plantas (nombre_comun, nombre_cientifico, descripcion) VALUES ('Tomate', 'Solanum lycopersicum', 'Planta anual originaria de América del Sur, que se cultiva como hortaliza en todo el mundo');
INSERT INTO plantas (nombre_comun, nombre_cientifico, descripcion) VALUES ('Cactus', 'Cactaceae', 'Familia de plantas suculentas que se encuentran en regiones áridas y semiáridas de todo el mundo');

-- caracteristicas
INSERT INTO caracteristicas (nombre) VALUES ('Color');
INSERT INTO caracteristicas (nombre) VALUES ('Altura');
INSERT INTO caracteristicas (nombre) VALUES ('Temperatura');
INSERT INTO caracteristicas (nombre) VALUES ('Humedad');
INSERT INTO caracteristicas (nombre) VALUES ('Tipo de suelo');

-- relaaciones
INSERT INTO relaciones (planta_id, caracteristica_id) VALUES (1, 1);
INSERT INTO relaciones (planta_id, caracteristica_id) VALUES (2, 1);
INSERT INTO relaciones (planta_id, caracteristica_id) VALUES (3, 4);
INSERT INTO relaciones (planta_id, caracteristica_id) VALUES (4, 3);
INSERT INTO relaciones (planta_id, caracteristica_id) VALUES (5, 5);

-- habitats
INSERT INTO habitats (nombre, descripcion) VALUES ('Desierto', 'Zonas muy áridas con temperaturas extremas y poca lluvia');
INSERT INTO habitats (nombre, descripcion) VALUES ('Bosque', 'Zonas con una gran densidad de árboles y otros tipos de vegetación');
INSERT INTO habitats (nombre, descripcion) VALUES ('Pradera', 'Zonas de tierra plana cubiertas por hierba y otros tipos de vegetación');
INSERT INTO habitats (nombre, descripcion) VALUES 
  ('Desierto', 'Zonas muy áridas con temperaturas extremas y poca lluvia'),
  ('Tundra', 'Regiones frías y áridas con permafrost y una corta temporada de crecimiento'),
  ('Bosque tropical', 'Selvas lluviosas con una gran variedad de especies y alta humedad'),
  ('Sabana', 'Regiones con una estación seca y otra húmeda, con pastizales y árboles dispersos'),
  ('Bosque templado', 'Bosques caducifolios con estaciones bien definidas y una gran diversidad de flora y fauna');

  INSERT INTO plantas_habitats (planta_id, habitat_id) VALUES
(1, 2),
(2, 3),
(3, 1),
(4, 4),
(5, 2);
