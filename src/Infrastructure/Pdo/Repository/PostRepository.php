<?php

namespace Infrastructure\Pdo\Repository;

use Blog\Comment;
use Blog\Post;
use Blog\PostRepository as BlogPostRepository;

class PostRepository extends PdoRepository implements BlogPostRepository
{
    /**
     * @param int $limit
     *
     * @return Post[]
     */
    public function findLatest($limit = Post::NUM_ITEMS)
    {
        $statement = $this->pdo->prepare('SELECT * FROM Post LIMIT :limit');
        $statement->execute(['limit' => $limit]);

        return $statement->fetchAll(\PDO::FETCH_CLASS, Post::class);
    }

    /**
     * @return Post[]
     */
    public function findAll()
    {
        $statement = $this->pdo->prepare('SELECT * FROM Post');
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_CLASS, Post::class);
    }

    /**
     * @param int $id
     *
     * @return null|Post
     */
    public function findOneById($id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM Post WHERE id = :id');
        $statement->execute(['id' => $id]);

        $post = $statement->fetchObject(Post::class);

        if ($post instanceof Post) {
            $this->fetchComments($post);

            return $post;
        }

        return null;
    }

    /**
     * @param $slug
     *
     * @return null|Post
     */
    public function findOneBySlug($slug)
    {
        $statement = $this->pdo->prepare('SELECT * FROM Post WHERE slug = :slug');
        $statement->execute(['slug' => $slug]);

        $post = $statement->fetchObject(Post::class);

        if ($post instanceof Post) {
            $this->fetchComments($post);

            return $post;
        }

        return null;
    }

    /**
     * @param Post $post
     */
    public function publish(Post $post)
    {
        if ($post->getId()) {
            $this->update($post);
        } else {
            $this->insert($post);
        }
    }

    /**
     * @param Post $post
     */
    public function remove(Post $post)
    {
        $statement = $this->pdo->prepare('DELETE FROM Post WHERE id = :id');
        $result = $statement->execute(['id' => $post->getId()]);

        if (!$result) {
            throw new \RuntimeException('Could not remove the post');
        }
    }

    /**
     * @param Post $post
     */
    private function fetchComments(Post $post)
    {
        $statement = $this->pdo->prepare('SELECT * FROM Comment WHERE post_id = :post_id');
        $statement->execute(['post_id' => $post->getId()]);
        $comments = $statement->fetchAll(\PDO::FETCH_CLASS, Comment::class);
        foreach ($comments as $comment) {
            $post->addComment($comment);
        }
    }

    /**
     * @param Post $post
     */
    private function insert(Post $post)
    {
        $statement = $this->pdo->prepare('INSERT INTO Post(title, slug, summary, content, authorEmail, publishedAt) VALUES(:title, :slug, :summary, :content, :authorEmail, :publishedAt)');
        $result = $statement->execute([
            'title' => $post->getTitle(),
            'slug' => $post->getSlug(),
            'summary' => $post->getSummary(),
            'content' => $post->getContent(),
            'authorEmail' => $post->getAuthorEmail(),
            'publishedAt' => $post->getPublishedAt()->format('Y-m-d H:i:s'),
        ]);

        if (!$result) {
            throw new \RuntimeException('Could not publish the post');
        }
    }

    /**
     * @param Post $post
     */
    private function update(Post $post)
    {
        $statement = $this->pdo->prepare('UPDATE Post SET title=:title, slug=:slug, summary=:summary, content=:content, authorEmail=:authorEmail, publishedAt=:publishedAt WHERE id = :id');
        $result = $statement->execute([
            'title' => $post->getTitle(),
            'slug' => $post->getSlug(),
            'summary' => $post->getSummary(),
            'content' => $post->getContent(),
            'authorEmail' => $post->getAuthorEmail(),
            'publishedAt' => $post->getPublishedAt()->format('Y-m-d H:i:s'),
            'id' => $post->getId(),
        ]);

        if (!$result) {
            throw new \RuntimeException('Could not publish the post');
        }
    }
}