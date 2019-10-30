<?php

namespace App\Infra\Request\RequestObject;


interface LoggableRequestInterface
{
    /**
     * Уровень логирования параметров (debug, info, e.t.c.)
     * @return string
     */
    public function getLogLevelOfRequest(): string;

    /**
     * Уровень логирования возникших ошибок (warning, error, e.t.c.)
     * @return string
     */
    public function getLogLevelOfErrors(): string;
}
