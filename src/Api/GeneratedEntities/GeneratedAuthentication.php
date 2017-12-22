<?php

namespace User\Api\GeneratedEntities;

abstract class GeneratedAuthentication extends AbstractGeneratedEntity
{

    /**
     * @var null|string
     */
    protected $ip = null;

    /**
     * @var null|string
     */
    protected $userAgent = null;

    /**
     * @var null|\User\Api\Entities\User
     */
    protected $user = null;

    /**
     * @param null|string $ip
     */
    public function setIp(?string $ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return null|string
     */
    public function getIp() : ?string
    {
        return $this->ip;
    }

    /**
     * @param null|string $userAgent
     */
    public function setUserAgent(?string $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return null|string
     */
    public function getUserAgent() : ?string
    {
        return $this->userAgent;
    }

    /**
     * @param null|\User\Api\Entities\User $user
     */
    public function setUser(?\User\Api\Entities\User $user)
    {
        $this->user = $user;
    }

    /**
     * @return null|\User\Api\Entities\User
     */
    public function getUser() : ?\User\Api\Entities\User
    {
        return $this->user;
    }

    /**
     * @param array $data
     */
    public function populate(array $data)
    {
        $this->setIp($data['ip'] ?? null);

        $this->setUserAgent($data['user-agent'] ?? null);

        $this->setUser(new \User\Api\Entities\User($data['user'] ?? []));
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return array_filter(
            [
                'ip' => $this->getIp(),
                'user-agent' => $this->getUserAgent(),
                'user' => $this->getUser()->toArray()
            ],
            function ($value) {
                return $value !== null;
            }
        );
    }


}
