<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Comment;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
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