<?php

namespace spec\NiR\CircuitBreaker\Storage;

use NiR\CircuitBreaker\Storage;
use NiR\CircuitBreaker\Storage\LocalArray;
use PhpSpec\ObjectBehavior;

class LocalArraySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LocalArray::class);
    }

    function it_is_a_storage()
    {
        $this->shouldImplement(Storage::class);
    }
}
