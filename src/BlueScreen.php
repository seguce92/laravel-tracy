<?php

namespace Recca0120\LaravelTracy;

use ErrorException;
use Exception;
use Tracy\Debugger;
use Tracy\Helpers;

class BlueScreen
{
    /**
     * render.
     *
     * @method render
     *
     * @param \Exception $exception
     *
     * @return string
     */
    public function render(Exception $exception)
    {
        $exception = $this->fixStack($exception);

        ob_start();
        Helpers::improveException($exception);
        Debugger::getBlueScreen()->render($exception);

        return ob_get_clean();
    }

    protected function fixStack($exception, $error = null)
    {
        $error = is_null($error) ? error_get_last() : $error;
        if (in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE, E_RECOVERABLE_ERROR, E_USER_ERROR], true) === true) {
            return Helpers::fixStack(new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
        }

        return $exception;
    }
}
