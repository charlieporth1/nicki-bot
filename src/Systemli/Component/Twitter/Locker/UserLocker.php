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
     * @param TweetInterface $tweet
     */
    public function lock(TweetInterface $tweet)
    {
        $this->redis->set(sprintf("%s:%s", self::PREFIX, $tweet->getUser()->getId()), true);
    }

    /**
     * {@inheritDoc}
     */
    public function isLocked(TweetInterface $tweet)
    {
        return (bool) $this->redis->get(sprintf("%s:%s", self::PREFIX, $tweet->getUser()->getId()));
    }
}