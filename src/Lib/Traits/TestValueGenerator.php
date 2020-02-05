<?php

namespace Todo\Lib\Traits;

trait TestValueGenerator
{
    private function generateEmail(): string
    {
        return 'test@test.test';
    }

    private function generateUserName(string $method, string $class, int $postfix = 1): string
    {
        $fieldName = 'email';
        $fieldValue = sprintf('%s_%s_%s_%s', $fieldName, $method, $class, $postfix);

        return $this->generateField($fieldValue);
    }

    private function generateText(string $method, string $class, int $postfix = 1): string
    {
        $fieldName = 'text';
        $fieldValue = sprintf('%s_%s_%s_%s', $fieldName, $method, $class, $postfix);

        return $this->generateField($fieldValue);
    }

    private function generateField(string $fieldValue): string
    {
        return uniqid($fieldValue, true);
    }
}
