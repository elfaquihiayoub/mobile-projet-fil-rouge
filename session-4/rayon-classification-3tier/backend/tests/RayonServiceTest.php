<?php

require_once __DIR__ . '/../services/RayonService.php';

function assertSameValue($expected, $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException(
            $message . ': expected ' . var_export($expected, true) . ', got ' . var_export($actual, true)
        );
    }
}

$temporaryDataFile = tempnam(sys_get_temp_dir(), 'rayon-3tier-test-');
if ($temporaryDataFile === false) {
    throw new RuntimeException('Unable to create temporary test data file.');
}

try {
    file_put_contents($temporaryDataFile, json_encode([
        ['id' => 4, 'nom' => 'Fruits', 'emplacement' => 'A1'],
    ]));

    $dao = new RayonDAO($temporaryDataFile);
    $service = new RayonService($dao);
    assertSameValue(1, count($service->getAll()), 'Read through service and DAO');

    $created = $service->create('  Boissons  ', '  ');
    assertSameValue(5, $created->getId(), 'Create generated ID');
    assertSameValue('Boissons', $created->getNom(), 'Create trims name');
    assertSameValue(null, $created->getEmplacement(), 'Create normalizes empty location');

    $updated = $service->update(5, 'Epicerie', 'B2');
    if ($updated === null) {
        throw new RuntimeException('Update should return the updated Rayon.');
    }
    assertSameValue('Epicerie', $updated->getNom(), 'Update name');

    $found = $service->getById(5);
    if ($found === null) {
        throw new RuntimeException('The updated Rayon should be readable.');
    }
    assertSameValue('B2', $found->getEmplacement(), 'Read updated data');

    assertSameValue(true, $service->delete(5), 'Delete existing Rayon');
    assertSameValue(null, $service->getById(5), 'Deleted Rayon is absent');
    assertSameValue(false, $service->delete(5), 'Delete missing Rayon');
    assertSameValue(null, $service->update(5, 'No longer here', null), 'Update missing Rayon');

    try {
        $service->create('   ', null);
        throw new RuntimeException('Expected an empty Rayon name to fail validation.');
    } catch (InvalidArgumentException) {
    }

    echo "RayonService tests passed.\n";
} finally {
    unlink($temporaryDataFile);
}
