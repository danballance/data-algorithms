<?php

namespace DanBallance\DataAlgorithms;

class Benchmark
{
    protected $name;
    protected $timeStart;
    protected $timeStop;
    protected $memoryStart;
    protected $memoryStop;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function start()
    {
        $this->timeStart = microtime(true);
        $this->memoryStart = memory_get_usage();
    }

    public function stop()
    {
        $this->timeStop = microtime(true);
        $this->memoryStop = memory_get_usage();
    }

    public function stats() : array
    {
        $memoryConsumed = ($this->memoryStop - $this->memoryStart) / (1024*1024); 
        return [
            'timeStart' => $this->timeStart,
            'timeStop' => $this->timeStop,
            'durationSeconds' => $this->timeStop - $this->timeStart,
            'memoryStart' => $this->memoryStart,
            'memoryStop' => $this->memoryStop,
            'memoryConsumed' => $memoryConsumed,
            'memoryMB' => ceil($memoryConsumed)
        ];
    }

    public function report(bool $toConsole = false)
    {
        if (!$toConsole) {
            return $this->makeReport();
        }
        foreach ($this->makeReport() as $line) {
            echo "{$line}\n";
        }
        echo "\n\n";
    }

    protected function makeReport() : array
    {
        $stats = $this->stats();
        return [
            "Benchmark: {$this->name}",
            " | Time running: {$stats['durationSeconds']} seconds",
            " | Memory used: {$stats['memoryMB']}MB"
        ];
    }
}