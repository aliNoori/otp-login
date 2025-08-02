<?php

echo "🔖 Enter version (e.g. 1.0.0): ";

$version = trim(fgets(STDIN));

if (empty($version)) {
    echo "❌ Version is required\n";
    exit(1);
}

$composerFile = __DIR__ . '/composer.json';

if (!file_exists($composerFile)) {
    echo "❌ composer.json not found\n";
    exit(1);
}

// خواندن composer.json
$composer = json_decode(file_get_contents($composerFile), true);
$composer['version'] = $version;

// بازنویسی composer.json
file_put_contents(
    $composerFile,
    json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
);

// اجرای دستورات گیت
$branch = 'master'; // یا master بر حسب شاخه‌ی شما
exec("git add .");
exec("git commit -m \"release v$version\"");
exec("git tag v$version");
exec("git push origin $branch");
exec("git push origin v$version");

echo "✅ Version updated to $version and released!\n";
