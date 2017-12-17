<?php

namespace User\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class HalResponse extends JsonResponse
{
    protected $dataArray;
    protected $links = [];

    public function __construct(array $data = [], $status = 200, $headers = [])
    {
        $this->dataArray = $data;
        parent::__construct($data, $status, $headers, false);
    }

    /**
     * Add a HAL link which will be
     *
     * @param string $name
     * @param string $url
     */
    public function addLink(string $name, string $url)
    {
        $this->links[$name] = $url;
    }

    public function send()
    {
        $this->setData(
            array_replace(
                $this->dataArray,
                [
                    '_links' => $this->links
                ]
            )
        );
        parent::send();
    }
}
