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
            $sql = "
            SELECT 
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
            $sql = " SELECT
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
            $sql = " 
                    UPDATE tasks
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

        } catch (PDOException $error) {
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
}