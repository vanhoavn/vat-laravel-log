<?php
/**
 * User: vhnvn
 * Date: 3/19/18
 * Time: 8:27 PM
 */

namespace VZT\Laravel\VZTLog\Model;


use VZT\Laravel\VZTLog\Logic\VZTLogLogic;

class VZTScopedLogEntry
{
    const LEVELS = ['debug', 'info', 'notice', 'warn', 'err', 'crit', 'alert', 'emerg'];

    const REMAP = [
        'warning'   => 'warn',
        'error'     => 'err',
        'critical'  => 'crit',
        'emergency' => 'emerg',
    ];


    static $stack_level = 0;
    private $name;
    private $logger;
    private $level;

    private $logs;


    private $start;
    private $end_called;
    /**
     * @var array
     */
    private $context;

    /**
     * VZTScopedLogEntry constructor.
     *
     * @param VZTLogLogic $logger
     * @param string      $level
     * @param             $name
     * @param array       $context
     */
    public function __construct($logger, $level, $name, $context = [])
    {
        $this->logger = $logger;
        $this->name = $name;

        $this->level = $level;
        $this->context = $context;

        $this->start = microtime(true);
        $this->end_called = false;

        self::$stack_level++;

        $this->logStart();
    }

    public function __destruct()
    {
        self::$stack_level--;
        $this->logEnd();
    }

    public function addLog($level, $message, $context = [])
    {
        $this->logs [] = ['level' => $level, 'message' => $message, 'context' => $context];
        if (array_search($level, self::LEVELS, true) > array_search($this->level, self::LEVELS, true)) {
            $this->level = $level;
        }
    }

    protected function logStart()
    {
        $this->addLog($this->level, "Start {$this->name}", $this->context);
    }

    protected function logEnd()
    {
        if ($this->end_called) return;
        $this->end_called = true;
        $level = $this->level;
        if (!$this->context) $this->context = [];
        $this->context['logs'] = $this->logs;
        $this->logger->$level("Scope {$this->name}", $this->context);
    }

    public function toArray()
    {
        if (!$this->context) $this->context = [];
        $this->context['logs'] = $this->logs;
        return [
            'scope'   => $this->name,
            'level'   => $this->level,
            'context' => $this->context,
        ];
    }
}