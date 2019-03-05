<?php

namespace VZT\Laravel\VZTLog\Logic;

/** @noinspection PhpHierarchyChecksInspection */

use App\Modules\AppFramework\Exceptions\ZonException;
use VZT\Laravel\VZTLog\Model\VZTLogContextBuilder;
use VZT\Laravel\VZTLog\Model\VZTScopedLogEntry;
use Psr\Log\LoggerInterface;

/**
 * Class VZTLogLogic
 *
 * @package VZT\Laravel\VZTLog\Logic
 *
 */
class VZTLogLogic
{
    //	$a = ['debug', 'info', 'notice', 'warn', 'warning', 'err', 'error', 'crit', 'critical', 'alert', 'emerg', 'emergency'];
    //	foreach ($a as $n) {
    //      echo "function $n(\$message, \$context = []){return \$this->invokeWithLevel('$n', \$message, \$context);}\n";
    //      echo "function {$n}Scope(\$name, \$context = []){return \$this->invokeStackedWithLevel('$n', \$name, \$context);}\n";
    //	}

    const REPORT_LEVELS = ['err', 'error', 'crit', 'critical', 'alert', 'emerg', 'emergency'];

    private $logging_levels;

    /**
     * @var VZTScopedLogEntry[]
     */
    private $scoped_log = [];
    private $logger;
    private $context    = [];

    function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;

