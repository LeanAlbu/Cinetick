<?php

class CommentController extends ApiController {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new CommentModel();
    }

    public function index($filme_id) {
        $comments = $this->commentModel->getCommentsByFilmeId($filme_id);
        $this->sendJsonResponse($comments);
    }

    public function store($filme_id) {
        $data = $this->getJsonInput();
        $data['filme_id'] = $filme_id;
        $data['user_id'] = $_SESSION['user_id']; // Assuming user is logged in

        if ($this->commentModel->createComment($data)) {
            $this->sendJsonResponse(['message' => 'Comment created successfully.'], 201);
        } else {
            $this->sendJsonError('Failed to create comment.', 500);
        }
    }
}
