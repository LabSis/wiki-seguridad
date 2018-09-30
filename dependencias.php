<?php

$resultado = array();

if (function_exists('xdiff_string_bdiff')) {
    $resultado['xdiff_string_bdiff'] = "Detectada";
} else {
    $resultado['xdiff_string_bdiff'] = "No detectada";
}

if (function_exists('xdiff_string_bpatch')) {
    $resultado['xdiff_string_bpatch'] = "Detectada";
} else {
    $resultado['xdiff_string_bpatch'] = "No detectada";
}

echo "<pre>" . json_encode($resultado, JSON_PRETTY_PRINT) . "</pre>";
