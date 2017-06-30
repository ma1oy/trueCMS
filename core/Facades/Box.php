<?php
namespace T\Facades;

use T\Abstracts\Facade;

/**
 * Class Box
 * @package T\Facades
 *
 * @method static make(string $abstract, array $params = [])
 * @see \T\Services\BoxInterface::make()
 */
class Box extends Facade
{
    protected static function getFacadeAccessor() {
        return \T\Interfaces\BoxInterface::class;
    }
}
