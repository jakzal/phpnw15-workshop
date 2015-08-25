<?php

namespace Infrastructure\Cache\Repository;

use Blog\Post;
use Blog\PostRepository as BlogPostRepository;
use Doctrine\Common\Cache\Cache;

class PostRepository implements BlogPostRepository
{
    /**
     * @var BlogPostRepository
     */
    private $postRepository;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @param BlogPostRepository $postRepository
     * @param Cache              $cache
     */
    public function __construct(BlogPostRepository $postRepository, Cache $cache)
    {
        $this->postRepository = $postRepository;
        $this->cache = $cache;
    }

    /**
     * @param int $limit
     *
     * @return Post[]
     */
    public function findLatest($limit = Post::NUM_ITEMS)
    {
        $key = $this->getLatestPostsCacheKey();
        $latest = unserialize($this->cache->fetch($key));

        if (!$latest) {
            $latest = $this->postRepository->findLatest($limit);

            $this->cache->save($key, serialize($latest), 300);
        }

        return $latest;;
    }

    /**
     * @return Post[]
     */
    public function findAll()
    {
        return $this->postRepository->findAll();
    }

    /**
     * @param int $id
     *
     * @return null|Post
     */
    public function findOneById($id)
    {
        return $this->postRepository->findOneById($id);
    }

    /**
     * @param $slug
     *
     * @return null|Post
     */
    public function findOneBySlug($slug)
    {
        $key = $this->getPostCacheKey($slug);
        $post = unserialize($this->cache->fetch($key));

        if (!$post instanceof Post) {
            $post = $this->postRepository->findOneBySlug($slug);

            $this->cache->save($key, serialize($post), 300);
        }

        return $post;
    }

    /**
     * @param Post $post
     */
    public function publish(Post $post)
    {
        $this->postRepository->publish($post);
        $this->cache->delete($this->getPostCacheKey($post->getSlug()));
    }

    /**
     * @param Post $post
     */
    public function remove(Post $post)
    {
        $this->postRepository->remove($post);
        $this->cache->delete($this->getPostCacheKey($post->getSlug()));
    }

    /**
     * @param string $slug
     *
     * @return string
     */
    private function getPostCacheKey($slug)
    {
        return 'post.' . sha1($slug);
    }

    /**
     * @return string
     */
    private function getLatestPostsCacheKey()
    {
        return 'post.latest';
    }
}