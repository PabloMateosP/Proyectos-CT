<?php

class workingHoursModel extends Model
{

    # ---------------------------------------------------------------------------------
    #
    #     _____ ______ _______ 
    #    / ____|  ____|__   __|
    #   | |  __| |__     | |   
    #   | | |_ |  __|    | |   
    #   | |__| | |____   | |   
    #    \_____|______|  |_|   
    #
    # ---------------------------------------------------------------------------------
    # Method get 
    # Select form table working hours for the admin view
    public function get()
    {
        try {
            $sql = "
            SELECT 
                wh.id,
                wh.id_employee,
                concat_ws(', ', emp.last_name, emp.name) employee_name,
                tc.time_code,
                p.project AS project_name,
                t.description AS task_description,
                wh.date_worked,
                wh.duration
            FROM 
                working_hours wh
            JOIN 
                employees emp ON wh.id_employee = emp.id
            JOIN 
                time_codes tc ON wh.id_time_code = tc.id
            LEFT JOIN 
                projects p ON wh.id_project = p.id
            LEFT JOIN 
                tasks t ON wh.id_task = t.id
            ORDER by wh.id asc;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }


    public function getExport()
    {
        try {
            $sql = "SELECT 
                        emp.identification,
                        concat_ws(', ', emp.last_name, emp.name) employee_name,
                        tc.time_code,
                        p.project AS project_name,
                        t.description AS task_description,
                        wh.date_worked,
                        wh.duration
                    FROM 
                        working_hours wh
                    JOIN 
                        employees emp ON wh.id_employee = emp.id
                    JOIN 
                        time_codes tc ON wh.id_time_code = tc.id
                    LEFT JOIN 
                        projects p ON wh.id_project = p.id
                    LEFT JOIN 
                        tasks t ON wh.id_task = t.id
                    ORDER by wh.id asc;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #
    #    _____ ______ _______   ______ __  __ _____  _      ______     ________ ______    _    _  ____  _    _ _____  
    #   / ____|  ____|__   __| |  ____|  \/  |  __ \| |    / __ \ \   / /  ____|  ____|  | |  | |/ __ \| |  | |  __ \ 
    #  | |  __| |__     | |    | |__  | \  / | |__) | |   | |  | \ \_/ /| |__  | |__     | |__| | |  | | |  | | |__) |
    #  | | |_ |  __|    | |    |  __| | |\/| |  ___/| |   | |  | |\   / |  __| |  __|    |  __  | |  | | |  | |  _  / 
    #  | |__| | |____   | |    | |____| |  | | |    | |___| |__| | | |  | |____| |____   | |  | | |__| | |__| | | \ \ 
    #   \_____|______|  |_|    |______|_|  |_|_|    |______\____/  |_|  |______|______|  |_|  |_|\____/ \____/|_|  \_\
    #
    # ---------------------------------------------------------------------------------
    # Method get employee hours
    # Select from table working hours where the email is same that the user email
    # For employee table 
    public function get_employeeHours($user_email)
    {
        try {
            $sql = "
        SELECT 
            wh.id,
            wh.id_employee,
            concat_ws(', ', emp.last_name, emp.name) employee_name,
            tc.time_code,
            p.project AS project_name,
            t.description AS task_description,
            wh.date_worked,
            wh.duration
        FROM 
            working_hours wh
        JOIN 
            employees emp ON wh.id_employee = emp.id
        JOIN 
            time_codes tc ON wh.id_time_code = tc.id
        LEFT JOIN 
            projects p ON wh.id_project = p.id
        LEFT JOIN
            tasks t ON wh.id_task = t.id
        JOIN users u on emp.email = u.email
        WHERE
            u.email = :email
        ORDER BY wh.id ASC;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':email', $user_email);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function get_employeeHoursExport($user_email)
    {
        try {
            $sql = "SELECT 
                        emp.identification,
                        concat_ws(', ', emp.last_name, emp.name) employee_name,
                        tc.time_code,
                        p.project AS project_name,
                        t.description AS task_description,
                        wh.date_worked,
                        wh.duration
                    FROM 
                        working_hours wh
                    LEFT JOIN 
                        employees emp ON wh.id_employee = emp.id
                    JOIN 
                        time_codes tc ON wh.id_time_code = tc.id
                    LEFT JOIN 
                        projects p ON wh.id_project = p.id
                    LEFT JOIN 
                        tasks t ON wh.id_task = t.id
                    JOIN users u on emp.email = u.email
                    WHERE
                        u.email = :email
                    ORDER BY wh.id ASC;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':email', $user_email);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function get_employeeHoursExport2($email, $dateFilter)
    {
        try {

            $query = "SELECT 
                            emp.identification,
                            concat_ws(', ', emp.last_name, emp.name) employee_name,
                            tc.time_code,
                            p.project AS project_name,
                            t.description AS task_description,
                            wh.date_worked,
                            wh.duration
                        FROM 
                            working_hours wh
                        JOIN 
                            employees emp ON wh.id_employee = emp.id
                        JOIN 
                            time_codes tc ON wh.id_time_code = tc.id
                        LEFT JOIN 
                            projects p ON wh.id_project = p.id
                        LEFT JOIN 
                            tasks t ON wh.id_task = t.id
                        JOIN 
                            users u on emp.email = u.email
                        WHERE
                            u.email = :email AND $dateFilter
                        ORDER BY wh.id ASC;";

            $conexion = $this->db->connect();
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }

    }

    public function getExport2($dateFilter)
    {
        try {

            $query = "SELECT 
                        emp.identification,
                        concat_ws(', ', emp.last_name, emp.name) employee_name,
                        tc.time_code,
                        p.project AS project_name,
                        t.description AS task_description,
                        wh.date_worked,
                        wh.duration
                    FROM 
                        working_hours wh
                    JOIN 
                        employees emp ON wh.id_employee = emp.id
                    JOIN 
                        time_codes tc ON wh.id_time_code = tc.id
                    LEFT JOIN 
                        projects p ON wh.id_project = p.id
                    LEFT JOIN 
                        tasks t ON wh.id_task = t.id
                    WHERE $dateFilter
                    ORDER by wh.id asc;";

            $conexion = $this->db->connect();
            $stmt = $conexion->prepare($query);
            $stmt->execute();
            return $stmt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }

    }

    # ---------------------------------------------------------------------------------
    #    
    #    _____ ______ _______   _______ ____ _______       _        _    _  ____  _    _ _____   _____ 
    #   / ____|  ____|__   __| |__   __/ __ \__   __|/\   | |      | |  | |/ __ \| |  | |  __ \ / ____|
    #  | |  __| |__     | |       | | | |  | | | |  /  \  | |      | |__| | |  | | |  | | |__) | (___  
    #  | | |_ |  __|    | |       | | | |  | | | | / /\ \ | |      |  __  | |  | | |  | |  _  / \___ \ 
    #  | |__| | |____   | |       | | | |__| | | |/ ____ \| |____  | |  | | |__| | |__| | | \ \ ____) |
    #   \_____|______|  |_|       |_|  \____/  |_/_/    \_\______| |_|  |_|\____/ \____/|_|  \_\_____/ 
    #
    # ---------------------------------------------------------------------------------
    public function getTotalHours()
    {
        try {
            $sql = "SELECT total_hours FROM employees WHERE id = :employee_id";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':employee_id', $_SESSION['employee_id']);
            $pdoSt->execute();
            $result = $pdoSt->fetch(PDO::FETCH_ASSOC);

            // Verificar si $result es falso o es un array
            if ($result !== false && isset($result['total_hours'])) {
                return $result['total_hours'];
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }




    # ---------------------------------------------------------------------------------
    #   _____ ______ _______   ______ __  __ _____  _      ______     ________ ______    _____  ______ _______       _____ _       _____ 
    #  / ____|  ____|__   __| |  ____|  \/  |  __ \| |    / __ \ \   / /  ____|  ____|  |  __ \|  ____|__   __|/\   |_   _| |     / ____|
    # | |  __| |__     | |    | |__  | \  / | |__) | |   | |  | \ \_/ /| |__  | |__     | |  | | |__     | |  /  \    | | | |    | (___  
    # | | |_ |  __|    | |    |  __| | |\/| |  ___/| |   | |  | |\   / |  __| |  __|    | |  | |  __|    | | / /\ \   | | | |     \___ \ 
    # | |__| | |____   | |    | |____| |  | | |    | |___| |__| | | |  | |____| |____   | |__| | |____   | |/ ____ \ _| |_| |____ ____) |
    #  \_____|______|  |_|    |______|_|  |_|_|    |______\____/  |_|  |______|______|  |_____/|______|  |_/_/    \_\_____|______|_____/ 
    #                                                                                                                                                                                                                                                                       
    # ---------------------------------------------------------------------------------
    public function getEmployeeDetails($id)
    {
        try {
            $sql = "
            SELECT 
                id,
                CONCAT_WS(', ', last_name, name) AS employee_
            FROM 
                employees
            WHERE 
                id = :id;
        ";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':id', $id);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;
        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #
    #    _____ ______ _______   _    _  _____ ______ _____     ______ __  __          _____ _      
    #   / ____|  ____|__   __| | |  | |/ ____|  ____|  __ \   |  ____|  \/  |   /\   |_   _| |     
    #  | |  __| |__     | |    | |  | | (___ | |__  | |__) |  | |__  | \  / |  /  \    | | | |     
    #  | | |_ |  __|    | |    | |  | |\___ \|  __| |  _  /   |  __| | |\/| | / /\ \   | | | |     
    #  | |__| | |____   | |    | |__| |____) | |____| | \ \   | |____| |  | |/ ____ \ _| |_| |____ 
    #   \_____|______|  |_|     \____/|_____/|______|_|  \_\  |______|_|  |_/_/    \_\_____|______|                                                                                                                                                                                            
    #
    # ---------------------------------------------------------------------------------
    # Method get_userEmailById
    # With that method we can take the email of a user by his id
    public function get_userEmailById($user_id)
    {
        try {
            $sql = "SELECT email FROM users WHERE id = :user_id";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':user_id', $user_id);
            $pdoSt->execute();

            # Verificar si la consulta encontró resultados
            if ($pdoSt->rowCount() > 0) {

                $result = $pdoSt->fetch(PDO::FETCH_ASSOC);
                return $result['email'];

            } else {
                return null;
            }

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------

    # ---------------------------------------------------------------------------------
    public function get_employee_email($employee_id)
    {
        try {
            $sql = "SELECT email FROM employees WHERE id = :employee_id";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':employee_id', $employee_id);
            $pdoSt->execute();

            # Verificar si la consulta encontró resultados
            if ($pdoSt->rowCount() > 0) {

                $result = $pdoSt->fetch(PDO::FETCH_ASSOC);
                return $result['email'];

            } else {
                return null;
            }

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #   _____ ______ _______  __          ___    _  ____  _    _ _____  
    #   / ____|  ____|__   __| \ \        / / |  | |/ __ \| |  | |  __ \ 
    #  | |  __| |__     | |     \ \  /\  / /| |__| | |  | | |  | | |__) |
    #  | | |_ |  __|    | |      \ \/  \/ / |  __  | |  | | |  | |  _  / 
    #  | |__| | |____   | |       \  /\  /  | |  | | |__| | |__| | | \ \ 
    #   \_____|______|  |_|        \/  \/   |_|  |_|\____/ \____/|_|  \_\
    #
    # ---------------------------------------------------------------------------------
    # getWHour
    # Select total hours from the employee with the id
    public function getWHours($id)
    {
        try {
            $sql = "SELECT duration FROM working_hours WHERE id = :id";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':id', $id);
            $pdoSt->execute();
            $result = $pdoSt->fetch(PDO::FETCH_ASSOC);
            return $result['duration'];

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #    _____ ______ _______   _______ _____ __  __ ______  _____     _____ ____  _____  ______  _____ 
    #   / ____|  ____|__   __| |__   __|_   _|  \/  |  ____|/ ____|   / ____/ __ \|  __ \|  ____|/ ____|
    #  | |  __| |__     | |       | |    | | | \  / | |__  | (___    | |   | |  | | |  | | |__  | (___  
    #  | | |_ |  __|    | |       | |    | | | |\/| |  __|  \___ \   | |   | |  | | |  | |  __|  \___ \ 
    #  | |__| | |____   | |       | |   _| |_| |  | | |____ ____) |  | |___| |__| | |__| | |____ ____) |
    #   \_____|______|  |_|       |_|  |_____|_|  |_|______|_____/    \_____\____/|_____/|______|_____/ 
    #
    # ---------------------------------------------------------------------------------
    # get_times_codes
    # Select from the time_code from the time_code
    public function get_times_codes()
    {
        try {
            $sql = "SELECT id, time_code, description FROM time_codes";
            $conexion = $this->db->connect();
            $result = $conexion->prepare($sql);
            $result->setFetchMode(PDO::FETCH_OBJ);
            $result->execute();

            return $result->fetchAll();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------  
    #  
    #    _____ ______ _______   _____  _____   ____       _ ______ _____ _______ _____ 
    #   / ____|  ____|__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __/ ____|
    #  | |  __| |__     | |    | |__) | |__) | |  | |    | | |__ | |       | | | (___  
    #  | | |_ |  __|    | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |  \___ \ 
    #  | |__| | |____   | |    | |    | | \ \| |__| | |__| | |___| |____   | |  ____) |
    #   \_____|______|  |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_| |_____/ 
    #
    # ---------------------------------------------------------------------------------
    # function get_projects 
    # function to get the information about all the projects
    public function get_projects()
    {
        try {
            $sql = "SELECT 
                    pr.id,
                    pr.project,
                    pr.description,
                    pm.last_name AS manager_last_name,
                    pm.name AS manager_name
                FROM 
                    projects pr
                JOIN 
                    projectManager_project ppm ON pr.id = ppm.id_project
                JOIN 
                    project_managers pm ON ppm.id_project_manager = pm.id";

            $conexion = $this->db->connect();
            $result = $conexion->prepare($sql);
            $result->setFetchMode(PDO::FETCH_OBJ);
            $result->execute();

            return $result->fetchAll();
        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }


    # ---------------------------------------------------------------------------------  
    #  
    #    _____ ______ _______   _____  _____   ____       _ ______ _____ _______ _____ 
    #   / ____|  ____|__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __/ ____|
    #  | |  __| |__     | |    | |__) | |__) | |  | |    | | |__ | |       | | | (___  
    #  | | |_ |  __|    | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |  \___ \ 
    #  | |__| | |____   | |    | |    | | \ \| |__| | |__| | |___| |____   | |  ____) |
    #   \_____|______|  |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_| |_____/ 
    #
    # ---------------------------------------------------------------------------------
    # function get_projects 
    # function to get the information about all the projects
    public function get_projectsRelated($id_employee)
    {
        try {
            $sql = "SELECT 
                    pr.id,
                    pr.project,
                    pr.description,
                    pm.last_name AS manager_last_name,
                    pm.name AS manager_name
                FROM 
                    projects pr
                JOIN 
                    projectManager_project ppm ON pr.id = ppm.id_project
                JOIN 
                    project_managers pm ON ppm.id_project_manager = pm.id
                JOIN 
                    project_employee pEmp ON pr.id = pEmp.id_project
                JOIN 
                    employees emp ON pEmp.id_employee = emp.id
                WHERE emp.id = :id_employee";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':id_employee', $id_employee);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();

            // Recuperar los resultados de la consulta
            $projects = $pdoSt->fetchAll();

            // Devolver los resultados
            return $projects;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }


    # ---------------------------------------------------------------------------------
    #  
    #    _____ ______ _______   _______        _____ _  __ _____ 
    #   / ____|  ____|__   __| |__   __|/\    / ____| |/ // ____|
    #  | |  __| |__     | |       | |  /  \  | (___ | ' /| (___  
    #  | | |_ |  __|    | |       | | / /\ \  \___ \|  <  \___ \ 
    #  | |__| | |____   | |       | |/ ____ \ ____) | . \ ____) |
    #   \_____|______|  |_|       |_/_/    \_\_____/|_|\_\_____/ 
    #
    # ---------------------------------------------------------------------------------
    public function get_tasks()
    {
        try {
            $sql = "SELECT 
                        ta.id,
                        ta.task,
                        ta.description,
                        pr.project AS project,
                        pr.description AS project_description
                    FROM 
                        tasks ta
                    JOIN 
                        projects pr ON ta.id_project = pr.id";

            $conexion = $this->db->connect();
            $result = $conexion->prepare($sql);
            $result->setFetchMode(PDO::FETCH_OBJ);
            $result->execute();

            return $result->fetchAll();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #
    #     _____ ______ _______   _______        _____ _  __ _____    _____  ______ _            _______ ______ _____  
    #    / ____|  ____|__   __| |__   __|/\    / ____| |/ // ____|  |  __ \|  ____| |        /\|__   __|  ____|  __ \ 
    #   | |  __| |__     | |       | |  /  \  | (___ | ' /| (___    | |__) | |__  | |       /  \  | |  | |__  | |  | |
    #   | | |_ |  __|    | |       | | / /\ \  \___ \|  <  \___ \   |  _  /|  __| | |      / /\ \ | |  |  __| | |  | |
    #   | |__| | |____   | |       | |/ ____ \ ____) | . \ ____) |  | | \ \| |____| |____ / ____ \| |  | |____| |__| |
    #    \_____|______|  |_|       |_/_/    \_\_____/|_|\_\_____/   |_|  \_\______|______/_/    \_\_|  |______|_____/ 
    #
    # ---------------------------------------------------------------------------------
    public function get_tasksRelated($id_project)
    {
        try {
            $sql = "SELECT 
                        ta.id,
                        ta.task,
                        ta.description,
                        pr.project AS project,
                        pr.description AS project_description
                    FROM 
                        tasks ta
                    JOIN 
                        projects pr ON ta.id_project = pr.id
                    WHERE ta.id_project = :id_project";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':id_project', $id_project);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();

            // Recuperar los resultados de la consulta
            $tasks = $pdoSt->fetchAll();

            // Devolver los resultados
            return $tasks;


        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #   _    _ _____  _____       _______ ______   _______ ____ _______       _        _    _  ____  _    _ _____   _____ 
    #  | |  | |  __ \|  __ \   /\|__   __|  ____| |__   __/ __ \__   __|/\   | |      | |  | |/ __ \| |  | |  __ \ / ____|
    #  | |  | | |__) | |  | | /  \  | |  | |__       | | | |  | | | |  /  \  | |      | |__| | |  | | |  | | |__) | (___  
    #  | |  | |  ___/| |  | |/ /\ \ | |  |  __|      | | | |  | | | | / /\ \ | |      |  __  | |  | | |  | |  _  / \___ \ 
    #  | |__| | |    | |__| / ____ \| |  | |____     | | | |__| | | |/ ____ \| |____  | |  | | |__| | |__| | | \ \ ____) |
    #   \____/|_|    |_____/_/    \_\_|  |______|    |_|  \____/  |_/_/    \_\______| |_|  |_|\____/ \____/|_|  \_\_____/ 
    #                                                                                                                                                                                                                                       
    # ---------------------------------------------------------------------------------
    # Método update para las horas totales del usuario
    # Permite actualizar las horas totales de un usuario mediante la suma de la duración de las horas laborales 
    public function update_total_hours($employee_id)
    {
        try {
            $sql = "
            UPDATE employees e
            SET e.total_hours = (
                SELECT SUM(wh.duration)
                FROM working_hours wh
                WHERE wh.id_employee = :employee_id
            )
            WHERE e.id = :employee_id
        ";

            # Preparar la consulta
            $statement = $this->db->prepare($sql);

            # Bind de los parámetros
            $statement->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);

            # Ejecutar la consulta
            $statement->execute();

            # Comprobar si se realizó la actualización correctamente
            if ($statement->rowCount() > 0) {
                return true; // Actualización exitosa
            } else {
                return false; // No se pudo actualizar (el empleado puede no existir)
            }

        } catch (PDOException $error) {

            require_once ("template/partials/errorDB.php");
            exit();

        }
    }


    # ---------------------------------------------------------------------------------
    #    
    #    _____ _____  ______       _______ ______ 
    #   / ____|  __ \|  ____|   /\|__   __|  ____|
    #  | |    | |__) | |__     /  \  | |  | |__   
    #  | |    |  _  /|  __|   / /\ \ | |  |  __|  
    #  | |____| | \ \| |____ / ____ \| |  | |____ 
    #   \_____|_|  \_\______/_/    \_\_|  |______|
    #
    # ---------------------------------------------------------------------------------
    # Method create
    # Allow to create a new working hour
    public function create(classWorkingHours $workingHours)
    {
        try {
            $sql = " INSERT INTO 
                        working_hours 
                        (
                            id_employee, 
                            id_time_code, 
                            id_project, 
                            id_task, 
                            description,
                            duration,
                            date_worked
                        ) 
                        VALUES 
                        ( 
                            :id_employee, 
                            :id_time_code, 
                            :id_project, 
                            :id_task, 
                            :description_,
                            :duration,
                            :date_worked
                        )";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            // Link the parameters
            //-----------------------------------------------------------------------------------
            $pdoSt->bindParam(":id_employee", $workingHours->id_employee, PDO::PARAM_STR, 10);
            $pdoSt->bindParam(":id_time_code", $workingHours->id_time_code, PDO::PARAM_STR, 10);
            $pdoSt->bindParam(":id_project", $workingHours->id_project, PDO::PARAM_STR, 10);
            $pdoSt->bindParam(":id_task", $workingHours->id_task, PDO::PARAM_STR, 10);
            $pdoSt->bindParam(":description_", $workingHours->description, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":duration", $workingHours->duration, PDO::PARAM_INT, 2);
            $pdoSt->bindParam(":date_worked", $workingHours->date_worked, PDO::PARAM_STR, 20);

            // execute
            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #    _____ _    _ __  __   _______ _    _  ____  _    _ _____   _____  __          ___    _  ____  _    _ _____   _____ 
    #   / ____| |  | |  \/  | |__   __| |  | |/ __ \| |  | |  __ \ / ____| \ \        / / |  | |/ __ \| |  | |  __ \ / ____|
    #  | (___ | |  | | \  / |    | |  | |__| | |  | | |  | | |__) | (___    \ \  /\  / /| |__| | |  | | |  | | |__) | (___  
    #   \___ \| |  | | |\/| |    | |  |  __  | |  | | |  | |  _  / \___ \    \ \/  \/ / |  __  | |  | | |  | |  _  / \___ \ 
    #   ____) | |__| | |  | |    | |  | |  | | |__| | |__| | | \ \ ____) |    \  /\  /  | |  | | |__| | |__| | | \ \ ____) |
    #  |_____/ \____/|_|  |_|    |_|  |_|  |_|\____/ \____/|_|  \_\_____/      \/  \/   |_|  |_|\____/ \____/|_|  \_\_____/                                                                                                                   
    #
    # ---------------------------------------------------------------------------------
    # sumTHoursWHours
    # Sum the new working hour plus the total hours 
    public function sumTHoursWHour($duration, $employee_id)
    {
        try {

            $sql = "UPDATE employees SET total_hours = total_hours + :duration WHERE id = :employee_id";

            $pdoSt = $this->db->connect()->prepare($sql);

            $pdoSt->bindParam(":duration", $duration, PDO::PARAM_INT);
            $pdoSt->bindParam(":employee_id", $employee_id, PDO::PARAM_INT);

            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partialS/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #   _____  ______ _      ______ _______ ______ 
    #  |  __ \|  ____| |    |  ____|__   __|  ____|
    #  | |  | | |__  | |    | |__     | |  | |__   
    #  | |  | |  __| | |    |  __|    | |  |  __|  
    #  | |__| | |____| |____| |____   | |  | |____ 
    #  |_____/|______|______|______|  |_|  |______|
    #
    # ---------------------------------------------------------------------------------
    # Method delete
    # Permit execute command DELETE at the table workingHours
    public function delete($id)
    {
        try {

            $sql = "DELETE FROM working_hours WHERE id = :id;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # Method subtractTH
    # Method to subtract the working hour delete in the total hours
    public function subtractTH($duration, $employee_id)
    {
        try {
            // Consulta SQL para actualizar las total_hours del empleado sumando la duración de la nueva working hour
            $sql = "UPDATE employees SET total_hours = total_hours - :duration WHERE id = :employee_id";

            // Preparar la consulta
            $pdoSt = $this->db->connect()->prepare($sql);

            // Vincular los parámetros
            $pdoSt->bindParam(":duration", $duration, PDO::PARAM_INT);
            $pdoSt->bindParam(":employee_id", $employee_id, PDO::PARAM_INT);

            // Ejecutar la consulta
            $pdoSt->execute();
        } catch (PDOException $e) {
            require_once ("template/partialS/errorDB.php");
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #   _____  ______          _____  
    #  |  __ \|  ____|   /\   |  __ \ 
    #  | |__) | |__     /  \  | |  | |
    #  |  _  /|  __|   / /\ \ | |  | |
    #  | | \ \| |____ / ____ \| |__| |
    #  |_|  \_\______/_/    \_\_____/ 
    #
    # ---------------------------------------------------------------------------------
    # Method read 
    # Get the data of a working hour
    public function read($id)
    {
        try {
            $sql = " SELECT
            id,
            id_employee, 
            id_time_code,
            id_project,
            id_task,
            description,
            duration,
            date_worked
        FROM 
            working_hours
        WHERE id =  :id;";

            # Connect with the database
            $conexion = $this->db->connect();

            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(':id', $id, PDO::PARAM_INT);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();

            return $pdoSt->fetch();

        } catch (PDOException $e) {
            include_once ('template/partials/errorDB.php');
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    # 
    #   _    _ _____  _____       _______ ______ 
    #  | |  | |  __ \|  __ \   /\|__   __|  ____|
    #  | |  | | |__) | |  | | /  \  | |  | |__   
    #  | |  | |  ___/| |  | |/ /\ \ | |  |  __|  
    #  | |__| | |    | |__| / ____ \| |  | |____ 
    #   \____/|_|    |_____/_/    \_\_|  |______|
    #
    # ---------------------------------------------------------------------------------
    # Method update
    # Update the workingHour's data
    public function update(classWorkingHours $workingHours, $id)
    {
        try {
            $sql = " 
                    UPDATE working_hours
                    SET
                        id_time_code=:id_time_code,
                        id_project=:id_project,
                        id_task=:id_task,
                        description=:description,
                        duration=:duration,
                        date_worked=:date_worked,
                        update_at = now()
                    WHERE
                        id=:id
                    LIMIT 1";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(":id_time_code", $workingHours->id_time_code, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":id_project", $workingHours->id_project, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":id_task", $workingHours->id_task, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":description", $workingHours->description, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":duration", $workingHours->duration, PDO::PARAM_INT, 2);
            $pdoSt->bindParam(":date_worked", $workingHours->date_worked, PDO::PARAM_STR);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_STR);

            $pdoSt->execute();

        } catch (PDOException $error) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #
    #     ____  _____  _____  ______ _____  
    #    / __ \|  __ \|  __ \|  ____|  __ \ 
    #   | |  | | |__) | |  | | |__  | |__) |
    #   | |  | |  _  /| |  | |  __| |  _  / 
    #   | |__| | | \ \| |__| | |____| | \ \ 
    #    \____/|_|  \_\_____/|______|_|  \_\ 
    # 
    # ---------------------------------------------------------------------------------
    # Method order
    # Permite ordenar la tabla de workingHours por cualquiera de las columnas del main
    # El criterio de ordenación se establec mediante el número de la columna del select
    public function order(int $criterio)
    {
        try {
            $sql = "
            SELECT 
                wh.id, 
                wh.id_employee, 
                concat_ws(', ', emp.last_name, emp.name) employee_name, 
                tc.time_code, 
                p.project AS project_name,  
                t.description AS task_description,  
                wh.date_worked, 
                wh.duration 
            FROM 
                working_hours wh
            JOIN 
                employees emp ON wh.id_employee = emp.id
            JOIN 
                time_codes tc ON wh.id_time_code = tc.id
            LEFT JOIN 
                projects p ON wh.id_project = p.id
            LEFT JOIN 
                tasks t ON wh.id_task = t.id
            ORDER by :criterio;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":criterio", $criterio, PDO::PARAM_INT);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);

            $pdoSt->execute();

            return $pdoSt;
        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }


    # ---------------------------------------------------------------------------------
    #
    #     ____  _____  _____  ______ _____  
    #    / __ \|  __ \|  __ \|  ____|  __ \ 
    #   | |  | | |__) | |  | | |__  | |__) |
    #   | |  | |  _  /| |  | |  __| |  _  / 
    #   | |__| | | \ \| |__| | |____| | \ \ 
    #    \____/|_|  \_\_____/|______|_|  \_\ 
    # 
    # ---------------------------------------------------------------------------------
    # Method order
    # Permite ordenar la tabla de workingHours por cualquiera de las columnas del main
    # El criterio de ordenación se establec mediante el número de la columna del select
    public function orderEmp(int $criterio, $id)
    {
        try {
            $sql = "
            SELECT 
                wh.id, 
                wh.id_employee, 
                concat_ws(', ', emp.last_name, emp.name) employee_name, 
                tc.time_code, 
                p.project AS project_name,  
                t.description AS task_description,  
                wh.date_worked, 
                wh.duration 
            FROM 
                working_hours wh
            JOIN 
                employees emp ON wh.id_employee = emp.id
            JOIN 
                time_codes tc ON wh.id_time_code = tc.id
            LEFT JOIN 
                projects p ON wh.id_project = p.id
            LEFT JOIN 
                tasks t ON wh.id_task = t.id
            WHERE wh.id_employee = :id
            ORDER by :criterio;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":criterio", $criterio, PDO::PARAM_INT);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);

            $pdoSt->execute();

            return $pdoSt;
        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #   ______ _____ _   _______ ______ _____  
    #  |  ____|_   _| | |__   __|  ____|  __ \ 
    #  | |__    | | | |    | |  | |__  | |__) |
    #  |  __|   | | | |    | |  |  __| |  _  / 
    #  | |     _| |_| |____| |  | |____| | \ \ 
    #  |_|    |_____|______|_|  |______|_|  \_\
    #
    # ---------------------------------------------------------------------------------
    # Método filter
    # Permite filtar la tabla workingHourss a partir de una expresión de búsqueda
    public function filter($expresion)
    {
        try {

            $sql = "
                    SELECT 
                        wh.id, 
                        wh.id_employee, 
                        concat_ws(', ', emp.last_name, emp.name) AS employee_name, 
                        tc.time_code, 
                        p.project AS project_name,  
                        t.description AS task_description,  
                        wo.description AS work_order_description, 
                        wh.date_worked, 
                        wh.duration 
                    FROM 
                        working_hours wh
                    JOIN 
                        employees emp ON wh.id_employee = emp.id
                    JOIN 
                        time_codes tc ON wh.id_time_code = tc.id
                    LEFT JOIN 
                        projects p ON wh.id_project = p.id
                    LEFT JOIN 
                        tasks t ON wh.id_task = t.id
                    WHERE 
                        concat_ws(  
                            ' ',
                            emp.last_name,
                            emp.name,
                            tc.time_code,
                            t.description,
                            wo.description,
                            wh.date_worked,
                            wh.duration
                            )
                        LIKE 
                            :expresion
                    
                    ORDER BY id ASC";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            # enlazamos parámetros con variable
            $expresion = "%" . $expresion . "%";
            $pdoSt->bindValue(':expresion', $expresion, PDO::PARAM_STR);

            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #        
    #   ______ _____ _   _______ ______ _____     ______ __  __ _____  
    #  |  ____|_   _| | |__   __|  ____|  __ \   |  ____|  \/  |  __ \ 
    #  | |__    | | | |    | |  | |__  | |__) |  | |__  | \  / | |__) |
    #  |  __|   | | | |    | |  |  __| |  _  /   |  __| | |\/| |  ___/ 
    #  | |     _| |_| |____| |  | |____| | \ \   | |____| |  | | |     
    #  |_|    |_____|______|_|  |______|_|  \_\  |______|_|  |_|_|     
    # 
    #
    # ---------------------------------------------------------------------------------
    # Method filter
    # Allow an employee to search in the table working hour 
    public function filterEmp($empId, $expresion)
    {
        try {
            $sql = "
                SELECT 
                    wh.id, 
                    wh.id_employee, 
                    concat_ws(', ', emp.last_name, emp.name) AS employee_name, 
                    tc.time_code, 
                    p.project AS project_name,  
                    t.description AS task_description,  
                    wh.date_worked, 
                    wh.duration 
                FROM 
                    working_hours wh
                JOIN 
                    employees emp ON wh.id_employee = emp.id
                JOIN 
                    time_codes tc ON wh.id_time_code = tc.id
                JOIN 
                    projects p ON wh.id_project = p.id
                JOIN 
                    tasks t ON wh.id_task = t.id
                WHERE 
                    wh.id_employee = :empId AND
                    concat_ws(
                        ' ',
                        emp.last_name,
                        emp.name,
                        tc.time_code,
                        t.description,
                        wo.description,
                        wh.date_worked,
                        wh.duration
                    ) LIKE :expresion
                ORDER BY id ASC";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            $expresion = "%" . $expresion . "%";
            $pdoSt->bindValue(':empId', $empId, PDO::PARAM_INT);
            $pdoSt->bindValue(':expresion', $expresion, PDO::PARAM_STR);

            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

}
