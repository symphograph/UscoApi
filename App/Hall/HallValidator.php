<?php

namespace App\Hall;

use Symphograph\Bicycle\Errors\AppErr;

class HallValidator {

    public function __construct(private HallITF $hall)
    {
    }

    public function validate(): void
    {
        $this->uniqueName();
    }

    private function uniqueName(): void
    {
        $pubMsg = 'Такое название зала уже есть';
        $this->uniqueField('name', $pubMsg);
    }

    private function uniqueField(string $fieldName, string $pubMsg): void
    {
        $fieldName = ucfirst($fieldName);
        $getMethod = "get$fieldName";
        $byMethod = "by$fieldName";

        $value = $this->hall->$getMethod();
        $existingHall = $this->hall::$byMethod($value);

        if ($this->compareId($existingHall)) {
            return;
        }

        throw new AppErr("Hall$fieldName already exists", $pubMsg);
    }

    private function compareId(HallITF|false $existingHall): bool
    {
        return !$existingHall || $this->hall->getId() === $existingHall->getId();
    }
}