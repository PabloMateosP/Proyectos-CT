<?php
class RegisterModel extends Model
{

    # Validate user name
    public function validaName($username)
    {
        if ((strlen($username) < 4) || (strlen($username) > 50)) {
            return false;
        }
        return true;

    }

    #Validate password
    public function validatePass($pass)
    {
        if ((strlen($pass) < 4) || (strlen($pass) > 50)) {
            return false;
        }
        return true;
    }

    #Validate unique email
    public function validateEmailUnique($email)
    {

        try {

            $selectSQL = "SELECT * FROM users WHERE email = :email";
            $pdo = $this->db->connect();
            $resultado = $pdo->prepare($selectSQL);
            $resultado->bindParam(':email', $email, PDO::PARAM_STR, 50);
            $resultado->execute();
            if ($resultado->rowCount() > 0)
                return false;
            else
                return true;
        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }


    }

    # Creo nuevo usuario a partir de los datos de formulario de registro
    public function create($name, $email, $pass)
    {
        try {

            $password_encriptado = password_hash($pass, CRYPT_BLOWFISH);

            $insertarsql = "INSERT INTO users VALUES (
             null,
            :nombre,
            :email,
            :pass,
            default,
            default)";

            $pdo = $this->db->connect();
            $stmt = $pdo->prepare($insertarsql);

            $stmt->bindParam(':nombre', $name, PDO::PARAM_STR, 50);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR, 50);
            $stmt->bindParam(':pass', $password_encriptado, PDO::PARAM_STR, 60);

            $stmt->execute();

            # Asignamos rol de registrado
            // Rol que asignaremos por defecto el de empleado 
            $role_id = 4;

            # Obtener id del último usuario insertado
            $ultimo_id = $pdo->lastInsertId();

            // Si el id del empleado es 1, el rol será 4
            if ($ultimo_id == 1) {
                $role_id = 4;
            }

            $insertarsql = "INSERT INTO roles_users VALUES (
            null,
            :user_id,
            :role_id,
            default,
            default)";

            $stmt = $pdo->prepare($insertarsql);
            $stmt->bindParam(':user_id', $ultimo_id);
            $stmt->bindParam(':role_id', $role_id);
            $stmt->execute();

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }


}
