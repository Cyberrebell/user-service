<?php

namespace User\Api\GeneratedEntities;

abstract class GeneratedUser extends AbstractGeneratedEntity
{

    /**
     * @var null|string
     */
    protected $user = null;

    /**
     * @var null|string
     */
    protected $email = null;

    /**
     * @var null|string
     */
    protected $password = null;

    /**
     * @var null|string
     */
    protected $firstName = null;

    /**
     * @var null|string
     */
    protected $lastName = null;

    /**
     * @var null|string
     */
    protected $birthday = null;

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
     * @param null|string $email
     */
    public function setEmail(?string $email)
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getEmail() : ?string
    {
        return $this->email;
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
     * @param null|string $firstName
     */
    public function setFirstName(?string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return null|string
     */
    public function getFirstName() : ?string
    {
        return $this->firstName;
    }

    /**
     * @param null|string $lastName
     */
    public function setLastName(?string $lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return null|string
     */
    public function getLastName() : ?string
    {
        return $this->lastName;
    }

    /**
     * @param null|string $birthday
     */
    public function setBirthday(?string $birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return null|string
     */
    public function getBirthday() : ?string
    {
        return $this->birthday;
    }

    /**
     * @param array $data
     */
    public function populate(array $data)
    {
        $this->setUser($data['user'] ?? null);

        $this->setEmail($data['email'] ?? null);

        $this->setPassword($data['password'] ?? null);

        $this->setFirstName($data['first-name'] ?? null);

        $this->setLastName($data['last-name'] ?? null);

        $this->setBirthday($data['birthday'] ?? null);
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return array_filter(
            [
                'user' => $this->getUser(),
                'email' => $this->getEmail(),
                'password' => $this->getPassword(),
                'first-name' => $this->getFirstName(),
                'last-name' => $this->getLastName(),
                'birthday' => $this->getBirthday()
            ],
            function ($value) {
                return $value !== null;
            }
        );
    }


}
