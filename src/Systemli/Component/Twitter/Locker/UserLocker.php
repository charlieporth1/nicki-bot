<?php

namespace Systemli\Component\Twitter\Locker;

use Predis\Client;
use Systemli\Component\Twitter\Model\TweetInterface;

/**
 * @author louis <louis@systemli.org>
 */
class UserLocker implements LockerInterface
{
    const PREFIX = "bot:user";

    /**
     * @var Client
     */
    private $redis;

    /**
     * @param Client $redis
     */
    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    /**
     * {@inheritDoc}
     */
    public function lock(TweetInterface $tweet)
    {
        $this->redis->set($this->getKey($tweet), true);
        $this->redis->expire($this->getKey($tweet), 86400);
    }

    /**
     * {@inheritDoc}
     */
    public function isLocked(TweetInterface $tweet)
    {
        return (bool) $this->redis->get($this->getKey($tweet));
    }

    /**
     * @param TweetInterface $tweet
     *
     * @return string
     */
    private function getKey(TweetInterface $tweet)
    {
        return sprintf("%s:%s", self::PREFIX, $tweet->getUser()->getId());
    }
}