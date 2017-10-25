<?php

namespace spec\NiR\CircuitBreaker;

use NiR\CircuitBreaker\CircuitBreaker;
use NiR\CircuitBreaker\Exception\ServiceNotDefined;
use NiR\CircuitBreaker\Service\Configuration;
use NiR\CircuitBreaker\Service\Status;
use NiR\CircuitBreaker\Storage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CircuitBreakerSpec extends ObjectBehavior
{
    function let(Storage $storage, Configuration $config)
    {
        $config->getServiceName()->willReturn('service');

        $this->beConstructedWith($storage, $config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CircuitBreaker::class);
    }

    function it_throws_an_exception_for_unknown_services()
    {
        $this->shouldThrow(ServiceNotDefined::class)->during('isAvailable', ['unknown service']);
    }

    function it_signals_service_as_available_if_its_breaker_is_close(Status $status, $storage, $config)
    {
        $storage->loadStatus($config)->willReturn($status);
        $status->isClose()->willReturn(true);

        $this->isAvailable('service')->shouldReturn(true);
    }

    function it_signals_service_as_available_if_its_breaker_is_half_open(Status $status, $storage, $config)
    {
        $storage->loadStatus($config)->willReturn($status);
        $status->isClose()->willReturn(false);
        $status->isHalfOpen()->willReturn(true);

        $this->isAvailable('service')->shouldReturn(true);
    }

    function it_signals_service_as_unavailable_if_its_breaker_is_open(Status $status, $storage, $config)
    {
        $storage->loadStatus($config)->willReturn($status);
        $status->isClose()->willReturn(false);
        $status->isHalfOpen()->willReturn(false);

        $this->isAvailable('service')->shouldReturn(false);
    }

    function it_throws_an_exception_when_a_success_for_an_unknown_service_is_reported()
    {
        $this->shouldThrow(ServiceNotDefined::class)->during('reportSuccess', ['unknown service']);
    }

    function it_stores_the_breaker_as_closed_when_a_success_is_reported_and_the_breaker_was_not_initially_closed(
        Status $storedStatus,
        Status $updatedStatus,
        $storage,
        $config
    ) {
        $storage->loadStatus($config)->willReturn($storedStatus);
        $storedStatus->isClose()->willReturn(false);
        $storedStatus->close()->willReturn($updatedStatus);

        $storage->saveStatus($config, $updatedStatus)->shouldBeCalled();

        $this->reportSuccess('service');
    }

    function it_does_not_store_the_breaker_as_closed_when_a_success_is_reported_but_the_breaker_was_already_closed(
        Status $storedStatus,
        $storage,
        $config
    ) {
        $storage->loadStatus($config)->willReturn($storedStatus);
        $storedStatus->isClose()->willReturn(true);

        $storage->saveStatus($config, Argument::type(Status::class))->shouldNotBeCalled();

        $this->reportSuccess('service');
    }

    function it_throws_an_exception_when_a_failure_for_an_unknown_service_is_reported()
    {
        $this->shouldThrow(ServiceNotDefined::class)->during('reportSuccess', ['unknown service']);
    }

    function it_updates_breaker_status_when_a_failure_is_reported(
        Status $storedStatus,
        Status $updatedStatus,
        $storage,
        $config
    ) {
        $storage->loadStatus($config)->willReturn($storedStatus);
        $storedStatus->failed()->willReturn($updatedStatus);

        $storage->saveStatus($config, $updatedStatus)->shouldBeCalled();

        $this->reportFailure('service');
    }
}
