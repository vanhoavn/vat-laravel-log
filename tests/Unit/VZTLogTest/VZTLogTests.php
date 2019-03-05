<?php
/**
 * User: vhnvn
 * Date: 3/19/18
 * Time: 9:45 PM
 */

namespace Tests\Unit\VZTLogTest;


use App\Modules\Services\VZTLog\Logic\VZTLogLogic;
use App\Modules\Services\VZTLog\Model\VZTScopedLogEntry;
use Psr\Log\LoggerInterface;
use Tests\TestCase;
use Tests\Unit\VZTLogTest\Helpers\LogsCollector;

class VZTLogTests extends TestCase
{
    public function testNormalLoggingLevels()
    {
        $log_collector = new LogsCollector();
        app()->bind(LoggerInterface::class, function ($app) use ($log_collector) {
            return $log_collector;
        });

        /** @var VZTLogLogic $vzt_log */
        $vzt_log = app(VZTLogLogic::class);

        $expected = [];
        foreach (VZTScopedLogEntry::LEVELS as $level) {
            $vzt_log->$level("msg[[$level]]");
            $expected[] = [
                'name' => $level,
                'args' => [
                    "[] msg[[$level]]",
                    [],
                ],
            ];
        }

        $this->assertEquals($expected, $log_collector->history);
    }

    public function testScopedLoggingSimple()
    {
        $log_collector = new LogsCollector();
        app()->bind(LoggerInterface::class, function ($app) use ($log_collector) {
            return $log_collector;
        });

        /** @var VZTLogLogic $vzt_log */
        $vzt_log = app(VZTLogLogic::class);

        $ent = [
            'name' => 'warn',
            'args' => [
                '[] Scope debug-0',
                [
                    'logs' => [
                        [
                            'level'   => 'warn',
                            'message' => 'Start debug-0',
                            'context' => [],
                        ],
                        [
                            'level'   => 'info',
                            'message' => '[] test',
                            'context' => [
                                3 => 1,
                                'tags' => ['tag1', 'tag2', 'tag3'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $debug = $vzt_log->warnScope("debug-0");

        $vzt_log->info('test', [3 => 1])->withTag('tag1')->withTag(['tag2', 'tag3']);

        unset($debug);

        $this->assertEquals([$ent], $log_collector->history);
    }

    public function testScopedLoggingLevels()
    {
        $log_collector = new LogsCollector();
        app()->bind(LoggerInterface::class, function ($app) use ($log_collector) {
            return $log_collector;
        });

        /** @var VZTLogLogic $vzt_log */
        $vzt_log = app(VZTLogLogic::class);

        $debug = $vzt_log->debugScope("debug-0");

        $ent = [
            'name' => 'warn',
            'args' => [
                '[] Scope debug-0',
                [
                    'logs' => [
                    ],
                ],
            ],
        ];

        $scoped_invoke = function ($lvl, $nn, &$logs) use ($vzt_log, &$scoped_invoke) {
            $logs[] = [
                'level'   => $lvl > 1 ? 'warn' : 'debug',
                'message' => "Start $nn",
                'context' => [
                ],
            ];

            $vzt_log->info("check $nn");
            $logs[] = [
                'level'   => 'info',
                'message' => "[] check $nn",
                'context' => [
                ],
            ];
            $scoped = $vzt_log->warnScope("warn-$lvl");

            $ent = [
                'level'   => 'warn',
                'message' => "[] Scope warn-$lvl",
                'context' => ['logs' => []],
            ];
            if ($lvl < 3) {
                $scoped_invoke($lvl + 1, "warn-$lvl", $ent['context']['logs']);
            } else {
                $ent['context']['logs'][] = [
                    'level'   => 'warn',
                    'message' => "Start warn-$lvl",
                    'context' => [],
                ];
            }
            $logs[] = $ent;
        };

        $scoped_invoke(1, 'debug-0', $ent['args'][1]['logs']);

        $this->assertEquals([], $log_collector->history);
        unset($debug);
        $this->assertEquals([$ent], $log_collector->history);
    }
}