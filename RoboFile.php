<?php
use Robo\Tasks;

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends Tasks
{

    /**
     * Default options for test tasks
     */
    const DEFAULT_OPTIONS_TEST = [
        'coverage' => false,
        'dox' => false,
    ];

    /**
     * Run all test suites
     *
     * @param array $opts
     * @option $dox Output agile documentation
     * @option $coverage Generate test coverage report
     */
    public function test($opts = self::DEFAULT_OPTIONS_TEST)
    {
        $task = $this->taskPHPUnit();

        self::applyTestOptions($task, $opts);

        $task->run();
    }

    /**
     * Run unit tests
     *
     * @param array $opts
     * @option $dox Output agile documentation
     * @option $coverage Generate test coverage report
     */
    public function testUnit($opts = self::DEFAULT_OPTIONS_TEST)
    {
        $task = $this->taskPHPUnit()
            ->option('testsuite', 'Unit');

        self::applyTestOptions($task, $opts);

        $task->run();
    }

    /**
     * Run integration tests
     *
     * @param array $opts
     * @option $dox Output agile documentation
     * @option $coverage Generate test coverage report
     */
    public function testIntegration($opts = self::DEFAULT_OPTIONS_TEST)
    {
        $task = $this->taskPHPUnit()
            ->option('testsuite', 'Integration');

        self::applyTestOptions($task, $opts);

        $task->run();
    }

    private function applyTestOptions($task, $opts)
    {
        if ($opts['dox']) {
            $task->option('testdox');
        }

        if ($opts['coverage']) {
            $task->option('coverage-html', './tests/coverage');
        }

        return $task;
    }
}
