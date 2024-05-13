<?php

class projectsModel extends Model
{

    # ---------------------------------------------------------------------------------  
    #
    #    _____ ______ _______ 
    #   / ____|  ____|__   __|
    #  | |  __| |__     | |   
    #  | | |_ |  __|    | |   
    #  | |__| | |____   | |   
    #   \_____|______|  |_|   
    #                         
    # ---------------------------------------------------------------------------------
    # Method get 
    # Select form table project for the view except employee
    public function get()
    {
        try {
            $sql = "
                SELECT 
                    pr.id,
                    pr.project,
                    pr.description,
                    concat_ws(', ', pm.last_name, pm.name) manager_name,
                    c.name customerName,
                    pr.finish_date
                FROM 
                    projects pr
                LEFT JOIN 
                    projectManager_project ppm ON pr.id = ppm.id_project
                LEFT JOIN
                    project_managers pm ON ppm.id_project_manager = pm.id
                LEFT JOIN 
                    customer_project cp ON pr.id = cp.id_project
                LEFT JOIN 
                    customers c ON cp.id_customer = c.id 
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

    public function getEmpProj($employee_id)
    {
        try {
            $sql = "SELECT 
                        pr.id,
                        pr.project,
                        pr.description,
                        concat_ws(', ', pm.last_name, pm.name) manager_name,
                        c.name customerName,
                        pr.finish_date
                    FROM 
                        projects pr
                    LEFT JOIN 
                        projectManager_project ppm ON pr.id = ppm.id_project
                    LEFT JOIN
                        project_managers pm ON ppm.id_project_manager = pm.id
                    LEFT JOIN 
                        customer_project cp ON pr.id = cp.id_project
                    LEFT JOIN 
                        customers c ON cp.id_customer = c.id 
                    LEFT JOIN 
                        project_employee ep ON pr.id = ep.id_project
                    LEFT JOIN 
                        employees e ON ep.id_employee = e.id
                    WHERE 
                        e.id = :employee_id
                    ORDER by pr.id asc;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":employee_id", $employee_id, PDO::PARAM_INT);
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
    #    _____ ______ _______   ______ __  __ _____  _      ______     ________ ______  _____ 
    #   / ____|  ____|__   __| |  ____|  \/  |  __ \| |    / __ \ \   / /  ____|  ____|/ ____|
    #  | |  __| |__     | |    | |__  | \  / | |__) | |   | |  | \ \_/ /| |__  | |__  | (___  
    #  | | |_ |  __|    | |    |  __| | |\/| |  ___/| |   | |  | |\   / |  __| |  __|  \___ \ 
    #  | |__| | |____   | |    | |____| |  | | |    | |___| |__| | | |  | |____| |____ ____) |
    #   \_____|______|  |_|    |______|_|  |_|_|    |______\____/  |_|  |______|______|_____/ 
    #
    # ---------------------------------------------------------------------------------
    # Method get 
    # Select form table enployee
    public function get_Employees()
    {
        try {
            $sql = "
            SELECT 
                id,
                concat_ws(', ', last_name, name) employee
            FROM 
                employees
            ORDER BY id;";

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
    #    _____  _____   ______         _______  ______ 
    #   / ____||  __ \ |  ____|    /\ |__   __||  ____|
    #  | |     | |__) || |__      /  \   | |   | |__   
    #  | |     |  _  / |  __|    / /\ \  | |   |  __|  
    #  | |____ | | \ \ | |____  / ____ \ | |   | |____ 
    #   \_____||_|  \_\|______|/_/    \_\|_|   |______|
    #
    # ---------------------------------------------------------------------------------
    # Method create
    # Allow to create a new project
    public function create(classProject $project)
    {
        try {
            $sql = "INSERT INTO 
                    projects 
                    (
                        project, 
                        description, 
                        finish_date,
                        id_projectManager,
                        id_customer
                    ) 
                    VALUES 
                    ( 
                        :project, 
                        :description, 
                        :finish_date,
                        :id_projectManager,
                        :id_customer
                    )";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            // Link the parameters
            $pdoSt->bindParam(":project", $project->project, PDO::PARAM_STR, 8);
            $pdoSt->bindParam(":description", $project->description, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":finish_date", $project->finish_date, PDO::PARAM_STR, 20);
            $pdoSt->bindParam(":id_projectManager", $project->id_projectManager, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":id_customer", $project->id_customer, PDO::PARAM_INT, 10);

            // execute
            $pdoSt->execute();

            // Retrieve the ID of the last inserted row
            $lastInsertedId = $conexion->lastInsertId();

            return $lastInsertedId; // Return the ID of the last inserted row

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function insertProjectManagerRelationship($projectId, $projectManagerId)
    {
        try {

            $sql = "INSERT INTO projectManager_project (id_project, id_project_manager) VALUES (:projectId, :projectManagerId)";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":projectId", $projectId, PDO::PARAM_INT);
            $pdoSt->bindParam(":projectManagerId", $projectManagerId, PDO::PARAM_INT);
            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function insertCustomerProjectRelationship($projectId, $customerId)
    {
        try {

            $sql = "INSERT INTO customer_project (id_customer, id_project) VALUES ( :customerId, :projectId)";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":projectId", $projectId, PDO::PARAM_INT);
            $pdoSt->bindParam(":customerId", $customerId, PDO::PARAM_INT);
            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function insertProjectEmployeeRelationship($employee_id, $project_id)
    {
        try {

            $sql = "INSERT INTO project_employee (id_employee, id_project) VALUES (:employee_id, :project_id)";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
            $pdoSt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }
    


    # ---------------------------------------------------------------------------------
    #    
    #  _____  ______          _____  
    # |  __ \|  ____|   /\   |  __ \ 
    # | |__) | |__     /  \  | |  | |
    # |  _  /|  __|   / /\ \ | |  | |
    # | | \ \| |____ / ____ \| |__| |
    # |_|  \_\______/_/    \_\_____/ 
    #
    # ---------------------------------------------------------------------------------
    # function read
    # take the info of an project
    public function read($id)
    {
        try {
            $sql = " 
                SELECT
                    id,
                    project, 
                    description,
                    id_projectManager,
                    id_customer,
                    finish_date
                FROM 
                    projects
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
    # Update the project's data
    public function update(classProject $project_, $id)
    {
        try {
            $sql = " 
                    UPDATE projects
                    SET
                        project=:project,
                        description=:description,
                        id_projectManager=:id_projectManager,
                        id_customer=:id_customer,
                        finish_date=:finish_date,
                        update_at = now()
                    WHERE
                        id=:id
                    LIMIT 1";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(":project", $project_->project, PDO::PARAM_STR, 8);
            $pdoSt->bindParam(":description", $project_->description, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":id_projectManager", $project_->id_projectManager, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":id_customer", $project_->id_customer, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":finish_date", $project_->finish_date, PDO::PARAM_STR, 20);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_STR);

            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function updatePMyC($id_project)
    {
        try {
            $sql = " 
                    UPDATE projects
                    SET
                        id_projectManager=null,
                        id_customer=null
                    WHERE
                        id=:id
                    LIMIT 1";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(":id", $id_project, PDO::PARAM_STR);

            $pdoSt->execute();

        } catch (PDOException $e) {
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
    # Permit execute command DELETE at the table projects
    public function delete($id)
    {
        try {

            $sql = "DELETE FROM projects WHERE id = :id;";

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

    public function deleteRelationE($id_project)
    {
        try {

            $sql = " DELETE FROM project_employee WHERE id_project = :id_project;";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id_project", $id_project, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function deleteRelationEP($id_project, $id_employee)
    {
        try {

            $sql = " DELETE FROM project_employee WHERE id_project = :id_project AND id_employee = :id_employee;";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id_project", $id_project, PDO::PARAM_INT);
            $pdoSt->bindParam(":id_employee", $id_employee, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function deleteRelationPM($id_project)
    {
        try {

            $sql = " DELETE FROM projectManager_project WHERE id_project = :id_project;";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id_project", $id_project, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function deleteRelationC($id_project)
    {
        try {

            $sql = " DELETE FROM customer_project WHERE id_project = :id_project;";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id_project", $id_project, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function deleteTasks($id_project)
    {
        try {

            $sql = "DELETE FROM tasks WHERE id_project = :id_project;";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id_project", $id_project, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }


    # ---------------------------------------------------------------------------------
    #    ____   _____   _____   ______  _____  
    #   / __ \ |  __ \ |  __ \ |  ____||  __ \ 
    #  | |  | || |__) || |  | || |__   | |__) |
    #  | |  | ||  _  / | |  | ||  __|  |  _  / 
    #  | |__| || | \ \ | |__| || |____ | | \ \ 
    #   \____/ |_|  \_\|_____/ |______||_|  \_\
    #
    # ---------------------------------------------------------------------------------
    # Method order
    # Allows you to sort the proj$project table by any of the main columns
    # The sort order is set by the select column number
    public function order(int $criterio)
    {
        try {

            $sql = "
                SELECT 
                    pr.id,
                    pr.project,
                    pr.description,
                    concat_ws(', ', prM.last_name, prM.name) manager_name,
                    c.name customerName,
                    pr.finish_date
                FROM 
                    projects pr
                LEFT JOIN 
                    project_managers prM ON pr.id_projectManager = prM.id
                LEFT JOIN 
                    customers c ON pr.id_customer = c.id
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

    public function orderProjEmp($id_employee, $criterio)
    {
        try {
            $sql = "SELECT 
                        pr.id,
                        pr.project,
                        pr.description,
                        concat_ws(', ', pm.last_name, pm.name) manager_name,
                        c.name customerName,
                        pr.finish_date
                    FROM 
                        projects pr
                    LEFT JOIN 
                        projectManager_project ppm ON pr.id = ppm.id_project
                    LEFT JOIN
                        project_managers pm ON ppm.id_project_manager = pm.id
                    LEFT JOIN 
                        customer_project cp ON pr.id = cp.id_project
                    LEFT JOIN 
                        customers c ON cp.id_customer = c.id 
                    LEFT JOIN 
                        project_employee ep ON pr.id = ep.id_project
                    LEFT JOIN 
                        employees e ON ep.id_employee = e.id
                    WHERE 
                        e.id = :employee_id
                    ORDER by :criterio ;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":employee_id", $id_employee, PDO::PARAM_INT);
            $pdoSt->bindParam(":criterio", $criterio, PDO::PARAM_INT);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # Método filter
    # Permite filtar la tabla employees a partir de una expresión de búsqueda
    public function filter($expresion)
    {
        try {

            $sql = "SELECT 
                        pr.id,
                        pr.project,
                        pr.description,
                        concat_ws(', ', prM.last_name, prM.name) manager_name,
                        c.name customerName,
                        pr.finish_date
                    FROM 
                        projects pr
                    LEFT JOIN 
                        project_managers prM ON pr.id_projectManager = prM.id
                    LEFT JOIN 
                        customers c ON pr.id_customer = c.id
                    WHERE 
                        concat_ws(  
                                    ' ',
                                    pr.id,
                                    pr.project,
                                    pr.description,
                                    prM.last_name,
                                    prM.name,
                                    c.name,
                                    pr.finish_date
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
    #    _____ ______ _______    _____ _    _  _____ _______ ____  __  __ ______ _____   _____ 
    #   / ____|  ____|__   __|  / ____| |  | |/ ____|__   __/ __ \|  \/  |  ____|  __ \ / ____|
    #  | |  __| |__     | |    | |    | |  | | (___    | | | |  | | \  / | |__  | |__) | (___  
    #  | | |_ |  __|    | |    | |    | |  | |\___ \   | | | |  | | |\/| |  __| |  _  / \___ \ 
    #  | |__| | |____   | |    | |____| |__| |____) |  | | | |__| | |  | | |____| | \ \ ____) |
    #   \_____|______|  |_|     \_____|\____/|_____/   |_|  \____/|_|  |_|______|_|  \_\_____/ 
    #
    # ---------------------------------------------------------------------------------
    # get_customers
    # Select from get_customers
    public function get_customers()
    {
        try {
            $sql = "SELECT id, name FROM customers";
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
    #   _____ ______ _______   _____  _____   ____       _ ______ _____ _______   __  __          _   _          _____ ______ _____  
    #   / ____|  ____|__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __| |  \/  |   /\   | \ | |   /\   / ____|  ____|  __ \ 
    #  | |  __| |__     | |    | |__) | |__) | |  | |    | | |__ | |       | |    | \  / |  /  \  |  \| |  /  \ | |  __| |__  | |__) |
    #  | | |_ |  __|    | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |    | |\/| | / /\ \ | . ` | / /\ \| | |_ |  __| |  _  / 
    #  | |__| | |____   | |    | |    | | \ \| |__| | |__| | |___| |____   | |    | |  | |/ ____ \| |\  |/ ____ \ |__| | |____| | \ \ 
    #   \_____|______|  |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_|    |_|  |_/_/    \_\_| \_/_/    \_\_____|______|_|  \_\
    #                                                                                                                                
    # ---------------------------------------------------------------------------------
    # get_customers
    # Select from get_customers
    public function get_projectManagers()
    {
        try {
            $sql = "SELECT id, name, last_name FROM project_managers";
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

    public function getProjectEmployees($projectId)
    {
        try {

            $sql = "SELECT id_employee FROM project_employee WHERE id_project = :id_project";
            $pdoSt = $this->db->connect()->prepare($sql);
            $pdoSt->bindParam(':id_project', $projectId, PDO::PARAM_INT);
            $pdoSt->execute();
            $result = $pdoSt->fetchAll(PDO::FETCH_COLUMN);
            return $result;

        } catch (PDOException $e) {

            require_once ("template/partials/errorDB.php");
            exit();

        }

    }


    public function hasProjectManagerRelation($projectId)
    {
        try {

            $sql = "SELECT COUNT(*) AS count FROM projectManager_project WHERE id_project = :projectId";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
            $pdoSt->execute();
            $result = $pdoSt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;

        } catch (PDOException $e) {

            require_once ("template/partials/errorDB.php");
            exit();

        }
    }

    public function updateProjectManagerRelation($projectManagerId, $projectId)
    {
        try {

            $sql = "UPDATE projectManager_project SET id_project_manager = :projectManagerId WHERE id_project = :projectId";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':projectManagerId', $projectManagerId, PDO::PARAM_INT);
            $pdoSt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
            $pdoSt->execute();

        } catch (PDOException $e) {

            require_once ("template/partials/errorDB.php");
            exit();

        }
    }

    public function hasCustomerRelation($projectId)
    {
        try {
            $sql = "SELECT COUNT(*) AS count FROM customer_project WHERE id_project = :projectId";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
            $pdoSt->execute();
            $result = $pdoSt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;

        } catch (PDOException $e) {

            require_once ("template/partials/errorDB.php");
            exit();

        }
    }

    public function updateCustomerRelation($customerId, $projectId)
    {
        try {

            $sql = "UPDATE customer_project SET id_customer = :customerId WHERE id_project = :projectId";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
            $pdoSt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
            $pdoSt->execute();

        } catch (PDOException $e) {

            require_once ("template/partials/errorDB.php");
            exit();

        }
    }
}