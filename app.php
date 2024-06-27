<?php

use App\core\Application;

require implode(DIRECTORY_SEPARATOR, [__DIR__, 'vendor', 'autoload.php']);

try {
    Application::getInstance()->init(require_once implode(DIRECTORY_SEPARATOR, [__DIR__, 'src', 'config', 'app.php']))->run($argv);
} catch (Exception $e) {
    echo $e->getMessage();
}
