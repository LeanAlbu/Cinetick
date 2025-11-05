<?php

class CommentModel extends Model {
    public function getCommentsByFilmeId($filme_id) {
        $sql = "SELECT * FROM comments WHERE filme_id = :filme_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['filme_id' => $filme_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createComment($data) {
        $sql = "INSERT INTO comments (filme_id, user_id, comment, rating) VALUES (:filme_id, :user_id, :comment, :rating)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'filme_id' => $data['filme_id'],
            'user_id' => $data['user_id'],
            'comment' => $data['comment'],
            'rating' => $data['rating']
        ]);
    }
}