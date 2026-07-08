<?php

function dd(...$vars): void
{
    echo '<pre>';

    foreach ($vars as $var) {
        var_dump($var);
    }

    echo '</pre>';

    die();
}

function dump(...$vars): void
{
    echo '<pre>';

    foreach ($vars as $var) {
        var_dump($var);
    }

    echo '</pre>';
}

function dd_sql(string $sql, array $params = []): string
{
    foreach ($params as $key => $value) {

        if ($value === null) {
            $value = 'NULL';
        } elseif (is_string($value)) {
            $value = "'" . addslashes($value) . "'";
        } elseif (is_bool($value)) {
            $value = $value ? 1 : 0;
        }

        $sql = preg_replace(
            '/:' . preg_quote($key, '/') . '\b/',
            (string) $value,
            $sql,
        );
    }

    dd($sql);
}
