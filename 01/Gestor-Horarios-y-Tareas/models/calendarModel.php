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

    # ---------------------------------------------------------------------------------
    #
    #     _____ ______ _______ _____  _____ _    _ ______ _____  _    _ _      ______  _____ 
    #    / ____|  ____|__   __/ ____|/ ____| |  | |  ____|  __ \| |  | | |    |  ____|/ ____|
    #   | |  __| |__     | | | (___ | |    | |__| | |__  | |  | | |  | | |    | |__  | (___  
    #   | | |_ |  __|    | |  \___ \| |    |  __  |  __| | |  | | |  | | |    |  __|  \___ \ 
    #   | |__| | |____   | |  ____) | |____| |  | | |____| |__| | |__| | |____| |____ ____) |
    #    \_____|______|  |_| |_____/ \_____|_|  |_|______|_____/ \____/|______|______|_____/ 
    #
    # ---------------------------------------------------------------------------------
    # Method getSchedules 
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

    # ---------------------------------------------------------------------------------
    #
    #     _____ _    _ ______ _____ _  _______ ______ ________   _______  _____ _______ _____ 
    #    / ____| |  | |  ____/ ____| |/ /_   _|  ____|  ____\ \ / /_   _|/ ____|__   __/ ____|
    #   | |    | |__| | |__ | |    | ' /  | | | |__  | |__   \ V /  | | | (___    | | | (___  
    #   | |    |  __  |  __|| |    |  <   | | |  __| |  __|   > <   | |  \___ \   | |  \___ \ 
    #   | |____| |  | | |___| |____| . \ _| |_| |    | |____ / . \ _| |_ ____) |  | |  ____) |
    #    \_____|_|  |_|______\_____|_|\_\_____|_|    |______/_/ \_\_____|_____/   |_| |_____/ 
    #
    # ---------------------------------------------------------------------------------
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

    # --------------------------------------------------------------------------------- 
    #    
    #    _____    __      ________ ________      ________ _   _ _______ 
    #   / ____|  /\ \    / /  ____|  ____\ \    / /  ____| \ | |__   __|
    #  | (___   /  \ \  / /| |__  | |__   \ \  / /| |__  |  \| |  | |   
    #   \___ \ / /\ \ \/ / |  __| |  __|   \ \/ / |  __| | . ` |  | |   
    #   ____) / ____ \  /  | |____| |____   \  /  | |____| |\  |  | |   
    #  |_____/_/    \_\/   |______|______|   \/   |______|_| \_|  |_|   
    #
    # ---------------------------------------------------------------------------------
    # Method saveEvent 
    public function saveEvent($data)
    {
        try {

            $sql = "INSERT INTO `schedule_list` (`title`, `description`, `start_datetime`, `end_datetime`) VALUES (:title, :description, :start_datetime, :end_datetime)";
            $conexion = $this->db->connect();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":title", $data['title'], PDO::PARAM_STR, 20);
            $stmt->bindParam(":description", $data['description'], PDO::PARAM_INT, 9);
            $stmt->bindParam(":start_datetime", $data['start_datetime'], PDO::PARAM_STR, 20);
            $stmt->bindParam(":end_datetime", $data['end_datetime'], PDO::PARAM_INT, 20);

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
    # Method delete
    # Allow to execute command delete
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