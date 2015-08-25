<?php

namespace AppBundle\Entity;

interface UserRepository
{
    /**
     * @param string $username
     *
     * @return null|User
     */
    public function findOneByUsername($username);

    /**
     * @param int $maxResults
     *
     * @return User[]
     */
    public function findSorted($maxResults);

    /**
     * @param User $user
     */
    public function register(User $user);
}