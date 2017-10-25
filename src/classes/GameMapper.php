<?php
namespace classes;


class GameMapper extends Mapper
{
    public function getGames() {
        $sql = "SELECT *
        from games";
        $stmt = $this->db->query($sql);
        $results = [];
        while($user = $stmt->fetchObject(GameEntity::class)) {
            $results[] = $user;
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
    public function getGameById($id) {
        $sql = "SELECT *
            from games
            where id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["id" => $id]);
        if($result) {
            return $stmt->fetchObject(GameEntity::class);
            //return new UserEntity($stmt->fetch());
        }
    }

}