<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$failures = [];
$legacyProductName = 'php'.'vms';

$required = [
    'README.md',
    'SECURITY.md',
    'CHANGELOG.md',
    '.env.example',
    'docs/ARCHITECTURE.md',
    'docs/ENGINEERING_DECISIONS.md',
    'docs/OPERATIONS.md',
    'docs/EVIDENCE_CHECKLIST.md',
    'assets/air-jamaica-virtual-banner.svg',
    'assets/system-architecture.svg',
];

foreach ($required as $file) {
    if (!is_file($root.DIRECTORY_SEPARATOR.$file)) {
        $failures[] = "Missing required file: {$file}";
    }
}

$forbiddenFiles = ['.env', 'database.sqlite', 'storage.sqlite'];
foreach ($forbiddenFiles as $file) {
    if (is_file($root.DIRECTORY_SEPARATOR.$file)) {
        $failures[] = "Forbidden runtime or secret file: {$file}";
    }
}

$secretPatterns = [
    'private key' => '/-----BEGIN (?:RSA |EC |OPENSSH )?PRIVATE KEY-----/',
    'GitHub token' => '/\b(?:ghp|github_pat)_[A-Za-z0-9_]{20,}\b/',
    'OpenAI-style key' => '/\bsk-[A-Za-z0-9_-]{20,}\b/',
    'AWS access key' => '/\bAKIA[0-9A-Z]{16}\b/',
];

$trackedOutput = [];
$trackedExitCode = 0;
exec('git -C '.escapeshellarg($root).' ls-files --cached --others --exclude-standard', $trackedOutput, $trackedExitCode);
if ($trackedExitCode !== 0) {
    $failures[] = 'Unable to enumerate tracked repository files.';
}

foreach ($trackedOutput as $relative) {
    $relative = str_replace('\\', '/', trim($relative));
    $path = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);
    if (!is_file($path) || filesize($path) > 2_000_000) {
        continue;
    }

    $contents = @file_get_contents($path);
    if ($contents === false || str_contains($contents, "\0")) {
        continue;
    }

    if ($relative !== 'THIRD_PARTY_NOTICES.md' && stripos($contents, $legacyProductName) !== false) {
        $failures[] = "Legacy product reference found in {$relative}";
    }

    foreach ($secretPatterns as $label => $pattern) {
        if (preg_match($pattern, $contents) === 1) {
            $failures[] = "Possible {$label} in {$relative}";
        }
    }
}

foreach (glob($root.'/{README.md,docs/*.md}', GLOB_BRACE) ?: [] as $document) {
    $contents = file_get_contents($document);
    preg_match_all('/!?\[[^\]]*\]\(([^)]+)\)/', $contents, $matches);
    foreach ($matches[1] as $target) {
        $target = trim(explode('#', $target, 2)[0], '<>');
        if ($target === '' || preg_match('/^(?:https?:|mailto:)/', $target) === 1) {
            continue;
        }

        $resolved = dirname($document).DIRECTORY_SEPARATOR.rawurldecode($target);
        if (!file_exists($resolved)) {
            $failures[] = 'Broken relative link in '.basename($document).": {$target}";
        }
    }
}

if ($failures !== []) {
    fwrite(STDERR, "Public repository validation failed:\n- ".implode("\n- ", array_unique($failures))."\n");
    exit(1);
}

echo "Public repository validation passed.\n";
