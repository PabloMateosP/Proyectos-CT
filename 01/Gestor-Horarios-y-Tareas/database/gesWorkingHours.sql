--
-- Base de Datos Proyecto gestor Proyectos, Tareas y Horarios 
--
drop database if EXISTS gesWorkingHours;

create database if not EXISTS gesWorkingHours;

use gesWorkingHours;

-- Table structure for table `employees`
-- Table to collect employee data
DROP TABLE IF EXISTS `employees`;

CREATE TABLE `employees` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `last_name` VARCHAR(45) DEFAULT NULL,
    `name` VARCHAR(20) DEFAULT NULL,
    `phone` CHAR(9) DEFAULT NULL,
    `city` VARCHAR(20) DEFAULT NULL,
    `dni` CHAR(9) DEFAULT NULL,
    `email` VARCHAR(45) DEFAULT NULL,
    `total_hours` int(2) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `update_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `dni` (`dni`),
    UNIQUE KEY `email` (`email`)
);

--
-- Table structure for table `customer`
-- Table to collect customer data
drop TABLE if EXISTS `customers`;

create table `customers` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(20) Default Null,
    `phone` char(9) DEFAULT NULL,
    `city` varchar(20) DEFAULT NULL,
    `address` varchar(20) DEFAULT NULL,
    `email` varchar(45) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `update_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
);

-- Table structure for table `project_managers`
-- Table to collect project manager data
DROP TABLE IF EXISTS `project_managers`;

CREATE TABLE `project_managers` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `last_name` VARCHAR(45) DEFAULT NULL,
    `name` VARCHAR(20) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `update_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`)
);

-- Table structure for table `projects`
-- Table to collect project data
CREATE TABLE `projects` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `project` CHAR(8) DEFAULT NULL,
    `description` VARCHAR(50) DEFAULT NULL,
    `id_projectManager` INT(10) UNSIGNED DEFAULT NULL,
    `id_customer` INT(10) UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `finish_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `update_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_project_manager` FOREIGN KEY (`id_projectManager`) REFERENCES `project_managers` (`id`),
    CONSTRAINT `fk_customer` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id`)
);

-- Table structure for table `project_employee`
-- Table to establish the relationship between projects and employees
-- [Tabla para indicar que un empleado está asociado a uno o varios proyectos y viceversa]
DROP TABLE IF EXISTS `project_employee`;

CREATE TABLE `project_employee` (
    `id_employee` INT(10) UNSIGNED NOT NULL,
    `id_project` INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_employee`, `id_project`),
    FOREIGN KEY (`id_employee`) REFERENCES `employees`(`id`),
    FOREIGN KEY (`id_project`) REFERENCES `projects`(`id`)
);

-- Table structure for table `projectManager_project`
-- Table to establish the relationship between project managers and projects
-- [Tabla para relacionar un manager de proyecto con uno o más proyecto]
DROP TABLE IF EXISTS `projectManager_project`;

CREATE TABLE `projectManager_project` (
    `id_project_manager` INT(10) UNSIGNED NOT NULL,
    `id_project` INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_project_manager`, `id_project`),
    FOREIGN KEY (`id_project_manager`) REFERENCES `project_managers`(`id`),
    FOREIGN KEY (`id_project`) REFERENCES `projects`(`id`)
);

-- Table structure for table `customer_project`
-- Table to establish the relationship between customers and projects
-- [Tabla para relacionar un cliente con varios proyectos]
DROP TABLE IF EXISTS `customer_project`;

CREATE TABLE `customer_project` (
    `id_customer` INT(10) UNSIGNED NOT NULL,
    `id_project` INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_customer`, `id_project`),
    FOREIGN KEY (`id_customer`) REFERENCES `customers`(`id`),
    FOREIGN KEY (`id_project`) REFERENCES `projects`(`id`)
);

-- Table structure for table `tasks`
-- Table to collect task data
DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `task` VARCHAR(20) DEFAULT NULL,
    `description` VARCHAR(50) DEFAULT NULL,
    `id_project` INT(10) UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `update_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`id_project`) REFERENCES `projects`(`id`)
);

-- 
-- Table structure for table `time_codes`
-- Table to collect time codes
DROP TABLE IF EXISTS `time_codes`;

CREATE TABLE IF NOT EXISTS `time_codes` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `time_code` int(3) DEFAULT NULL,
    `description` varchar(50) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `update_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
);

-- Table structure for table `working_hours`
-- Table to collect working hours
DROP TABLE IF EXISTS `working_hours`;

CREATE TABLE IF NOT EXISTS `working_hours` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_employee` int(10) unsigned DEFAULT NULL,
    `id_time_code` int(10) unsigned DEFAULT NULL,
    `id_project` int(10) unsigned DEFAULT NULL,
    `id_task` int(10) unsigned DEFAULT NULL,
    `description` varchar(50) DEFAULT NULL,
    `duration` INT(2) DEFAULT NULL,
    `date_worked` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `update_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `id_employee` (`id_employee`),
    KEY `id_time_code` (`id_time_code`),
    KEY `id_project` (`id_project`),
    KEY `id_task` (`id_task`),
    FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id`),
    FOREIGN KEY (`id_time_code`) REFERENCES `time_codes` (`id`),
    FOREIGN KEY (`id_project`) REFERENCES `projects` (`id`),
    FOREIGN KEY (`id_task`) REFERENCES `tasks` (`id`)
);

-- ------------------------------------------------------------------------------------------------------
-- USER GESTION 
-- ------------------------------------------------------------------------------------------------------
--
-- Table structure for table `users`
-- Table to collect users data
DROP TABLE IF EXISTS `users`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(50),
    `email` VARCHAR(50) UNIQUE,
    `password` CHAR(60),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `update_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--
-- Table structure for table `roles`
-- Table to collect roles data
DROP TABLE IF EXISTS `roles`;

CREATE TABLE IF NOT EXISTS `roles`(
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(20),
    `description` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `update_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO
    `roles`
VALUES
    (
        1,
        'Admin',
        'All the privileges fromt the app (Can create, delete and edit all in the app)',
        default,
        default
    ),
    (
        2,
        'Manager',
        'Can create tasks, projects, workingHours and employees',
        default,
        default
    ),
    (
        3,
        'Organiser',
        'Can create tasks, projects and workingHours.',
        default,
        default
    ),
    (
        4,
        'Employee',
        'Can create workingHours.',
        default,
        default
    );

--
-- Table structure for table `roles_users`
-- Table to collect users roles
DROP TABLE IF EXISTS `roles_users`;

CREATE TABLE IF NOT EXISTS `roles_users`(
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT UNSIGNED,
    `role_id` INT UNSIGNED,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `update_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- POSIBLES FUNCIONES PARA MEJORAR EL RENDIMIENTO DEL SERVIDOR --
-- ----------------------------------------------------------- --

-- 1. Función para sumar a las horas totales cuando se añada una nueva working hour
-- ----------------------------------------------------------- --

-- DELIMITER //

-- CREATE TRIGGER update_total_hours AFTER INSERT ON working_hours
-- FOR EACH ROW
-- BEGIN
--     UPDATE employees 
--     SET total_hours = total_hours + NEW.duration 
--     WHERE id = NEW.employee_id;
-- END;
-- //

-- DELIMITER ;

-- ----------------------------------------------------------- --
