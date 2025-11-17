<?php

if (! function_exists('flatter')) {

    function flatter(array $data): array
    {
        return collect($data)
            ->flatMap(fn ($v, $k) => [$k, $v])
            ->all();
    }

}
