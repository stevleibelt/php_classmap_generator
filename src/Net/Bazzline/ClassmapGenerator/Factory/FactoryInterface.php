<?php

namespace Net\Bazzline\ClassmapGenerator\Factory;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
interface FactoryInterface
{
    /**
     * @author stev leibelt
     * @param array $options
     * @since 2013-03-03
     * @throws \InvalidArgumentException
     */
    public static function create(array $options);
}