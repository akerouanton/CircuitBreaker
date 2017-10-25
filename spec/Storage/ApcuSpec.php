<?php

namespace spec\NiR\CircuitBreaker\Storage;

use NiR\CircuitBreaker\Storage;
use NiR\CircuitBreaker\Storage\Apcu;
use PhpSpec\ObjectBehavior;

class ApcuSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Apcu::class);
    }

    function it_is_a_storage()
    {
        $this->shouldImplement(Storage::class);
    }
}
