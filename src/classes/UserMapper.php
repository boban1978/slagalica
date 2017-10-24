<?php
namespace classes;


class UserMapper extends Mapper
{
    public function getUsers() {
        $sql = "SELECT u.id, u.msisdn 
        from users u";
        $stmt = $this->db->query($sql);
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = $stmt->fetchObject(UserEntity::class);
            //$results[] = new UserEntity($row);
        }
        return $results;
    }
    /**
     * Get one ticket by its ID
     *
     * @param int $ticket_id The ID of the ticket
     * @return UserEntity  The user
     */
    public function getUserById($id) {
        $sql = "SELECT u.id, u.msisdn
            from users u
            where u.id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["id" => $id]);
        if($result) {
            return $stmt->fetchObject(UserEntity::class);
            //return new UserEntity($stmt->fetch());
        }
    }

    public function getUserByMsisdn($msisdn) {
        $sql = "SELECT u.id, u.msisdn
            from users u
            where u.msisdn = :msisdn";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["msisdn" => $msisdn]);
        if($result) {
            return $stmt->fetchObject(UserEntity::class);
            //return new UserEntity($stmt->fetch());
        }
    }

    public function save(UserEntity $user) {
        $sql = "insert into users
            (msisdn) values
            (:msisdn)
            ON DUPLICATE KEY UPDATE msisdn = :msisdn";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "msisdn" => $user->getMsisdn(),
        ]);
        if(!$result) {
            throw new Exception("could not save record");
        }

        $id = $this->db->lastInsertId();
        if (!$id) {//updated
            $userNew = $this->getUserByMsisdn($user->getMsisdn());
            $id = $userNew->getId();
        }

        return (int) $id;
    }
}