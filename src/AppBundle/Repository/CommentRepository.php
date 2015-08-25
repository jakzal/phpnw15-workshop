<?php

namespace AppBundle\Repository;

use Blog\Comment;
use Blog\CommentRepository as BlogCommentRepository;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository implements BlogCommentRepository
{
    /**
     * @param Comment $comment
     */
    public function post(Comment $comment)
    {
        $em = $this->getEntityManager();
        $em->persist($comment);
        $em->flush();
    }
}
