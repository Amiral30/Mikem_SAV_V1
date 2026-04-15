<?php
echo "<h1>Sonde de Diagnostic Mikem SAV</h1>";

// 1. Version PHP
echo "<h3>1. Version PHP</h3>";
if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
    echo "<p style='color:green'>✅ " . PHP_VERSION . " (Compatible Laravel 11)</p>";
} else {
    echo "<p style='color:red'>❌ " . PHP_VERSION . " (INCOMPATIBLE ! Laravel 11 nécessite 8.2+)</p>";
    echo "<p>Veuillez changer la version dans votre panel InfinityFree.</p>";
}

// 2. Fichiers Critiques
echo "<h3>2. Fichiers Critiques</h3>";
$fichiers = [
    'bootstrap/app.php',
    'bootstrap/providers.php',
    'vendor/autoload.php',
    'public/index.php',
    '.env'
];

foreach ($fichiers as $f) {
    if (file_exists($f)) {
        echo "<p style='color:green'>✅ $f est présent.</p>";
    } else {
        echo "<p style='color:red'>❌ $f est MANQUANT !</p>";
    }
}

// 3. Permissions Dossiers
echo "<h3>3. Dossiers Writable</h3>";
$dossiers = ['storage', 'bootstrap/cache'];
foreach ($dossiers as $d) {
    if (is_writable($d)) {
        echo "<p style='color:green'>✅ $d est accessible en écriture.</p>";
    } else {
        echo "<p style='color:red'>❌ $d n'est PAS accessible en écriture.</p>";
    }
}

echo "<hr><p>Si tout est vert et que vous avez une erreur 500, le souci est dans le code des Layouts.</p>";
