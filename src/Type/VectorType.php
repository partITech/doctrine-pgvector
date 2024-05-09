<?php
declare(strict_types=1);

namespace Partitech\DoctrinePgVector\Type;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class VectorType extends Type
{
    public const NAME = 'vector';

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): array
    {

        if (is_string($value)) {
            $value = str_replace(['[', ']'], '', $value);
            return array_map('floatval', explode(',', $value));
        }

        if (is_array($value)) {
            return $value;
        }

        return [];
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {

        if (!is_array($value) || count($value) === 0) {
            return null;
        }

        $values = array_filter($value, function ($item) {
            return is_float($item) || is_int($item);
        });

        if (count($values) == 0) {
            return null;
        }

        return '[' . implode(',', $values) . ']';

    }

    public function canRequireSQLConversion(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return [self::NAME];
    }


    /**
     * @throws Exception
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {

        if (isset($column['length']) && $column['length']) {
            return 'vector(' . $column['length'] . ')';
        }

        return 'vector(1024)';
    }
}
