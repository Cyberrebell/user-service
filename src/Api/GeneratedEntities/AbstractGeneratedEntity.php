<?php

namespace User\Api\GeneratedEntities;

abstract class AbstractGeneratedEntity implements \JsonSerializable
{

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->populate($data);
    }

    /**
     * @param array $data
     */
    abstract public function populate(array $data);
    /**
     * @return array
     */
    abstract public function toArray() : array;
    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }


}