        $this->logging_levels = array_flip(VZTScopedLogEntry::LEVELS);
    }

    /**
     * @param \Closure $closure
     * @param string   $name
     * @param array    $context
     *
     * @throws \Exception
     */
    function scopeCapture(\Closure $closure, $name, $context = [])
    {
        $log_entry           = new VZTScopedLogEntry($this, 'info', $name, $context);
        $current_level       = count($this->scoped_log);
        $this->scoped_log [] = $log_entry;
        $exception           = null;
        try {
            $closure();
        } catch (\Exception $ex) {
            $exception = $ex;
        } finally {
            while (count($this->scoped_log) > $current_level) array_pop($this->scoped_log);
            unset($log_entry);
        }
        if ($exception) throw $exception;
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return VZTLogContextBuilder
     */
    function debug($message, $context = [])
    {
        return $this->invokeWithLevel('debug', $message, $context);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    function debugScope($name, $context = [])
    {
        return $this->invokeStackedWithLevel('debug', $name, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return VZTLogContextBuilder
     */
    function info($message, $context = [])
    {
        return $this->invokeWithLevel('info', $message, $context);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    function infoScope($name, $context = [])
    {
        return $this->invokeStackedWithLevel('info', $name, $context);
    }

    /**
     * @param       $message
     * @param array $context
     *
     * @return VZTLogContextBuilder
     */
    function notice($message, $context = [])
    {
        return $this->invokeWithLevel('notice', $message, $context);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    function noticeScope($name, $context = [])
    {
        return $this->invokeStackedWithLevel('notice', $name, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return VZTLogContextBuilder
     */
    function warn($message, $context = [])
    {
        return $this->invokeWithLevel('warn', $message, $context);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    function warnScope($name, $context = [])
    {
        return $this->invokeStackedWithLevel('warn', $name, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return VZTLogContextBuilder
     */
    function warning($message, $context = [])
    {
        return $this->invokeWithLevel('warning', $message, $context);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    function warningScope($name, $context = [])
    {
        return $this->invokeStackedWithLevel('warning', $name, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return VZTLogContextBuilder
     */
    function err($message, $context = [])
    {
        return $this->invokeWithLevel('err', $message, $context);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    function errScope($name, $context = [])
    {
        return $this->invokeStackedWithLevel('err', $name, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return VZTLogContextBuilder
     */
    function error($message, $context = [])
    {
        return $this->invokeWithLevel('error', $message, $context);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    function errorScope($name, $context = [])
    {
        return $this->invokeStackedWithLevel('error', $name, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return VZTLogContextBuilder
     */
    function crit($message, $context = [])
    {
        return $this->invokeWithLevel('crit', $message, $context);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    function critScope($name, $context = [])
    {
        return $this->invokeStackedWithLevel('crit', $name, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return VZTLogContextBuilder
     */
    function critical($message, $context = [])
    {
        return $this->invokeWithLevel('critical', $message, $context);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    function criticalScope($name, $context = [])
    {
        return $this->invokeStackedWithLevel('critical', $name, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return VZTLogContextBuilder
     */
    function alert($message, $context = [])
    {
        return $this->invokeWithLevel('alert', $message, $context);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    function alertScope($name, $context = [])
    {
        return $this->invokeStackedWithLevel('alert', $name, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return VZTLogContextBuilder
     */
    function emerg($message, $context = [])
    {
        return $this->invokeWithLevel('emerg', $message, $context);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    function emergScope($name, $context = [])
    {
        return $this->invokeStackedWithLevel('emerg', $name, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return VZTLogContextBuilder
     */
    function emergency($message, $context = [])
    {
        return $this->invokeWithLevel('emergency', $message, $context);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    function emergencyScope($name, $context = [])
    {
        return $this->invokeStackedWithLevel('emergency', $name, $context);
    }


    /**
     * @param string $level
     * @param string $message
     * @param array  $context
     *
     * @return VZTLogContextBuilder
     */
    private function invokeWithLevel($level, $message, $context = [])
    {
        return new VZTLogContextBuilder($context, function ($context) use ($level, $message) {
            $invoke_native = true;

            $message = json_encode($this->context) . " " . $message;

            if (in_array($level, self::REPORT_LEVELS)) {
                if (array_key_exists('exception', $context)) {
                    if ($context['exception'] instanceof ZonException) {
                        if ($context['exception']->isIntended()) $level = 'info';
                    }
                    $dont_report = config('exceptions.dont_log');
                    if ($dont_report && is_array($dont_report)) {
                        foreach ($dont_report as $class) {
                            if ($context['exception'] instanceof $class) {
                                $level = 'info';
                                break;
                            }
                        }
                    }
                }
            }

            if (!empty($this->scoped_log)) {
                $this->scoped_log[count($this->scoped_log) - 1]->addLog($level, $message, $context);
                $invoke_native = in_array($level, ['err', 'error', 'crit', 'critical', 'alert', 'emerg', 'emergency']);
            }

            if ($invoke_native) {
                if (config('logging.override_output')) {
                    fprintf(STDERR, "%s %s %s\n", $level, json_encode($context), $message);
                    if (@$context['exception']) {
                        fprintf(STDERR, "%s\n%s\n", $context['exception']->getMessage(), $context['exception']->getTraceAsString());
                    }
                }

                {
                    $min_level = strtolower(config('logging.min_logging_level'));
                    if (array_key_exists($min_level, VZTScopedLogEntry::REMAP)) {
                        $min_level = VZTScopedLogEntry::REMAP[$min_level];
                    }
                    $min_level = $this->logging_levels[$min_level];

                    if (array_key_exists($level, VZTScopedLogEntry::REMAP)) {
                        $current_level = VZTScopedLogEntry::REMAP[$level];
                    } else {
                        $current_level = $level;
                    }
                    $current_level = $this->logging_levels[$current_level];

                    if ($current_level >= $min_level) {
                        $this->logger->$level($message, $context);
                    }
                }
            }
        });
    }

    /**
     * @param string $level
     * @param string $name
     * @param array  $context
     *
     * @return mixed
     */
    private function invokeStackedWithLevel($level, $name, $context = [])
    {
        return new class($this, $this->scoped_log, $level, $name, $context)
        {
            private $logs;

            function __construct($logger, &$logs, $level, $name, $context)
            {
                $this->logs    = &$logs;
                $this->logs [] = new VZTScopedLogEntry($logger, $level, $name, $context);
            }

            function __destruct()
            {
                array_pop($this->logs);
            }
        };
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function pushContext($name, $short = null)
    {
        if (is_null($short)) {
            $short = $name;
        }
        $this->info($name);
        return new class($this, $this->context, $short)
        {
            private $context;

            function __construct($logger, &$context, $name)
            {
                $this->context    = &$context;
                $this->context [] = $name;
            }

            function __destruct()
            {
                array_pop($this->context);
            }
        };
    }
}
