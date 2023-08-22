<?php

namespace Orchestra\Canvas\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Orchestra\Canvas\Processors\GeneratesListenerCode;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * @see https://github.com/laravel/framework/blob/10.x/src/Illuminate/Foundation/Console/ListenerMakeCommand.php
 */
#[AsCommand(name: 'make:listener', description: 'Create a new event listener class')]
class Listener extends Generator
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event listener class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected string $type = 'Listener';

    /**
     * Generator processor.
     *
     * @var string
     */
    protected $processor = GeneratesListenerCode::class;

    /**
     * Get the stub file for the generator.
     */
    public function getStubFile(): string
    {
        return $this->getStubFileFromPresetStorage($this->preset, $this->getStubFileName());
    }

    /**
     * Get the stub file name for the generator.
     */
    public function getStubFileName(): string
    {
        if ($this->option('queued')) {
            return $this->option('event')
                ? 'listener-queued.stub'
                : 'listener-queued-duck.stub';
        }

        return $this->option('event')
            ? 'listener.stub'
            : 'listener-duck.stub';
    }

    /**
     * Get the default namespace for the class.
     */
    public function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace.'\Listeners';
    }

    /**
     * Generator options.
     *
     * @return array<string, mixed>
     */
    public function generatorOptions(): array
    {
        return [
            'event' => $this->option('event') ?? '',
            'force' => $this->option('force'),
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array<int, array>
     */
    protected function getOptions()
    {
        return [
            ['event', 'e', InputOption::VALUE_OPTIONAL, 'The event class being listened for'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the listener already exists'],
            ['queued', null, InputOption::VALUE_NONE, 'Indicates the event listener should be queued'],
        ];
    }
}
