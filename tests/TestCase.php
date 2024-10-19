<?php

namespace Tests;

use PressbooksAudioExport\Bootstrap;
use WP_UnitTestCase;

class TestCase extends WP_UnitTestCase
{
    public function setUp(): void
    {
        (new Bootstrap)->setUp();
        parent::setUp();
    }
}
