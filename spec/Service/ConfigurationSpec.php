<?php

namespace spec\NiR\CircuitBreaker\Service;

use NiR\CircuitBreaker\Service\Configuration;
use PhpSpec\ObjectBehavior;

class ConfigurationSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('service name', 50, 300, 60);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Configuration::class);
    }

    function it_has_a_name()
    {
        $this->getServiceName()->shouldReturn('service name');
    }

    function it_has_an_opening_threshold()
    {
        $this->getOpeningThreshold()->shouldReturn(50);
    }

    function it_has_a_closing_delay()
    {
        $this->getClosingDelay()->shouldReturn(300);
    }

    function it_has_a_dismiss_delay()
    {
        $this->getDismissDelay()->shouldreturn(60);
    }
}
