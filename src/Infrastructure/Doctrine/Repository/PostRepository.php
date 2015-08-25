<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infrastructure\Doctrine\Repository;

use Blog\PostRepository as BlogPostRepository;
use Doctrine\ORM\EntityRepository;
use Blog\Post;

/**
 * This custom Doctrine repository contains some methods which are useful when
 * querying for blog post information.
 * See http://symfony.com/doc/current/book/doctrine.html#custom-repository-classes
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class PostRepository extends EntityRepository implements BlogPostRepository
{
    public function findLatest($limit = Post::NUM_ITEMS)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p')
            ->where('p.publishedAt <= :now')->setParameter('now', new \DateTime())
            ->orderBy('p.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Post[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param int $id
     *
     * @return null|Post
     */
    public function findOneById($id)
    {
        return $this->find($id);
    }

    /**
     * @param $slug
     * @return null|Post
     */
    public function findOneBySlug($slug)
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * @param Post $post
     */
    public function publish(Post $post)
    {
        $em = $this->getEntityManager();
        $em->persist($post);
        $em->flush();
    }

    /**
     * @param Post $post
     */
    public function remove(Post $post)
    {
        $em = $this->getEntityManager();
        $em->remove($post);
        $em->flush();
    }
}