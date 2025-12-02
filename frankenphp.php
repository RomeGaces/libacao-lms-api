<?php

use Symfony\Component\Runtime\RuntimeInterface;

return function (array $context): RuntimeInterface {
    return new \Symfony\Component\Runtime\FrankenPhp\FrankenPhpRuntime($context);
};
