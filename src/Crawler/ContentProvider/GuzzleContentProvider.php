<?php

namespace Crawler\ContentProvider;

use Crawler\ContentProvider;
use GuzzleHttp\Client;

class GuzzleContentProvider implements ContentProvider
{
    /**
     * @var Client
     */
    private $guzzle;

    /**
     * @param Client $guzzle
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @param string $resource
     *
     * @return
     *
     * @throws \RuntimeException in case there are problems while fetching content
     */
    public function fetch($resource)
    {
        try {
            return (string) $this->guzzle->get($resource)->getBody();
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Failed to fetch a resource: "%s"', $resource), 0, $e);
        }
    }
}