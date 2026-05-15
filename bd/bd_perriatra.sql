-- 1. Crear la base de datos y usarla
CREATE DATABASE IF NOT EXISTS bd_perriatra;
USE bd_perriatra;

-- 2. Tabla para los Trabajadores (Login y Registro)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- Aquí guardaremos el Hash
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabla Razas
CREATE TABLE razas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    caracteristicas_fisicas TEXT,
    comportamiento TEXT
);

-- 4. Tabla Veterinarios
CREATE TABLE veterinarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    telefono VARCHAR(15),
    email VARCHAR(100) UNIQUE,
    especialidad VARCHAR(50),
    salario DECIMAL(10, 2) -- Formato para dinero (ej. 1500.50)
);

-- 5. Tabla Propietarios
CREATE TABLE propietarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(15) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    telefono VARCHAR(15),
    email VARCHAR(100)
);

-- 6. Tabla Mascotas (La más importante, con todas las relaciones)
CREATE TABLE mascotas (
    chip VARCHAR(50) PRIMARY KEY, -- Usamos el chip como identificador principal
    nombre VARCHAR(50) NOT NULL,
    sexo ENUM('Macho', 'Hembra', 'Desconocido') NOT NULL,
    fecha_nacimiento DATE,
    raza_id INT,
    propietario_id INT,
    veterinario_id INT,

    -- REGLAS DE LAS CLAVES FORÁNEAS (¿Qué pasa con el perro si borramos otra cosa?)
    -- 1. Si borras una Raza del sistema (ej. quitas "Pitbull" de la lista de razas):
    -- El perro NO se borra. Simplemente su casilla de raza se queda en blanco (NULL).
    FOREIGN KEY (raza_id) REFERENCES razas(id) ON DELETE SET NULL,

    -- 2. Si borras a un Dueño (ej. el cliente se da de baja de la clínica):
    -- EFECTO DOMINÓ (CASCADE): Se borran automáticamente de la base de datos 
    -- todos los perros que le pertenecían a ese dueño (no queremos perros "fantasma").
    FOREIGN KEY (propietario_id) REFERENCES propietarios(id) ON DELETE CASCADE,


    -- 3. Si borras a un Veterinario (ej. despedimos a Daniel):
    -- El perro NO se borra (¡los pacientes se quedan!). Su casilla de veterinario
    -- asignado se queda en blanco (NULL) a la espera de que le asignes otro médico.
    FOREIGN KEY (veterinario_id) REFERENCES veterinarios(id) ON DELETE SET NULL
);