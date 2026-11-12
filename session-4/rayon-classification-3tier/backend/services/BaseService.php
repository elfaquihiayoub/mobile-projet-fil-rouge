<?php

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
        if ($value === null) {
            $value = '';
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        return $value;
    }
}
