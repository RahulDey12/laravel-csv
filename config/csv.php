<?php

return [
    'sanitizers' => [
        \Rahul900day\Csv\Sanitizers\TrimString::class,
        \Rahul900day\Csv\Sanitizers\ConvertEmptyStringToNull::class,
    ],
];
