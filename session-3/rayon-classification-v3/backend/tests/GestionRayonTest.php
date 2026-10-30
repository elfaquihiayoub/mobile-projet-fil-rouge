<?php

require_once __DIR__ . '/../classes/GestionRayon.php';

function assertSameValue(mixed $expected, mixed $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException($message . ': expected ' . var_export($expected, true) . ', got ' . var_export($actual, true));
    }
}

$originalDataFile = GestionRayon::$dataFile;
$temporaryDataFile = tempnam(sys_get_temp_dir(), 'rayon-test-');
if ($temporaryDataFile === false) {
    throw new RuntimeException('Unable to create temporary test data file.');
}

try {
    GestionRayon::$dataFile = $temporaryDataFile;
    file_put_contents($temporaryDataFile, json_encode([
        ['id' => 4, 'nom' => 'Fruits', 'emplacement' => 'A1'],
    ], JSON_THROW_ON_ERROR));

    $rayon = new Rayon('Boissons', null);
    assertSameValue(null, $rayon->getId(), 'New Rayon ID');
    assertSameValue('Boissons', $rayon->getNom(), 'Rayon name');
    assertSameValue(null, $rayon->getEmplacement(), 'Optional location');

    $gestionRayon = new GestionRayon();
    $rayons = $gestionRayon->getAll();
    assertSameValue(1, count($rayons), 'getAll count');
    assertSameValue(4, $rayons[0]->getId(), 'Loaded Rayon ID');
    assertSameValue('Fruits', $rayons[0]->getNom(), 'Loaded Rayon name');

    assertSameValue(5, $gestionRayon->create($rayon), 'create generated ID');
    $savedData = json_decode(file_get_contents($temporaryDataFile), true, 512, JSON_THROW_ON_ERROR);
    assertSameValue([
        'id' => 5,
        'nom' => 'Boissons',
        'emplacement' => null,
    ], $savedData[1], 'Persisted Rayon');

    echo "GestionRayon tests passed.\n";
} finally {
    GestionRayon::$dataFile = $originalDataFile;
    unlink($temporaryDataFile);
}
