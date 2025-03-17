-- Prueba Tecnica - Harlyn Luna Brenes

-- Elimnar base de datos si existe
DROP DATABASE IF EXISTS ColegioBD;

-- Creación de la base de datos ColegioDB
CREATE DATABASE ColegioBD;

-- Tabla de Estudiantes
CREATE TABLE ColegioBD.ESTUDIANTE (
    IdEstudiante INT PRIMARY KEY AUTO_INCREMENT,
    NombreEstudiante VARCHAR(50) NOT NULL,
    CorreoEstudiante VARCHAR(50) NOT NULL, 
    FechaCreacion DATETIME
);

-- Tabla de Cursos
CREATE TABLE ColegioBD.CURSOS(
    idCurso INT PRIMARY KEY AUTO_INCREMENT,
    NombreCurso VARCHAR(50) NOT NULL
);

-- Tabla de Estudiantes_Cursos
CREATE TABLE ColegioBD.ESTUDIANTE_CURSO(
    IdEstudiante INT NOT NULL,
    IdCurso INT NOT NULL,
    PRIMARY KEY(IdEstudiante, IdCurso),
    FOREIGN KEY(IdEstudiante) REFERENCES ESTUDIANTE(IdEstudiante),
    FOREIGN KEY(IdCurso) REFERENCES CURSOS(idCurso)
);

-- Insertar Datos Tabla Estudiantes
INSERT INTO ColegioBD.ESTUDIANTE (NombreEstudiante, CorreoEstudiante, FechaCreacion) VALUES ('Harlyn','hluna@edu.co.cr', NOW());
INSERT INTO ColegioBD.ESTUDIANTE (NombreEstudiante, CorreoEstudiante, FechaCreacion) VALUES ('Luis','lsolis@edu.co.cr', NOW());
INSERT INTO ColegioBD.ESTUDIANTE (NombreEstudiante, CorreoEstudiante, FechaCreacion) VALUES ('Maria','mrodriguez@edu.co.cr', NOW());

-- Procedimientos almacenados 

-- Procedimiento para obtener estudiantes
DELIMITER $$
CREATE PROCEDURE ColegioBD.P_ObtenerEstudiantes()
BEGIN
    SELECT * FROM ESTUDIANTE;
END $$
DELIMITER ;

-- Procedimiento para obtener estudiante por ID
DELIMITER $$
CREATE PROCEDURE ColegioBD.P_ObtenerEstudianteID(
    IN c_IdEstudiante INT
)
BEGIN
    SELECT * FROM ESTUDIANTE WHERE IdEstudiante = c_IdEstudiante;
END $$
DELIMITER ;

-- Procedimiento para editar estudiante
DELIMITER $$
CREATE PROCEDURE ColegioBD.P_EditarEstudiantes(
    IN c_IdEstudiante INT,
    IN v_NombreEstudiante VARCHAR(50),
    IN v_CorreoEstudiante VARCHAR(50)
)
BEGIN
    UPDATE Estudiante
    SET 
        NombreEstudiante = v_NombreEstudiante,
        CorreoEstudiante = v_CorreoEstudiante
    WHERE IdEstudiante = c_IdEstudiante;
END $$
DELIMITER ;

-- Procedimiento para eliminar estudiantes
DELIMITER $$
CREATE PROCEDURE ColegioBD.P_EliminarEstudianteID(
    IN c_IdEstudiante INT
)
BEGIN
    DELETE FROM ESTUDIANTE WHERE IdEstudiante = c_IdEstudiante;
END $$
DELIMITER ;

-- Procedimiento para agregar nuevo estudiante
DELIMITER $$
CREATE PROCEDURE ColegioBD.P_AgregarEstudiante(
    IN v_NombreEstudiante VARCHAR(50),
    IN v_CorreoEstudiante VARCHAR(50)
)
BEGIN
    INSERT INTO ESTUDIANTE (NombreEstudiante, CorreoEstudiante, FechaCreacion) VALUES (v_NombreEstudiante, v_CorreoEstudiante, NOW());
END $$
DELIMITER ;