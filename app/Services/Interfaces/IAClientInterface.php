<?php

namespace App\Services\Interfaces;

interface IAClientInterface {
    public function send(string $promptText): string|array;
}
