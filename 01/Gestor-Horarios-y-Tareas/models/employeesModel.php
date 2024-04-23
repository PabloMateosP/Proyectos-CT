<?php


class employeesModel extends Model
{

    # Método get
    # Consulta SELECT a la tabla empleados
    public function get()
    {
        try {
            $sql = "

            SELECT 
                id,
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

    public function getEmployeeById($id)
    {
        try {
            $sql = "SELECT 
                id,
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
    
    public function getEmployeeByEmail($email) {
        try {

            $sql = "SELECT * FROM employees WHERE email= :email LIMIT 1";
            $pdo = $this->db->connect();
            $stmt = $pdo->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetch();

        }  catch (PDOException $e) {
            
            include_once('template/partials/errorDB.php');
            exit();

        }
    }

    # Método create
    # Permite ejecutar INSERT en la tabla employees
    public function create(classEmployee $employee)
    {
        try {
            $sql = " INSERT INTO 
                        employees 
                        (
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
            $pdoSt->bindParam(":name", $employee->name, PDO::PARAM_STR, 20);
            $pdoSt->bindParam(":last_name", $employee->last_name, PDO::PARAM_STR, 45);
            $pdoSt->bindParam(":phone", $employee->phone, PDO::PARAM_INT, 9);
            $pdoSt->bindParam(":city", $employee->city, PDO::PARAM_STR, 20);
            $pdoSt->bindParam(":dni", $employee->dni, PDO::PARAM_STR, 9);
            $pdoSt->bindParam(":email", $employee->email, PDO::PARAM_STR, 45);
            $pdoSt->bindParam(":total_hours", $employee->total_hours, PDO::PARAM_INT, 2);

            // Execute
            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

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

        } catch (PDOException $error) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # Método getemployee
    # Obtiene los detalles de un employee a partir del id
    public function getemployee($id)
    {
        try {
            $sql = " 
                    SELECT     
                        id,
                        last_name,
                        name,
                        telefono,
                        ciudad,
                        dni,
                        email
                    FROM  
                        employees  
                    WHERE
                        id = :id";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt->fetch();

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
            last_name, 
            name,
            telefono,
            ciudad,
            dni,
            email
        FROM 
            employees
        WHERE id =  :id;
                ";

            # Conectar con la base de datos
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
    # Actuliza los detalles de un employee una vez editados en el formuliario
    public function update(classemployee $employee, $id)
    {
        try {
            $sql = " 
                    UPDATE employees
                    SET
                        last_name=:last_name,
                        name=:name,
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
            $pdoSt->bindParam(":name", $employee->name, PDO::PARAM_STR, 30);
            $pdoSt->bindParam(":last_name", $employee->last_name, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":email", $employee->email, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":telefono", $employee->telefono, PDO::PARAM_STR, 9);
            $pdoSt->bindParam(":ciudad", $employee->ciudad, PDO::PARAM_STR, 30);
            $pdoSt->bindParam(":dni", $employee->dni, PDO::PARAM_STR, 9);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);

            $pdoSt->execute();

        } catch (PDOException $error) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # Método update
    # Permite ordenar la tabla de employee por cualquiera de las columnas del main
    # El criterio de ordenación se establec mediante el número de la columna del select
    public function order(int $criterio)
    {
        try {
            $sql = "
                    SELECT 
                        id,
                        concat_ws(', ', last_name, name) employee,
                        telefono,
                        ciudad,
                        dni,
                        email
                    FROM 
                        employees 
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

    # Método filter
    # Permite filtar la tabla employees a partir de una expresión de búsqueda
    public function filter($expresion)
    {
        try {

            $sql = "
                    SELECT 
                        id,
                        concat_ws(', ', last_name, name) employee,
                        telefono,
                        ciudad,
                        dni,
                        email
                    FROM 
                        employees 
                    WHERE 
                        concat_ws(  
                                    ' ',
                                    id,
                                    last_name,
                                    name,
                                    telefono,
                                    ciudad,
                                    dni,
                                    email
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
}
