<?php
namespace classes;


class UserMapper extends Mapper
{
    public function getAvailableUsers($gamesId) {
        $timestamp = date('Y-m-d H:i:s', time() - 20);
        $sql = "SELECT *
        from users WHERE games_id = :games_id AND `timestamp` > '" . $timestamp . "'";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["games_id" => $gamesId]);
        $results = [];
        if ($result) {
            while ($user = $stmt->fetchObject(UserEntity::class)) {
                $results[] = $user;
                //$results[] = new UserEntity($row);
            }
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
        $sql = "SELECT *
            from users
            where id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["id" => $id]);
        if($result) {
            return $stmt->fetchObject(UserEntity::class);
            //return new UserEntity($stmt->fetch());
        }
    }

    public function save(UserEntity $user) {
        $sql = "insert into users
            (id, games_id, `timestamp`) values
            (:id, :games_id, :timestamp)
            ON DUPLICATE KEY UPDATE games_id = :games_id, `timestamp` = :timestamp";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "id" => $user->id,
            "games_id" => $user->games_id,
            "timestamp" => $user->timestamp
        ]);
        if(!$result) {
            throw new Exception("could not save record");
        }

        return true;
    }
}