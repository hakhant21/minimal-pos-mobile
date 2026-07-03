<?php
$root = __DIR__ . '/..';
$langFile = $root . '/lang/my.json';
$ignoredDirs = ['/vendor/', '/storage/', '/node_modules/'];
$present = [];
if (!file_exists($langFile)) {
    echo "lang/my.json not found\n";
    exit(1);
}
$rawPresent = json_decode(file_get_contents($langFile), true);
$present = [];
foreach (array_keys($rawPresent) as $k) {
    $present[preg_replace('/\s+/', ' ', trim($k))] = true;
}
$files = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
foreach ($it as $f) {
    if ($f->isDir()) continue;
    $path = $f->getPathname();
    $rel = substr($path, strlen($root) + 1);
    $skip = false;
    foreach ($ignoredDirs as $d) if (strpos($rel, trim($d, '/')) === 0 || strpos($path, $d) !== false) {
        $skip = true;
        break;
    }
    if ($skip) continue;
    if (!preg_match('/\.(php)$/', $path)) continue;
    $files[] = $path;
}
$used = [];
$pattern = '/__\(\s*(?:\'|\")([^\'\"]+)(?:\'|\")\s*\)|@lang\(\s*(?:\'|\")([^\'\"]+)(?:\'|\")\s*\)|trans\(\s*(?:\'|\")([^\'\"]+)(?:\'|\")\s*\)/';
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (preg_match_all($pattern, $content, $m)) {
        for ($i = 1; $i < count($m); $i++) {
            foreach ($m[$i] as $k) {
                if (!$k) continue;
                $norm = preg_replace('/\s+/', ' ', trim($k));
                $used[$norm] = true;
            }
        }
    }
}
$usedKeys = array_keys($used);
sort($usedKeys);
$presentKeys = array_keys($present);
$missing = array_values(array_diff($usedKeys, $presentKeys));
if (empty($missing)) {
    echo "No missing translations found.\n";
    exit(0);
}
foreach ($missing as $m) echo $m . "\n";
