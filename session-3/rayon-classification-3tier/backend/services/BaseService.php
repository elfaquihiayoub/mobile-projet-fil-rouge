<?php
declare(strict_types=1);

abstract class BaseService
{
    protected function requiredText(string $value, string $field): string
    {
        $value = trim($value);
        if ($value === '') {
            throw new InvalidArgumentException("Le champ {$field} est requis.");
        }

        return $value;
    }

    protected function optionalText(?string $value): ?string
    {
        $value = trim($value ?? '');
        return $value === '' ? null : $value;
    }
}
