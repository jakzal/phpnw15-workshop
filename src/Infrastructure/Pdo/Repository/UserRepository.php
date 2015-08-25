<?php

namespace Infrastructure\Pdo\Repository;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRepository as BlogUserRepository;

class UserRepository extends PdoRepository implements BlogUserRepository
{
    /**
     * @param string $username
     *
     * @return null|User
     */
    public function findOneByUsername($username)
    {
        $statement = $this->pdo->prepare('SELECT * FROM User WHERE username = :username');
        $statement->execute(['username' => $username]);

        $user = $statement->fetchObject(User::class);

        return $user ? $user : null;
    }

    /**
     * @param User $user
     */
    public function register(User $user)
    {
        $statement = $this->pdo->prepare('INSERT INTO User (username, email, password, roles) VALUES (:username, :email, :password, :roles)');
        $result = $statement->execute([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'roles' => json_encode($user->getRoles()),
        ]);

        if (!$result) {
            throw new \RuntimeException('Could not register the user.');
        }
    }

    /**
     * @param int $maxResults
     *
     * @return User[]
     */
    public function findSorted($maxResults)
    {
        $statement = $this->pdo->prepare('SELECT * FROM User ORDER BY id DESC LIMIT :limit');
        $statement->execute(['limit' => $maxResults]);

        return $statement->fetchAll(\PDO::FETCH_CLASS, User::class);
    }
}