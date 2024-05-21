<?php

class calendarModel extends Model
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
    # Method to take the information about all the customers
    public function get()
    {
        try {
            $sql = "SELECT 
                        id, 
                        title,
                        description,
                        start_datetime,
                        end_datetime
                    FROM 
                        schedule_list;";

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

    public function getSchedules()
    {
        try {
            $sql = "SELECT 
                    id, 
                    title,
                    description,
                    start_datetime,
                    end_datetime
                FROM 
                    schedule_list;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();

            $result = [];
            while ($row = $pdoSt->fetch()) {
                $result[] = $row;
            }

            return $result;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function checkIfExists($start_datetime)
    {
        try {

            $sql = "SELECT * FROM `schedule_list` WHERE `start_datetime` BETWEEN DATE_ADD(:start_datetime, INTERVAL -15 MINUTE) AND DATE_ADD(:start_datetime2, INTERVAL 15 MINUTE)";

            $conexion = $this->db->connect();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":start_datetime", $start_datetime, PDO::PARAM_STR, 20);
            $stmt->bindParam(":start_datetime2", $start_datetime, PDO::PARAM_STR, 20);
            $stmt->execute();
            $result = $stmt->rowCount() > 0;

            return $result;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }

    }

    public function saveEvent($data)
    {
        try {

            if (empty($data['id'])) {

                $sql = "INSERT INTO `schedule_list` (`title`, `description`, `start_datetime`, `end_datetime`) VALUES (:title, :description, :start_datetime, :end_datetime)";
                $conexion = $this->db->connect();
                $stmt = $conexion->prepare($sql);
                $stmt->bindParam(":title", $data['title'], PDO::PARAM_STR, 20);
                $stmt->bindParam(":description", $data['description'], PDO::PARAM_INT, 9);
                $stmt->bindParam(":start_datetime", $data['start_datetime'], PDO::PARAM_STR, 20);
                $stmt->bindParam(":end_datetime", $data['end_datetime'], PDO::PARAM_INT, 20);

            } else {

                $sql = "UPDATE `schedule_list` SET `title` = :title, `description` = :description, `start_datetime` = :start_datetime, `end_datetime` = :end_datetime WHERE `id` = :id";
                $conexion = $this->db->connect();
                $stmt = $conexion->prepare($sql);
                $stmt->bindParam(":title", $data['title'], PDO::PARAM_STR, 20);
                $stmt->bindParam(":description", $data['description'], PDO::PARAM_INT, 9);
                $stmt->bindParam(":start_datetime", $data['start_datetime'], PDO::PARAM_STR, 20);
                $stmt->bindParam(":end_datetime", $data['end_datetime'], PDO::PARAM_INT, 20);
                $stmt->bindParam(":id", $data['id'], PDO::PARAM_INT, 20);

            }
            return $stmt->execute();

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
    # Permite ejecutar comando DELETE en la tabla customers
    public function delete($id)
    {
        try {

            $sql = "DELETE FROM schedule_list WHERE id = :id;";

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