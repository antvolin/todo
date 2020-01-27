<?php

namespace Todo\Lib\Traits;

trait TestValueGenerator
{
    /**
     * @return string
     */
    private function generateEmail(): string
    {
        return 'test@test.test';
    }

    /**
     * @param string $method
     * @param string $class
     * @param int $postfix
     *
     * @return string
     */
    private function generateUserName(string $method, string $class, int $postfix = 1): string
    {
        $fieldName = 'email';
        $fieldValue = sprintf('%s_%s_%s_%s', $fieldName, $method, $class, $postfix);

        return $this->generateField($fieldValue);
    }

    /**
     * @param string $method
     * @param string $class
     * @param int $postfix
     *
     * @return string
     */
    private function generateText(string $method, string $class, int $postfix = 1): string
    {
        $fieldName = 'text';
        $fieldValue = sprintf('%s_%s_%s_%s', $fieldName, $method, $class, $postfix);

        return $this->generateField($fieldValue);
    }

    /**
     * @param string $fieldValue
     *
     * @return string
     */
    private function generateField(string $fieldValue): string
    {
        return uniqid($fieldValue, true);
    }
}
