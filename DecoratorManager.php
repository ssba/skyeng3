<?php

namespace src\Decorator; // Сменить неймспейс - не ясно это директория или неймспейс

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

class DecoratorManager extends DataProvider // ошибка - для полноценного декоратора нужно реализовать интерфейс/абстрактный класс
{
    // protected - Для наследования
    public $cache;
    public $logger;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     */
    public function __construct($host, $user, $password, CacheItemPoolInterface $cache) // строгая типизация для более понятного кода
    {
        parent::__construct($host, $user, $password); // связь с родителем не очень хорошее решение в декораторе
        $this->cache = $cache;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(array $input) // дататайп
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = parent::get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );

            return $result;
        } catch (Exception $e) { // Добавить узкоспециальные эксепшны для понятного логгирования
            $this->logger->critical('Error');
        }

        return [];
    }

    public function getCacheKey(array $input) // private
    {
        return json_encode($input);//  лучше использовать GUID/UUID
    }
}
