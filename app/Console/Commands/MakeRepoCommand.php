<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use function Laravel\Prompts\text;
use function Laravel\Prompts\info;
use function Laravel\Prompts\error;
use function Laravel\Prompts\confirm;

class MakeRepoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class in app/Repository';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Prompt for name
        $name = text(
            label: 'Enter the repository name (without "Repository" suffix)',
            placeholder: 'Post',
            required: true
        );

        $name = Str::studly($name);
        $repositoryClass = $name . 'Repository';
        $directory = app_path('Repository');
        $path = "{$directory}/{$repositoryClass}.php";

        // 2. Check exists & confirm overwrite
        if (File::exists($path)) {
            if (!confirm("{$repositoryClass} already exists. Overwrite?", default: false)) {
                error('Operation cancelled.');
                return Command::FAILURE;
            }
        }

        // 3. Ensure directory exists
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // 4. Load stub
        $stubPath = base_path('app/stubs/repository.stub');
        if (!File::exists($stubPath)) {
            error("Stub file not found: {$stubPath}");
            return Command::FAILURE;
        }
        $stub = File::get($stubPath);

        // 5. Replace placeholders
        $content = str_replace(
            ['{{namespace}}', '{{class}}'],
            ['App\\Repository', $repositoryClass],
            $stub
        );

        // 6. Write file
        File::put($path, $content);

        info("Repository created: {$path}");
        return Command::SUCCESS;
    }
}
