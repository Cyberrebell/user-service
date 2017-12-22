<?php

namespace User\Service\User;

use ArangoDBClient\DocumentHandler;
use Bcrypt\Bcrypt;
use User\Api\Entities\User;

class UserStorage
{
    protected $documentHandler;

    public function __construct(DocumentHandler $documentHandler)
    {
        $this->documentHandler = $documentHandler;
    }

    /**
     * @param User $user
     * @return null|string
     * @throws \ArangoDBClient\Exception
     */
    public function createUser(User $user) : ?string
    {
        $this->cryptUserPassword($user);

        return $this->documentHandler->save('user', $user->toArray());
    }

    /**
     * @param string $userId
     * @return null|User
     * @throws \ArangoDBClient\Exception
     */
    public function readUser(string $userId) : ?User
    {
        $userDoc = $this->documentHandler->getById('user', $userId);
        $user = new User($userDoc->getAll());
        $user->setId($userDoc->getKey());
        $user->setPassword(null);
        return $user;
    }

    /**
     * @param string $userId
     * @param User $user
     * @throws \ArangoDBClient\ClientException
     * @throws \ArangoDBClient\Exception
     */
    public function updateUser(string $userId, User $user)
    {
        $this->cryptUserPassword($user);

        $userDoc = $this->documentHandler->getById('user', $userId);
        foreach ($user->toArray() as $name => $value) {
            $userDoc->set($name, $value);
        }
        $this->documentHandler->updateById('user', $userId, $userDoc);
    }

    /**
     * @param string $userId
     * @throws \ArangoDBClient\Exception
     */
    public function deleteUser(string $userId)
    {
        $this->documentHandler->removeById('user', $userId);
    }

    protected function cryptUserPassword(User $user)
    {
        $user->setPassword(
            Bcrypt::encrypt(
                $user->getPassword()
            )
        );
    }
}
