<?php

namespace Infrastructure\Pdo\Repository;

use Blog\Comment;
use Blog\CommentRepository as BlogCommentRepository;

class CommentRepository extends PdoRepository implements BlogCommentRepository
{
    /**
     * @param Comment $comment
     */
    public function post(Comment $comment)
    {
        $statement = $this->pdo->prepare('INSERT INTO Comment(post_id, content, authorEmail, publishedAt) VALUES(:post_id, :content, :authorEmail, :publishedAt)');
        $result = $statement->execute([
            'post_id' => $comment->getPost()->getId(),
            'content' => $comment->getContent(),
            'authorEmail' => $comment->getAuthorEmail(),
            'publishedAt' => $comment->getPublishedAt()->format('Y-m-d H:i:s'),
        ]);

        if (!$result) {
            throw new \RuntimeException('Could not publish the post');
        }
    }
}