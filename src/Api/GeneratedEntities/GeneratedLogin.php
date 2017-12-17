<?php

namespace User\Api\GeneratedEntities;

abstract class GeneratedLogin extends AbstractGeneratedEntity
{

    /**
     * @var null|string
     */
    protected $user = null;

    /**
     * @var null|string
     */
    protected $password = null;

    /**
     * @param null|string $user
     */
    public function setUser(?string $user)
    {
        $this->user = $user;
    }

    /**
     * @return null|string
     */
    public function getUser() : ?string
    {
        return $this->user;
    }

    /**
     * @param null|string $password
     */
    public function setPassword(?string $password)
    {
        $this->password = $password;
    }

    /**
     * @return null|string
     */
    public function getPassword() : ?string
    {
        return $this->password;
    }

    /**
     * @param array $data
     */
    public function populate(array $data)
    {
        $this->setUser($data['user'] ?? null);

        $this->setPassword($data['password'] ?? null);
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return array_filter(
            [
                'user' => $this->getUser(),
                'password' => $this->getPassword()
            ],
            function ($value) {
                return $value !== null;
            }
        );
    }


}
