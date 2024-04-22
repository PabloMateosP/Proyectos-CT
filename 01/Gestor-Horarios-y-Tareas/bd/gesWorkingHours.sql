--
-- Base de Datos Proyecto gestor Proyectos, Tareas y Horarios 
--
drop database if EXISTS gesWorkingHours;

create database if not EXISTS gesWorkingHours;

use gesWorkingHours;

--
-- Table structure for table `employee`
-- Table to collect employee data
drop table if EXISTS `employees`;

create table `employees` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `last_name` varchar(45) DEFAULT NULL,
    `name` varchar(20) DEFAULT NULL,
    `phone` char(9) DEFAULT NULL,
    `city` varchar(20) DEFAULT NULL,
    `dni` char(9) DEFAULT NULL,
    `email` varchar(45) DEFAULT NULL,
    `total_hours` int(2) DEFAULT NULL,  
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `update_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `dni` (`dni`),
    UNIQUE KEY `email` (`email`)
);

INSERT INTO
    `employees` (
        `last_name`,
        `name`,
        `phone`,
        `city`,
        `dni`,
        `email`,
        `total_hours`
    )
VALUES
    (
        'Gomez',
        'Juan',
        '123456789',
        'Madrid',
        '12345678A',
        'juan.gomez@example.com',
        '0'
    ),
    (
        'Lopez',
        'Maria',
        '987654321',
        'Barcelona',
        '87654321B',
        'maria.lopez@example.com',
        '0'
    ),
    (
        'Martinez',
        'Carlos',
        '456123789',
        'Valencia',
        '23456789C',
        'carlos.martinez@example.com',
        '0'
    ),
    (
        'Rodriguez',
        'Ana',
        '789456123',
        'Sevilla',
        '34567890D',
        'ana.rodriguez@example.com',
        '0'
    );

--
-- Table structure for table `projectManager`
-- Table to collect project Manager data
drop table if EXISTS `projectManager`;

create table `projectManager` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `last_name` varchar(45) DEFAULT NULL,
    `name` varchar(20) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `update_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
);

INSERT INTO
    `projectManager` (`last_name`, `name`)
VALUES
    ('Gomez', 'Juan'),
    ('Perez', 'Ana'),
    ('Rodriguez', 'Carlos'),
    ('Fernandez', 'Maria'),
    ('Martinez', 'Pedro');

--
-- Table structure for table `customer`
-- Table to collect customer data
drop TABLE if EXISTS `customer`;

create table `customer` (
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

INSERT INTO
    `customer` (`name`, `phone`, `city`, `address`, `email`)
VALUES
    (
        'Juan Perez',
        '123456789',
        'Madrid',
        'Calle Falsa 123',
        'juan.perez@example.com'
    ),
    (
        'Ana Gomez',
        '987654321',
        'Barcelona',
        'Avenida Siempre Viva 456',
        'ana.gomez@example.com'
    ),
    (
        'Carlos Rodriguez',
        '456789123',
        'Valencia',
        'Plaza Mayor 789',
        'carlos.rodriguez@example.com'
    ),
    (
        'Maria Fernandez',
        '321654987',
        'Sevilla',
        'Paseo de la Castellana 321',
        'maria.fernandez@example.com'
    ),
    (
        'Pedro Martinez',
        '654321789',
        'Zaragoza',
        'Gran Via 654',
        'pedro.martinez@example.com'
    );

--
-- Table structure for table `project`
-- Table to collect project data 
drop table if EXISTS `projects`;

create table `projects` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `project` char(8) DEFAULT NULL,
    `description` varchar(50) DEFAULT NULL,
    `id_projectManager` int(10) unsigned DEFAULT NULL,
    `id_customer` int(10) unsigned DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `finish_date` timestamp not null default current_timestamp(),
    `update_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `id_projectManager` (`id_projectManager`),
    CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`id_projectManager`) REFERENCES `projectManager` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
);

INSERT INTO
    `projects` (
        `project`,
        `description`,
        `id_projectManager`,
        `id_customer`
    )
VALUES
    (
        'PRJ001',
        'Desarrollo de una aplicación web',
        1,
        1
    ),
    (
        'PRJ002',
        'Migración de base de datos',
        2,
        2
    ),
    (
        'PRJ003',
        'Implementación de seguridad de red',
        3,
        3
    ),
    (
        'PRJ004',
        'Optimización de infraestructura en la nube',
        4,
        4
    ),
    (
        'PRJ005',
        'Desarrollo de una aplicación móvil',
        5,
        5
    );

-- 
-- Table structure for table `tasks`
-- Table to collect data on the tasks of each project
drop table if EXISTS `tasks`;

create table `tasks` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `task` int(10) DEFAULT NULL,
    `description` varchar(50) DEFAULT NULL,
    `id_project` int(10) unsigned DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `update_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `id_project` (`id_project`),
    CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`id_project`) REFERENCES `projects` (`id`) ON DELETE CASCADE
);

