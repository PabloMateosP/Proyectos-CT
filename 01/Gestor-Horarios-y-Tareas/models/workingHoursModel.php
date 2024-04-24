<?php

class workingHoursModel extends Model
{

    # Método get
    # Consulta SELECT a la tabla workingHours
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
                wo.description AS work_order_description,
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
            JOIN 
                work_orders wo ON wh.id_work_order = wo.id
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
            wo.description AS work_order_description,
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
        JOIN 
            work_orders wo ON wh.id_work_order = wo.id
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
            $result = $pdoSt->fetch(PDO::FETCH_ASSOC);
            return $result['email'];

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # Método update para las horas totales del usuario
    # Permite actualizar las horas totales de un usuario mediante la suma de la duración de las horas laborales 
    #
    public function update_total_hours($id)
    {
        try {
            $sql = "
                UPDATE employees e
                SET e.total_hours = (
                    SELECT SUM(wh.duration)
                    FROM working_hours wh
                    WHERE wh.id_employee = e.id
                );
            ";
        } catch (PDOException $error) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

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
                            id_work_order, 
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
                            :id_work_order, 
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
            $pdoSt->bindParam(":id_work_order", $workingHours->id_work_order, PDO::PARAM_STR, 10);
            $pdoSt->bindParam(":id_project", $workingHours->id_project, PDO::PARAM_STR, 10);
            $pdoSt->bindParam(":id_task", $workingHours->id_task, PDO::PARAM_STR, 10);
            $pdoSt->bindParam(":description_", $workingHours->description, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":duration", $workingHours->duration, PDO::PARAM_STR, 2);
            $pdoSt->bindParam(":date_worked", $workingHours->date_worked, PDO::PARAM_STR, 20);

            // execute
            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

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


    public function get_work_ordes()
    {
        try {
            $sql = "SELECT id, work_order, description, order_responsible FROM work_orders";
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
                        projectManager pm ON pr.id_projectManager = pm.id";

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

    public function read($id)
    {

        try {
            $sql = " SELECT
            id,
            id_employee, 
            id_time_code,
            id_work_order,
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

    # Método update
    # Actuliza los detalles de un workingHours una vez editados en el formuliario
    public function update(classworkingHours $workingHours, $id)
    {
        try {
            $sql = " 
                    UPDATE workingHourss
                    SET
                        apellidos=:apellidos,
                        nombre=:nombre,
                        telefono=:telefono,
                        ciudad=:ciudad,
                        dni=:dni,
                        email=:email,
                        update_at = now()
                    WHERE
                        id=:id
                    LIMIT 1";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            //Vinculamos los parámetros
            $pdoSt->bindParam(":nombre", $workingHours->nombre, PDO::PARAM_STR, 30);
            $pdoSt->bindParam(":apellidos", $workingHours->apellidos, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":email", $workingHours->email, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":telefono", $workingHours->telefono, PDO::PARAM_STR, 9);
            $pdoSt->bindParam(":ciudad", $workingHours->ciudad, PDO::PARAM_STR, 30);
            $pdoSt->bindParam(":dni", $workingHours->dni, PDO::PARAM_STR, 9);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);

            $pdoSt->execute();

        } catch (PDOException $error) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }



    # Método update
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
                wo.description AS work_order_description, 
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
            JOIN 
                work_orders wo ON wh.id_work_order = wo.id
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
                    JOIN 
                        projects p ON wh.id_project = p.id
                    JOIN 
                        tasks t ON wh.id_task = t.id
                    JOIN 
                        work_orders wo ON wh.id_work_order = wo.id
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

    public function validateUniqueEmail($email)
    {
        try {

            $sql = "
                SELECT * FROM workingHourss
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

    public function validateUniqueDni($dni)
    {
        try {

            $sql = "
                SELECT * FROM workingHourss
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
}
