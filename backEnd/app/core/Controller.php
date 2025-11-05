<?php

class Controller {
    protected function view($view, $data = []) {
        // Extract data to be used in the view
        extract($data);

        $viewPath = BASE_PATH . "/app/views/{$view}.php";

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // Handle view not found error
            self::handleError("View not found: {$viewPath}");
        }
    }

    protected function redirect($path) {
        $location = BASE_URL . $path;
        error_log("Redirecting to: " . $location);
        if (headers_sent()) {
            error_log("Headers already sent. Cannot redirect.");
            // Fallback: output a meta refresh or JavaScript redirect
            echo "<meta http-equiv=\"refresh\" content=\"0;url=" . htmlspecialchars($location) . "\">";
            echo "<script>window.location.href=\"" . htmlspecialchars($location) . "\";<\/script>";
        } else {
            header('Location: ' . $location);
        }
        exit;
    }

    public static function handleError($message) {
        // Simple error handling
        error_log($message);
        // You might want to show a generic error page
        require_once BASE_PATH . '/app/views/common/error.php';
        exit;
    }
}