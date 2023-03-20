CREATE TABLE plantas (
    id INTEGER PRIMARY KEY,
    nombre_comun TEXT,
    nombre_cientifico TEXT,
    descripcion TEXT
);
CREATE TABLE caracteristicas (
    id INTEGER PRIMARY KEY,
    nombre TEXT
);
CREATE TABLE relaciones (
    id INTEGER PRIMARY KEY,
    planta_id INTEGER,
    caracteristica_id INTEGER,
    FOREIGN KEY(planta_id) REFERENCES plantas(id),
    FOREIGN KEY(caracteristica_id) REFERENCES caracteristicas(id)
);
CREATE TABLE habitats (
    id INTEGER PRIMARY KEY,
    nombre TEXT,
    descripcion TEXT
);
