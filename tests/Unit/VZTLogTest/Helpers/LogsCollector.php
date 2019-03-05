<?php
/**
 * User: vhnvn
 * Date: 3/19/18
 * Time: 9:49 PM
 */

namespace Tests\Unit\VZTLogTest\Helpers;

use Psr\Log\LoggerInterface;

class LogsCollector implements LoggerInterface
{
    public $history = [];

    public function __call($name, $args)
    {
        $this->history [] = ['name' => $name, 'args' => $args];
    }

    public function emergency($message, array $context = [])
    {
        $this->history [] = ['name' => __FUNCTION__, 'args' => [$message, $context]];
    }

    public function alert($message, array $context = [])
    {
        $this->history [] = ['name' => __FUNCTION__, 'args' => [$message, $context]];
    }

    public function critical($message, array $context = [])
    {
        $this->history [] = ['name' => __FUNCTION__, 'args' => [$message, $context]];
    }

    public function error($message, array $context = [])
    {
        $this->history [] = ['name' => __FUNCTION__, 'args' => [$message, $context]];
    }

    public function warning($message, array $context = [])
    {
        $this->history [] = ['name' => __FUNCTION__, 'args' => [$message, $context]];
    }

    public function notice($message, array $context = [])
    {
        $this->history [] = ['name' => __FUNCTION__, 'args' => [$message, $context]];
    }

    public function info($message, array $context = [])
    {
        $this->history [] = ['name' => __FUNCTION__, 'args' => [$message, $context]];
    }

    public function debug($message, array $context = [])
    {
        $this->history [] = ['name' => __FUNCTION__, 'args' => [$message, $context]];
    }

    public function log($level, $message, array $context = [])
    {
        $this->history [] = ['name' => __FUNCTION__, 'args' => [$message, $context]];
    }
}