<?php

define('DATA_FILE', __DIR__ . '/data/data.json');
define('ERROR_LOG_FILE', __DIR__ . '/data/save_errors.log');

if (!function_exists('read_books')) {
    function read_books(): array {
        $file = DATA_FILE;
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
            @chmod($file, 0666);
        }
        $json = @file_get_contents($file);
        $data = json_decode($json, true);
        if (!is_array($data)) {
            $data = [];
        }
        return $data;
    }
}

if (!function_exists('save_books')) {
    function save_books(array $books): bool {
        $file = DATA_FILE;
        $json = json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            log_save_error("json_encode failed: " . json_last_error_msg());
            return false;
        }

        // Primero intenta escribir directamente
        $ok = @file_put_contents($file, $json, LOCK_EX);
        if ($ok !== false) {
            return true;
        }

        // Si falla, intenta con archivo temporal en mismo directorio
        $dir = dirname($file);
        $tmp = @tempnam($dir, 'books_');
        if ($tmp === false) {
            // fallback a temp dir del sistema
            $tmp = @tempnam(sys_get_temp_dir(), 'books_');
            if ($tmp === false) {
                log_save_error("No se pudo crear archivo temporal para guardar.");
                return false;
            }
        }

        $ok2 = @file_put_contents($tmp, $json);
        if ($ok2 === false) {
            @unlink($tmp);
            log_save_error("file_put_contents a tmp falló.");
            return false;
        }

        // Intentar renombrar
        if (@rename($tmp, $file)) {
            @chmod($file, 0666);
            return true;
        }

        // Si rename falla, intentar copiar y borrar
        if (@copy($tmp, $file)) {
            @unlink($tmp);
            @chmod($file, 0666);
            return true;
        }

        // último recurso: intentar escribir directo de nuevo
        $ok3 = @file_put_contents($file, $json, LOCK_EX);
        if ($ok3 !== false) {
            return true;
        }

        // si llegamos aquí, guardar info de error
        log_save_error("No se pudo guardar el archivo. Intentos fallidos.");
        return false;
    }
}

if (!function_exists('log_save_error')) {
    function log_save_error(string $msg) {
        $file = ERROR_LOG_FILE;
        $entry = "[" . date('Y-m-d H:i:s') . "] " . $msg . PHP_EOL;
        @file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
    }
}
