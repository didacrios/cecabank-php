<?php

namespace Cecabank\Tests;

use Cecabank\Client;
use PHPUnit\Framework\TestCase;

class ClientVersionTest extends TestCase
{
    public function testVersionConstantShouldExist()
    {
        // Given & When
        $version = Client::VERSION;

        // Then
        $this->assertIsString($version);
        $this->assertNotEmpty($version);
    }

    public function testVersionShouldFollowSemanticVersioning()
    {
        // Given
        $version = Client::VERSION;

        // When
        $pattern = '/^\d+\.\d+\.\d+$/';
        $matches = preg_match($pattern, $version);

        // Then
        $this->assertEquals(1, $matches, "Version should follow semver format (e.g., 1.0.0)");
    }

    public function testVersionShouldBeAccessibleWithoutInstantiation()
    {
        // Given & When
        $version = Client::VERSION;

        // Then
        $this->assertEquals('1.0.0', $version);
    }
}