INSERT INTO
    `tasks` (`task`, `description`, `id_project`)
VALUES
    (1, 'Diseño de interfaz de usuario', 1),
    (2, 'Desarrollo de backend', 2),
    (3, 'Pruebas unitarias', 3),
    (4, 'Despliegue en producción', 4),
    (5, 'Optimización de rendimiento', 5);

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

INSERT INTO
    `time_codes` (`time_code`, `description`)
VALUES
    (100, 'Normal Hours'),
    (200, 'Non Productive Hours'),
    (555, 'Extra Hours'),
    (900, 'Vacation Hours'),
    (901, 'Compensation Hours'),
    (905, 'Unpaid Leaves'),
    (906, 'Temp Layoff'),
    (909, 'Covid Flex-Hours');

-- 
-- Table structure for table `work_orders`
-- Table to collect work orders
DROP TABLE IF EXISTS `work_orders`;

CREATE TABLE IF NOT EXISTS `work_orders` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `work_order` CHAR(10) DEFAULT NULL,
    `description` VARCHAR(50) DEFAULT NULL,
    `order_responsible` varchar(20) Default Null,
    `project` char(8) DEFAULT NULL,
    `id_customer` int(10) unsigned DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `finish_date` timestamp not null default current_timestamp(),
    `update_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`) 
);
INSERT INTO
    `work_orders` (
        `work_order`,
        `description`,
        `order_responsible`,
        `project`,
        `id_customer`
    )
VALUES
    (
        'WO123456',
        'Repair machinery',
        'John Doe',
        'PRJ001',
        1
    ),
    (
        'WO789012',
        'Install new software',
        'Jane Smith',
        'PRJ002',
        2
    ),
    (
        'WO345678',
        'Inspect electrical system',
        'Mike Johnson',
        'PRJ003',
        3
    ),
    (
        'WO901234',
        'Replace damaged parts',
        'Emily Davis',
        'PRJ004',
        4
    );

--
-- Table structure for table `working_hours`
-- Table to collect working hours
DROP TABLE IF EXISTS `working_hours`;

CREATE TABLE IF NOT EXISTS `working_hours` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_employee` int(10) unsigned DEFAULT NULL,
    `id_time_code` int(10) unsigned DEFAULT NULL,
    `id_work_order` int(10) unsigned DEFAULT NULL,
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
    KEY `id_work_order` (`id_work_order`),
    KEY `id_project` (`id_project`),
    KEY `id_task` (`id_task`),
    FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id`),
    FOREIGN KEY (`id_time_code`) REFERENCES `time_codes` (`id`),
    FOREIGN KEY (`id_work_order`) REFERENCES `work_orders` (`id`),
    FOREIGN KEY (`id_project`) REFERENCES `projects` (`id`),
    FOREIGN KEY (`id_task`) REFERENCES `tasks` (`id`)
);

INSERT INTO
    `working_hours` (
        `id_employee`,
        `id_time_code`,
        `id_work_order`,
        `id_project`,
        `id_task`,
        `description`,
        `duration`,
        `date_worked`
    )
VALUES
    (
        1,
        2,
        3,
        4,
        5,
        'Trabajo en el proyecto X',
        3,
        '2024-04-17 12:39:58'
    ),
    (
        2,
        3,
        4,
        5,
        1,
        'Revisión de la tarea Y',
        5,
        '2024-04-16 10:30:00'
    ),
    (
        3,
        4,
        2,
        1,
        2,
        'Desarrollo de la orden de trabajo Z',
        8,
        '2024-04-15 14:45:30'
    ),
    (
        4,
        5,
        1,
        2,
        3,
        'Análisis del código de tiempo A',
        5,
        '2024-04-14 09:15:45'
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
    `id_employee` INT UNSIGNED UNIQUE,
    `name` VARCHAR(50),
    `email` VARCHAR(50) UNIQUE,
    `password` CHAR(60),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `update_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY `id_employee` (`id_employee`),
    FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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

INSERT INTO
    `roles`
VALUES
    (
        1,
        'Administrador',
        'Todos los privilegios de la aplicación',
        default,
        default
    ),
    (
        2,
        'Editor',
        'Sólo podrá consultar, modificar y añadir información. No podrá eliminar',
        default,
        default
    ),
    (
        3,
        'Registrado',
        'Sólo podrá realizar consultas',
        default,
        default
    );