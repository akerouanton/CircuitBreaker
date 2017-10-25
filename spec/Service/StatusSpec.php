<?php

namespace spec\NiR\CircuitBreaker\Service;

use NiR\CircuitBreaker\Service\Configuration;
use NiR\CircuitBreaker\Service\Status;
use PhpSpec\ObjectBehavior;

class StatusSpec extends ObjectBehavior
{
    function let(Configuration $config)
    {
        $this->beConstructedWith($config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Status::class);
    }

    function it_is_closed_when_there_is_not_more_failures_than_allowed($config)
    {
        $config->getOpeningThreshold()->willReturn(10);
        $this->beConstructedWith($config, 9);

        $this->isClose()->shouldReturn(true);
    }

    function it_is_half_open_when_failures_exceeded_threshold_and_closing_delay_elapsed($config)
    {
        $config->getOpeningThreshold()->willReturn(10);
        $config->getClosingDelay()->willReturn(30);

        $this->beConstructedWith($config, 10, time() - 40);

        $this->isHalfOpen()->shouldReturn(true);
    }

    function it_is_open_when_failures_exceeded_threshold_but_closing_delay_is_not_elapsed($config)
    {
        $config->getOpeningThreshold()->willReturn(10);
        $config->getClosingDelay()->willReturn(30);

        $this->beConstructedWith($config, 10, time() - 20);

        $this->isOpen()->shouldReturn(true);
    }

    function it_creates_new_instance_when_closing($config)
    {
        $this->beConstructedWith($config, 10);

        $this->close()->shouldNotBe($this->getWrappedObject());
    }

    function it_creates_new_instance_when_failure_is_notified($config)
    {
        $this->beConstructedWith($config, 10);

        $this->failed()->shouldNotBe($this->getWrappedObject());
    }
}
