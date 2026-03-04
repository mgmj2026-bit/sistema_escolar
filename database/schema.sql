CREATE DATABASE IF NOT EXISTS sistema_escolar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_escolar;

CREATE TABLE IF NOT EXISTS personas_institucionales (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dni CHAR(8) NOT NULL UNIQUE,
    name VARCHAR(120) NOT NULL,
    role ENUM('director','subdirector','secretaria','auxiliar','docente','estudiante','padre') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    person_id INT UNSIGNED NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    dni CHAR(8) NULL UNIQUE,
    role ENUM('director','subdirector','secretaria','auxiliar','docente','estudiante','padre') NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    verification_token VARCHAR(120) NULL,
    two_factor_code VARCHAR(10) NULL,
    two_factor_expires_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (person_id) REFERENCES personas_institucionales(id)
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    evento VARCHAR(120) NOT NULL,
    detalle VARCHAR(255) NOT NULL,
    ip VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS parent_students (
    parent_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (parent_id, student_id),
    FOREIGN KEY (parent_id) REFERENCES users(id),
    FOREIGN KEY (student_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS academic_years (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(80) NOT NULL,
    starts_on DATE NOT NULL,
    ends_on DATE NOT NULL,
    is_active TINYINT(1) DEFAULT 0
);
CREATE TABLE IF NOT EXISTS grades (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50) NOT NULL);
CREATE TABLE IF NOT EXISTS sections (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    grade_id INT UNSIGNED NOT NULL,
    name VARCHAR(20) NOT NULL,
    capacity INT UNSIGNED NOT NULL DEFAULT 30,
    FOREIGN KEY (grade_id) REFERENCES grades(id)
);
CREATE TABLE IF NOT EXISTS teacher_sections (
    teacher_id INT UNSIGNED NOT NULL,
    section_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (teacher_id, section_id),
    FOREIGN KEY (teacher_id) REFERENCES users(id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
);
CREATE TABLE IF NOT EXISTS enrollments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    section_id INT UNSIGNED NOT NULL,
    academic_year_id INT UNSIGNED NOT NULL,
    status ENUM('pendiente','confirmada','retirada') DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (section_id) REFERENCES sections(id),
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id)
);
CREATE TABLE IF NOT EXISTS finance_transactions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    concept VARCHAR(120) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    movement_type ENUM('ingreso','egreso') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE IF NOT EXISTS chat_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sender_id INT UNSIGNED NOT NULL,
    receiver_id INT UNSIGNED NOT NULL,
    body VARCHAR(500) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
);
CREATE TABLE IF NOT EXISTS attendance_students (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    teacher_id INT UNSIGNED NOT NULL,
    fecha DATE NOT NULL,
    estado ENUM('presente','tarde','ausente') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (teacher_id) REFERENCES users(id)
);
CREATE TABLE IF NOT EXISTS attendance_staff (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    staff_id INT UNSIGNED NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES users(id)
);

INSERT INTO personas_institucionales (dni, name, role) VALUES
('12345678', 'Maximiliano Juarez', 'estudiante'),
('87654321', 'Rosa Mendoza', 'docente'),
('45671234', 'Alicia Paredes', 'secretaria'),
('11223344', 'Carla Torres', 'subdirector'),
('99887766', 'Luis Salinas', 'director')
ON DUPLICATE KEY UPDATE name = VALUES(name), role = VALUES(role);

INSERT INTO users (person_id, name, email, dni, role, password_hash, is_verified) VALUES
((SELECT id FROM personas_institucionales WHERE dni='99887766'),'Directora General','directora@school.local','99887766','director','$2y$12$NeFQOWWFPgQN9UuFVuqKXO7CsLIe5tB6o3NAlWVsA616QRBgtDF3e',1)
ON DUPLICATE KEY UPDATE name = VALUES(name);
