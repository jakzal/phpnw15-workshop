<?php

namespace Blog;

interface PostRepository
{
    /**
     * @param int $limit
     *
     * @return Post[]
     */
    public function findLatest($limit = Post::NUM_ITEMS);

    /**
     * @param int $id
     *
     * @return null|Post
     */
    public function findOneById($id);

    /**
     * @param $slug
     *
     * @return null|Post
     */
    public function findOneBySlug($slug);

    /**
     * @param Post $post
     */
    public function publish(Post $post);

    /**
     * @param Post $post
     */
    public function remove(Post $post);
}