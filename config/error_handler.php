<?php

set_exception_handler(function ($exception) {
    error_log($exception->getMessage());
    echo "Произошла ошибка. Пожалуйста, попробуйте позже.";
});

// Устанавливаем обработчик ошибок
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    error_log("Ошибка: [$errno] $errstr в $errfile:$errline");
    echo "Произошла ошибка. Пожалуйста, попробуйте позже.";
});
