<?php

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;

class CustomApiTestCase extends ApiTestCase
{
    use HasBrowser {
        browser as baseKernelBrowser;
    }

    protected function browser(array $options = [], array $server = []): KernelBrowser
    {
        $options = array_merge(['environment' => 'test'], $options);

        return $this->baseKernelBrowser($options, $server)
            ->setDefaultHttpOptions(
                HttpOptions::create()
                    ->withHeader('Accept', 'application/ld+json')
            );
    }
}
