<?php


class employeesModel extends Model
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
    # Método get
    # Consulta SELECT a la tabla empleados
    public function get()
    {
        try {
            $sql = "

            SELECT 
                id,
                identification,
                concat_ws(', ', last_name, name) employee,
                phone,
                city,
                dni,
                email,
                total_hours
            FROM 
                employees
            ORDER BY id;

            ";

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
    #     _____ ______ _______   ______ __  __ _____  _      ______     ________ ______    ______     __  _____ _____  
    #    / ____|  ____|__   __| |  ____|  \/  |  __ \| |    / __ \ \   / /  ____|  ____|  |  _ \ \   / / |_   _|  __ \ 
    #   | |  __| |__     | |    | |__  | \  / | |__) | |   | |  | \ \_/ /| |__  | |__     | |_) \ \_/ /    | | | |  | |
    #   | | |_ |  __|    | |    |  __| | |\/| |  ___/| |   | |  | |\   / |  __| |  __|    |  _ < \   /     | | | |  | |
    #   | |__| | |____   | |    | |____| |  | | |    | |___| |__| | | |  | |____| |____   | |_) | | |     _| |_| |__| |
    #    \_____|______|  |_|    |______|_|  |_|_|    |______\____/  |_|  |______|______|  |____/  |_|    |_____|_____/ 
    #
    # ---------------------------------------------------------------------------------
    public function getEmployeeById($id)
    {
        try {
            $sql = "SELECT 
                id,
                identification,
                concat_ws(', ', last_name, name) employee,
                phone,
                city,
                dni,
                email,
                total_hours
            FROM 
                employees WHERE id = :id";

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
    #     _____ ______ _______   ______ __  __ _____  _      ______     ________ ______    ______     __  ______ __  __          _____ _      
    #    / ____|  ____|__   __| |  ____|  \/  |  __ \| |    / __ \ \   / /  ____|  ____|  |  _ \ \   / / |  ____|  \/  |   /\   |_   _| |     
    #   | |  __| |__     | |    | |__  | \  / | |__) | |   | |  | \ \_/ /| |__  | |__     | |_) \ \_/ /  | |__  | \  / |  /  \    | | | |     
    #   | | |_ |  __|    | |    |  __| | |\/| |  ___/| |   | |  | |\   / |  __| |  __|    |  _ < \   /   |  __| | |\/| | / /\ \   | | | |     
    #   | |__| | |____   | |    | |____| |  | | |    | |___| |__| | | |  | |____| |____   | |_) | | |    | |____| |  | |/ ____ \ _| |_| |____ 
    #    \_____|______|  |_|    |______|_|  |_|_|    |______\____/  |_|  |______|______|  |____/  |_|    |______|_|  |_/_/    \_\_____|______|
    #
    # ---------------------------------------------------------------------------------
    public function getEmployeeByEmail($email)
    {
        try {

            $sql = "SELECT * FROM employees WHERE email= :email LIMIT 1;";
            $pdo = $this->db->connect();
            $stmt = $pdo->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch();

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }

    # ---------------------------------------------------------------------------------
    #
    #     _____ ______ _______   _____  _____   ____       _ ______ _____ _______ _____ 
    #    / ____|  ____|__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __/ ____|
    #   | |  __| |__     | |    | |__) | |__) | |  | |    | | |__ | |       | | | (___  
    #   | | |_ |  __|    | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |  \___ \ 
    #   | |__| | |____   | |    | |    | | \ \| |__| | |__| | |___| |____   | |  ____) |
    #    \_____|______|  |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_| |_____/ 
    #
    # ---------------------------------------------------------------------------------
    public function getProjects()
    {
        try {

            $sql = "SELECT * FROM projects ORDER BY id;";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }

    public function getProjectEmployees($employeeId)
    {

        try {

            $sql = "SELECT id_project FROM project_employee WHERE id_employee = :id_employee";
            $pdoSt = $this->db->connect()->prepare($sql);
            $pdoSt->bindParam(':id_employee', $employeeId, PDO::PARAM_INT);
            $pdoSt->execute();
            $result = $pdoSt->fetchAll(PDO::FETCH_COLUMN);
            return $result;

        } catch (PDOException $e) {
            include_once ('template/partials/errorDB.php');
            exit();
        }

    }


    # ---------------------------------------------------------------------------------
    #
    #     _____ _____  ______       _______ ______ 
    #    / ____|  __ \|  ____|   /\|__   __|  ____|
    #   | |    | |__) | |__     /  \  | |  | |__   
    #   | |    |  _  /|  __|   / /\ \ | |  |  __|  
    #   | |____| | \ \| |____ / ____ \| |  | |____ 
    #    \_____|_|  \_\______/_/    \_\_|  |______|
    #
    # ---------------------------------------------------------------------------------
    # Método create
    # Permite ejecutar INSERT en la tabla employees
    public function create(classEmployee $employee)
    {
        try {
            $sql = " INSERT INTO 
                        employees 
                        (
                            identification,
                            name, 
                            last_name, 
                            phone, 
                            city, 
                            dni, 
                            email,
                            total_hours
                        ) 
                        VALUES 
                        ( 
                            :identification,
                            :name,
                            :last_name,
                            :phone,
                            :city,
                            :dni,
                            :email,
                            :total_hours
                        )";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            //Vinculamos los parámetros
            $pdoSt->bindValue(':identification', $employee->identification, PDO::PARAM_STR, 0);
            $pdoSt->bindParam(":name", $employee->name, PDO::PARAM_STR, 20);
            $pdoSt->bindParam(":last_name", $employee->last_name, PDO::PARAM_STR, 45);
            $pdoSt->bindParam(":phone", $employee->phone, PDO::PARAM_INT, 9);
            $pdoSt->bindParam(":city", $employee->city, PDO::PARAM_STR, 20);
            $pdoSt->bindParam(":dni", $employee->dni, PDO::PARAM_STR, 9);
            $pdoSt->bindParam(":email", $employee->email, PDO::PARAM_STR, 45);
            $pdoSt->bindParam(":total_hours", $employee->total_hours, PDO::PARAM_INT, 2);

            // Execute
            $pdoSt->execute();

            // Retrieve the ID of the last inserted row
            $lastInsertedId = $conexion->lastInsertId();

            return $lastInsertedId; // Return the ID of the last inserted row

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function createRelationPR($employeeId, $projectId)
    {
        try {

            $sql = "INSERT INTO project_employee (id_employee, id_project) VALUES (:employeeId, :projectId)";
            
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
            $pdoSt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
            $pdoSt->execute();

        } catch (PDOException $e) {

            require_once ("template/partials/errorDB.php");
            throw $e;
            
        }
    }

    # ---------------------------------------------------------------------------------
    #
    #     _____  _____   ____       _ ______ _____ _______   ______ __  __ _____  _      ______     ________ ______ 
    #    |  __ \|  __ \ / __ \     | |  ____/ ____|__   __| |  ____|  \/  |  __ \| |    / __ \ \   / /  ____|  ____|
    #    | |__) | |__) | |  | |    | | |__ | |       | |    | |__  | \  / | |__) | |   | |  | \ \_/ /| |__  | |__   
    #    |  ___/|  _  /| |  | |_   | |  __|| |       | |    |  __| | |\/| |  ___/| |   | |  | |\   / |  __| |  __|  
    #    | |    | | \ \| |__| | |__| | |___| |____   | |    | |____| |  | | |    | |___| |__| | | |  | |____| |____ 
    #    |_|    |_|  \_\\____/ \____/|______\_____|  |_|    |______|_|  |_|_|    |______\____/  |_|  |______|______|
    #
    # ---------------------------------------------------------------------------------
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
    #   _____  ______ _      ______ _______ ______ 
    #  |  __ \|  ____| |    |  ____|__   __|  ____|
    #  | |  | | |__  | |    | |__     | |  | |__   
    #  | |  | |  __| | |    |  __|    | |  |  __|  
    #  | |__| | |____| |____| |____   | |  | |____ 
    #  |_____/|______|______|______|  |_|  |______|
    #                                              
    # ---------------------------------------------------------------------------------                                          
    # Método delete
    # Permite ejecutar comando DELETE en la tabla employees
    public function delete($id)
    {
        try {

            $sql = " DELETE FROM employees WHERE id = :id;";

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

    public function deleteRelation($id_employee)
    {
        try {
            $sql = " DELETE FROM project_employee WHERE id_employee = :id_employee;";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id_employee", $id_employee, PDO::PARAM_INT);
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
            $sql = " DELETE FROM project_employee WHERE id_employee = :id_employee AND id_project = :id_project;";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id_employee", $id_employee, PDO::PARAM_INT);
            $pdoSt->bindParam(":id_project", $id_project, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #
    #    _____  ______          _____  
    #   |  __ \|  ____|   /\   |  __ \ 
    #   | |__) | |__     /  \  | |  | |
    #   |  _  /|  __|   / /\ \ | |  | |
    #   | | \ \| |____ / ____ \| |__| |
    #   |_|  \_\______/_/    \_\_____/ 
    #
    # ---------------------------------------------------------------------------------
    # Método read
    # Obtiene los detalles de un employee a partir del id
    public function read($id)
    {

        try {
            $sql = "SELECT
                        id,
                        identification,
                        last_name, 
                        name,
                        phone,
                        city,
                        dni,
                        email,
                        total_hours
                    FROM 
                        employees
                    WHERE id =  :id;
                            ";

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
    #   _    _  _____   _____         _______  ______ 
    #  | |  | ||  __ \ |  __ \    /\ |__   __||  ____|
    #  | |  | || |__) || |  | |  /  \   | |   | |__   
    #  | |  | ||  ___/ | |  | | / /\ \  | |   |  __|  
    #  | |__| || |     | |__| |/ ____ \ | |   | |____ 
    #   \____/ |_|     |_____//_/    \_\|_|   |______|
    #                                               
    # ---------------------------------------------------------------------------------
    # Method update
    # Update an employee's details once edited in the form
    public function update(classemployee $employee, $id)
    {
        try {
            $sql = " 
                    UPDATE employees
                    SET
                        identification = :identification,
                        last_name=:last_name,
                        name=:name,
                        phone=:phone,
                        city=:city,
                        dni=:dni,
                        email=:email,
                        total_hours=:total_hours,
                        update_at = now()
                    WHERE
                        id=:id
                    LIMIT 1";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(':identification', $employee->identification, PDO::PARAM_STR, 8);
            $pdoSt->bindParam(":name", $employee->name, PDO::PARAM_STR, 20);
            $pdoSt->bindParam(":last_name", $employee->last_name, PDO::PARAM_STR, 45);
            $pdoSt->bindParam(":email", $employee->email, PDO::PARAM_STR, 45);
            $pdoSt->bindParam(":phone", $employee->phone, PDO::PARAM_INT, 9);
            $pdoSt->bindParam(":city", $employee->city, PDO::PARAM_STR, 20);
            $pdoSt->bindParam(":total_hours", $employee->total_hours, PDO::PARAM_STR, 20);
            $pdoSt->bindParam(":dni", $employee->dni, PDO::PARAM_STR, 9);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);

            $pdoSt->execute();

        } catch (PDOException $e) {

            require_once ("template/partials/errorDB.php");
            exit();

        }
    }

    public function updateRelationPR($id_employee, $id_project)
    {
        try {

            $sql = "UPDATE 
                        project_employee 
                    SET 
                        id_project=:id_project
                    WHERE
                        id_employee=:id_employee
                    LIMIT 1;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(":id_employee", $id_employee, PDO::PARAM_INT);
            $pdoSt->bindParam(":id_project", $id_project, PDO::PARAM_INT);

            $pdoSt->execute();

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
    # Allows you to sort the employee table by any of the main columns
    # The sort order was established by the number of the select column
    public function order($criterio)
    {
        try {
            $sql = "
                    SELECT 
                        emp.identification,
                        concat_ws(', ', emp.last_name, emp.name) employee,
                        emp.phone,
                        emp.city,
                        emp.email,
                        emp.total_hours
                    FROM 
                        employees emp
                    ORDER BY
                        :criterio";

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
    #     ______ _____ _   _______ ______ _____  
    #    |  ____|_   _| | |__   __|  ____|  __ \ 
    #    | |__    | | | |    | |  | |__  | |__) |
    #    |  __|   | | | |    | |  |  __| |  _  / 
    #    | |     _| |_| |____| |  | |____| | \ \ 
    #    |_|    |_____|______|_|  |______|_|  \_\
    #                               
    # ---------------------------------------------------------------------------------
    # Método filter
    # Permite filtar la tabla employees a partir de una expresión de búsqueda
    public function filter($expresion)
    {
        try {

            $sql = "
                    SELECT 
                        emp.identification,
                        concat_ws(', ', emp.last_name, emp.name) employee,
                        emp.phone,
                        emp.city,
                        emp.email,
                        emp.total_hours
                    FROM 
                        employees emp
                    WHERE 
                        concat_ws(  
                                    ' ',
                                    id,
                                    identification,
                                    last_name,
                                    name,
                                    phone,
                                    city,
                                    email,
                                    total_hours
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
    #    __      __     _      _____ _____       _______ ______    _    _ _   _ _____ ____  _    _ ______    _____  _    _  ____  _   _ ______ 
    #    \ \    / /\   | |    |_   _|  __ \   /\|__   __|  ____|  | |  | | \ | |_   _/ __ \| |  | |  ____|  |  __ \| |  | |/ __ \| \ | |  ____|
    #     \ \  / /  \  | |      | | | |  | | /  \  | |  | |__     | |  | |  \| | | || |  | | |  | | |__     | |__) | |__| | |  | |  \| | |__   
    #      \ \/ / /\ \ | |      | | | |  | |/ /\ \ | |  |  __|    | |  | | . ` | | || |  | | |  | |  __|    |  ___/|  __  | |  | | . ` |  __|  
    #       \  / ____ \| |____ _| |_| |__| / ____ \| |  | |____   | |__| | |\  |_| || |__| | |__| | |____   | |    | |  | | |__| | |\  | |____ 
    #        \/_/    \_\______|_____|_____/_/    \_\_|  |______|   \____/|_| \_|_____\___\_\\____/|______|  |_|    |_|  |_|\____/|_| \_|______|
    #                                                                                                                                                                                                                                                                                       
    # ---------------------------------------------------------------------------------
    # Method validate unique phone 
    # To validate if a phone introduced is unique
    public function validateUniquePhone($phone)
    {
        try {

            $sql = "
                SELECT * FROM employees
                WHERE phone = :phone
            ";

            # Conectar con la base de datos
            $conexion = $this->db->connect();

            $pdostmt = $conexion->prepare($sql);

            $pdostmt->bindParam(':phone', $phone, PDO::PARAM_INT, 9);
            $pdostmt->execute();

            if ($pdostmt->rowCount() != 0) {
                return FALSE;
            }

            return TRUE;

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }

    # ---------------------------------------------------------------------------------
    #
    #  __      __     _      _____ _____       _______ ______    _    _ _   _ _____ ____  _    _ ______    ______ __  __          _____ _      
    #  \ \    / /\   | |    |_   _|  __ \   /\|__   __|  ____|  | |  | | \ | |_   _/ __ \| |  | |  ____|  |  ____|  \/  |   /\   |_   _| |     
    #   \ \  / /  \  | |      | | | |  | | /  \  | |  | |__     | |  | |  \| | | || |  | | |  | | |__     | |__  | \  / |  /  \    | | | |     
    #    \ \/ / /\ \ | |      | | | |  | |/ /\ \ | |  |  __|    | |  | | . ` | | || |  | | |  | |  __|    |  __| | |\/| | / /\ \   | | | |     
    #     \  / ____ \| |____ _| |_| |__| / ____ \| |  | |____   | |__| | |\  |_| || |__| | |__| | |____   | |____| |  | |/ ____ \ _| |_| |____ 
    #      \/_/    \_\______|_____|_____/_/    \_\_|  |______|   \____/|_| \_|_____\___\_\\____/|______|  |______|_|  |_/_/    \_\_____|______|
    #   
    # ---------------------------------------------------------------------------------
    # Method validate unique email 
    # To validate if the email introduced is unique 
    public function validateUniqueEmail($email)
    {
        try {

            $sql = "
                SELECT * FROM employees
                WHERE email = :email
            ";

            # Conectar con la base de datos
            $conexion = $this->db->connect();

            $pdostmt = $conexion->prepare($sql);

            $pdostmt->bindParam(':email', $email, PDO::PARAM_STR, 50);
            $pdostmt->execute();

            if ($pdostmt->rowCount() != 0) {
                return FALSE;
            }

            return TRUE;

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #  __      __     _      _____ _____       _______ ______    _    _ _   _ _____ ____  _    _ ______    _____  _   _ _____ 
    #  \ \    / /\   | |    |_   _|  __ \   /\|__   __|  ____|  | |  | | \ | |_   _/ __ \| |  | |  ____|  |  __ \| \ | |_   _|
    #   \ \  / /  \  | |      | | | |  | | /  \  | |  | |__     | |  | |  \| | | || |  | | |  | | |__     | |  | |  \| | | |  
    #    \ \/ / /\ \ | |      | | | |  | |/ /\ \ | |  |  __|    | |  | | . ` | | || |  | | |  | |  __|    | |  | | . ` | | |  
    #     \  / ____ \| |____ _| |_| |__| / ____ \| |  | |____   | |__| | |\  |_| || |__| | |__| | |____   | |__| | |\  |_| |_ 
    #      \/_/    \_\______|_____|_____/_/    \_\_|  |______|   \____/|_| \_|_____\___\_\\____/|______|  |_____/|_| \_|_____|
    #
    # ---------------------------------------------------------------------------------
    # Method validate unique dni 
    # To validate if a dni is unique
    public function validateUniqueDni($dni)
    {
        try {

            $sql = "
                SELECT * FROM employees
                WHERE dni = :dni
            ";

            # Conectar con la base de datos
            $conexion = $this->db->connect();

            $pdostmt = $conexion->prepare($sql);

            $pdostmt->bindParam(':dni', $dni, PDO::PARAM_STR, 50);
            $pdostmt->execute();

            if ($pdostmt->rowCount() != 0) {
                return FALSE;
            }

            return TRUE;

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }

    # ---------------------------------------------------------------------------------

    # ---------------------------------------------------------------------------------
    public function isEmployeeRelatedToProject($employeeId, $projectId)
    {
        try {

            $sql = "SELECT COUNT(*) AS count FROM project_employee WHERE id_employee = :employeeId AND id_project = :projectId";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
            $pdoSt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
            $pdoSt->execute();
            $result = $pdoSt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] > 0);

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            throw $e;

        }
    }

}
