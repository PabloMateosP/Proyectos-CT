<?php

class tasksModel extends Model
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
    # Select form table tasks 
    public function get()
    {
        try {
            $sql = "SELECT 
                        tk.id,
                        tk.task,
                        tk.description,
                        pr.project,
                        pr.description projectDescription,
                        tk.created_at
                    FROM 
                        tasks tk
                    JOIN 
                        projects pr ON tk.id_project = pr.id
                    ORDER by tk.id asc;";

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
    #     _____ ______ _______   _____  _____   ____       _ ______ _____ _______ _____    ______ __  __ _____  _      ______     ________ ______ 
    #    / ____|  ____|__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __/ ____|  |  ____|  \/  |  __ \| |    / __ \ \   / /  ____|  ____|
    #   | |  __| |__     | |    | |__) | |__) | |  | |    | | |__ | |       | | | (___    | |__  | \  / | |__) | |   | |  | \ \_/ /| |__  | |__   
    #   | | |_ |  __|    | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |  \___ \   |  __| | |\/| |  ___/| |   | |  | |\   / |  __| |  __|  
    #   | |__| | |____   | |    | |    | | \ \| |__| | |__| | |___| |____   | |  ____) |  | |____| |  | | |    | |___| |__| | | |  | |____| |____ 
    #    \_____|______|  |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_| |_____/   |______|_|  |_|_|    |______\____/  |_|  |______|______|
    #
    # ---------------------------------------------------------------------------------
    public function getProjectEmployee($employee_id)
    {
        try {
            $sql = "SELECT 
                    pr.id 
                FROM 
                    projects pr
                LEFT JOIN 
                    project_employee ep ON pr.id = ep.id_project
                LEFT JOIN 
                    employees e ON ep.id_employee = e.id
                WHERE 
                    e.id = :employee_id
                ORDER BY pr.id asc";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":employee_id", $employee_id, PDO::PARAM_INT);
            $pdoSt->fetchAll(PDO::FETCH_ASSOC);
            $pdoSt->execute();
            // Devolver todas las filas como un array de arrays asociativos
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #
    #     _____ ______ _______   _____  _____   ____       _    _______        _____ _  __
    #    / ____|  ____|__   __| |  __ \|  __ \ / __ \     | | |__   __|/\    / ____| |/ /
    #   | |  __| |__     | |    | |__) | |__) | |  | |    | |    | |  /  \  | (___ | ' / 
    #   | | |_ |  __|    | |    |  ___/|  _  /| |  | |_   | |    | | / /\ \  \___ \|  <  
    #   | |__| | |____   | |    | |    | | \ \| |__| | |__| |    | |/ ____ \ ____) | . \ 
    #    \_____|______|  |_|    |_|    |_|  \_\\____/ \____/|    |_/_/    \_\_____/|_|\_\
    #
    # ---------------------------------------------------------------------------------
    public function getProjTask($id_project)
    {
        try {
            $sql = "SELECT 
                        tk.id,
                        tk.task,
                        tk.description,
                        pr.project,
                        pr.description projectDescription,
                        tk.created_at
                    FROM 
                        tasks tk
                    JOIN 
                        projects pr ON tk.id_project = pr.id
                    WHERE 
                        tk.id_project = :id_project
                    ORDER by tk.id asc;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id_project", $id_project, PDO::PARAM_INT);
            $pdoSt->execute();

            return $pdoSt->fetchAll(PDO::FETCH_ASSOC);

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
            $sql = "
                SELECT 
                    pr.id,
                    pr.project,
                    pr.description,
                    concat_ws(', ', pm.last_name, pm.name) manager_name
                FROM 
                    projects pr
                LEFT JOIN 
                    projectManager_project ppm ON pr.id = ppm.id_project
                LEFT JOIN
                    project_managers pm ON ppm.id_project_manager = pm.id
                ORDER by pr.id asc;";

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
    #   _____ ______ _______   _____  _____   ____       _ ______ _____ _______ _____    _____  ______ _            _______ ______ _____  
    #   / ____|  ____|__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __/ ____|  |  __ \|  ____| |        /\|__   __|  ____|  __ \ 
    #  | |  __| |__     | |    | |__) | |__) | |  | |    | | |__ | |       | | | (___    | |__) | |__  | |       /  \  | |  | |__  | |  | |
    #  | | |_ |  __|    | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |  \___ \   |  _  /|  __| | |      / /\ \ | |  |  __| | |  | |
    #  | |__| | |____   | |    | |    | | \ \| |__| | |__| | |___| |____   | |  ____) |  | | \ \| |____| |____ / ____ \| |  | |____| |__| |
    #   \_____|______|  |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_| |_____/   |_|  \_\______|______/_/    \_\_|  |______|_____/ 
    #                                                                                                                                                                                                                                                                           
    # --------------------------------------------------------------------------------- 
    # Take the projects where an employee is related
    public function get_projectsRelated($id_employee)
    {
        try {
            $sql = "SELECT 
                        pr.id,
                        pr.project,
                        pr.description,
                        concat_ws(', ', pm.last_name, pm.name) manager_name
                    FROM 
                        projects pr
                    LEFT JOIN 
                        projectManager_project ppm ON pr.id = ppm.id_project
                    LEFT JOIN
                        project_managers pm ON ppm.id_project_manager = pm.id
                    LEFT JOIN 
                        project_employee ep ON pr.id = ep.id_project
                    LEFT JOIN 
                        employees e ON ep.id_employee = e.id
                    WHERE 
                        e.id = :employee_id
                    ORDER by pr.id asc;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":employee_id", $id_employee, PDO::PARAM_INT);
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
    #    _____ _____  ______       _______ ______ 
    #   / ____|  __ \|  ____|   /\|__   __|  ____|
    #  | |    | |__) | |__     /  \  | |  | |__   
    #  | |    |  _  /|  __|   / /\ \ | |  |  __|  
    #  | |____| | \ \| |____ / ____ \| |  | |____ 
    #   \_____|_|  \_\______/_/    \_\_|  |______|
    #
    # ---------------------------------------------------------------------------------
    # Method create
    # Allow to create a new task
    public function create(classTask $task)
    {
        try {
            $sql = " INSERT INTO 
                        tasks 
                        (
                            task, 
                            description, 
                            id_project
                        ) 
                        VALUES 
                        ( 
                            :task,
                            :description,
                            :id_project 
                        )";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            // Link the parameters
            //-----------------------------------------------------------------------------------
            $pdoSt->bindParam(":task", $task->task, PDO::PARAM_STR, 10);
            $pdoSt->bindParam(":description", $task->description, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":id_project", $task->id_project, PDO::PARAM_STR, 10);

            // execute
            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
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
            $sql = "SELECT
                        id,
                        task, 
                        description,
                        id_project
                    FROM 
                        tasks
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
    # Update the task's data
    public function update(classTask $task, $id)
    {
        try {
            $sql = "UPDATE tasks
                    SET
                        task=:task,
                        id_project=:id_project,
                        description=:description,
                        update_at = now()
                    WHERE
                        id=:id
                    LIMIT 1";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(":task", $task->task, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":id_project", $task->id_project, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":description", $task->description, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_STR);

            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #
    #   _    _ _____  _____       _______ ______    _____  ______ _            _______ _____ ____  _   _  __          ___    _ 
    #  | |  | |  __ \|  __ \   /\|__   __|  ____|  |  __ \|  ____| |        /\|__   __|_   _/ __ \| \ | | \ \        / / |  | |
    #  | |  | | |__) | |  | | /  \  | |  | |__     | |__) | |__  | |       /  \  | |    | || |  | |  \| |  \ \  /\  / /| |__| |
    #  | |  | |  ___/| |  | |/ /\ \ | |  |  __|    |  _  /|  __| | |      / /\ \ | |    | || |  | | . ` |   \ \/  \/ / |  __  |
    #  | |__| | |    | |__| / ____ \| |  | |____   | | \ \| |____| |____ / ____ \| |   _| || |__| | |\  |    \  /\  /  | |  | |
    #   \____/|_|    |_____/_/    \_\_|  |______|  |_|  \_\______|______/_/    \_\_|  |_____\____/|_| \_|     \/  \/   |_|  |_|
    #
    # ---------------------------------------------------------------------------------
    # Update relation working hour when id_task = x
    public function updateRelationWH($id_task){
        try {

            $sql = "UPDATE working_hours SET id_task=null WHERE id_task=:id_task;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(":id_task", $id_task, PDO::PARAM_INT);

            $pdoSt->execute();
        
        } catch (PDOException $e){
            require_once ("template/partials/errorDB.php");
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
    # Permit execute command DELETE at the table tasks
    public function delete($id)
    {
        try {

            $sql = "DELETE FROM tasks WHERE id = :id;";

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

    # ---------------------------------------------------------------------------------
    #    
    #    ____  _____  _____  ______ _____  
    #   / __ \|  __ \|  __ \|  ____|  __ \ 
    #  | |  | | |__) | |  | | |__  | |__) |
    #  | |  | |  _  /| |  | |  __| |  _  / 
    #  | |__| | | \ \| |__| | |____| | \ \ 
    #   \____/|_|  \_\_____/|______|_|  \_\
    #                             
    # ---------------------------------------------------------------------------------
    # Method order
    # Permit execute command ORDER BY at the table tasks
    public function order($criterio)
    {
        try {
            $sql = "SELECT 
                        tk.task,
                        tk.description,
                        pr.project,
                        pr.description projectDescription,
                        tk.created_at
                    FROM 
                        tasks tk
                    JOIN 
                        projects pr ON tk.id_project = pr.id
                    ORDER by :criterio ;";

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
    #     ____  _____  _____  ______ _____    _______        _____ _  __   ______ __  __ _____  
    #    / __ \|  __ \|  __ \|  ____|  __ \  |__   __|/\    / ____| |/ /  |  ____|  \/  |  __ \ 
    #   | |  | | |__) | |  | | |__  | |__) |    | |  /  \  | (___ | ' /   | |__  | \  / | |__) |
    #   | |  | |  _  /| |  | |  __| |  _  /     | | / /\ \  \___ \|  <    |  __| | |\/| |  ___/ 
    #   | |__| | | \ \| |__| | |____| | \ \     | |/ ____ \ ____) | . \   | |____| |  | | |     
    #    \____/|_|  \_\_____/|______|_|  \_\    |_/_/    \_\_____/|_|\_\  |______|_|  |_|_|      
    #                             
    # ---------------------------------------------------------------------------------
    # Method order
    # Permit execute command ORDER BY at the table tasks
    public function orderTaskEmp($criterio, $id_project)
    {
        try {
            $sql = "SELECT 
                        tk.id,
                        tk.task,
                        tk.description,
                        pr.project,
                        pr.description projectDescription,
                        tk.created_at
                    FROM 
                        tasks tk
                    JOIN 
                        projects pr ON tk.id_project = pr.id
                    WHERE 
                        pr.id = :project_id
                    ORDER by $criterio ASC;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":project_id", $id_project, PDO::PARAM_INT);

            $pdoSt->execute();

            return $pdoSt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            require_once ("template/partials/errorDB.php");
            exit();

        }
    }
}