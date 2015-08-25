<?php

namespace Blog;

interface CommentRepository
{
    /**
     * @param Comment $comment
     */
    public function post(Comment $comment);
}