<?php

namespace User\Service\User;

use ArangoDBClient\Connection;
use ArangoDBClient\Document;
use ArangoDBClient\DocumentHandler;
use ArangoDBClient\Edge;
use ArangoDBClient\EdgeHandler;
use ArangoDBClient\Statement;
use Bcrypt\Bcrypt;
use User\Api\Entities\Authentication;
use User\Api\Entities\Login;
use User\Api\Entities\User;

class AuthenticationStorage
{
    protected $connection;
    protected $documentHandler;
    protected $edgeHandler;

    public function __construct(Connection $connection, DocumentHandler $documentHandler, EdgeHandler $edgeHandler)
    {
        $this->connection = $connection;
        $this->documentHandler = $documentHandler;
        $this->edgeHandler = $edgeHandler;
    }

    public function authenticate(Login $login, string $ip, string $userAgent) : string
    {
        $identity = $this->createIdentity($ip, $userAgent);
        $identityId = $this->documentHandler->save('identity', $identity);

        $statement = new Statement($this->connection, []);
        $statement->setQuery('for u in user filter LOWER(u.user) == LOWER(@user) || LOWER(u.email) == LOWER(@user) return u');
        $statement->bind('user', $login->getUser());
        $users = $statement->execute();
        /* @var $user Document */
        foreach ($users as $user) {
            if ($this->verifyPassword($login->getPassword(), $user->password)) {
                $authentication = new Edge();
                $this->edgeHandler->saveEdge('authentication', $identityId, $user->getHandle(), $authentication);
                break;
            }
        }

        return $identity->token;
    }

    public function getUserData(string $token) : ?Authentication
    {
        $statement = new Statement($this->connection, []);
        $statement->setQuery('for i in identity filter i.token == @token return i');
        $statement->bind('token', $token);
        $identities = $statement->execute();
        /* @var $identity Document */
        $identity = reset($identities->getAll());

        $statement = new Statement($this->connection, []);
        $statement->setQuery(
            'for i in identity filter i._id == @identity' .
            ' for a in authentication filter a._from == i._id' .
            ' for u in user filter u._id == a._to return u'
        );
        $statement->bind('identity', $identity->getId());
        $users = $statement->execute();
        /* @var $user Document */
        $user = reset($users->getAll());

        $userEntity = new User($user->getAll());
        $userEntity->setPassword(null);
        $authentication = new Authentication($identity->getAll());
        $authentication->setUser($userEntity);

        return $authentication;
    }

    public function unauthenticate(string $token)
    {
        $statement = new Statement($this->connection, []);
        $statement->setQuery('for i in identity filter i.token == @token return i');
        $statement->bind('token', $token);
        $identities = $statement->execute();

        foreach ($identities as $identity) {
            $statement = new Statement($this->connection, []);
            $statement->setQuery('for a in authentication filter a._from == @identity return a');
            $statement->bind('identity', $identity->getId());
            $authentications = $statement->execute();

            foreach ($authentications as $authentication) {
                $this->documentHandler->remove($authentication);
            }
            $this->documentHandler->remove($identity);
        }
    }

    /**
     * @param string $passwordToTry
     * @param string $passwordHash
     * @return bool
     */
    protected function verifyPassword(string $passwordToTry, string $passwordHash)
    {
        return Bcrypt::verify(
            $passwordToTry,
            $passwordHash
        );
    }

    /**
     * @param string $ip
     * @param string $userAgent
     * @return Document
     */
    protected function createIdentity(string $ip, string $userAgent) : Document
    {
        $identity = new Document();
        $identity->token = bin2hex(openssl_random_pseudo_bytes(32));
        $identity->ip = $ip;
        $identity->set('user-agent', $userAgent);
        return $identity;
    }
}
