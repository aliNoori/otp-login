<?php

namespace OtpLogin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixModelNamespace extends Command
{
    protected $signature = 'otp-login:fix-model-namespaces';
    protected $description = 'Replace model namespaces after publishing';

    public function handle()
    {
        $path = app_path('Models');

        if (!File::exists($path)) {
            $this->warn("Models path does not exist: $path");
            return;
        }

        $files = File::allFiles($path);

        foreach ($files as $file) {
            $content = File::get($file->getPathname());

            if (str_contains($content, 'namespace OtpLogin\Models')) {
                $newContent = str_replace(
                    'namespace OtpLogin\Models',
                    'namespace App\Models',
                    $content
                );

                File::put($file->getPathname(), $newContent);
                $this->info("✅ Updated namespace in: " . $file->getFilename());
            }
        }

        $this->info('🎉 All namespaces updated.');
    }
}
