<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\Htmx;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\View\View;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class HtmxTest extends TestCase
{
    public function testMutatesResponseBasedOnTarget(): void
    {
        $view = Mockery::mock(View::class, static function (MockInterface $mock): void {
            $mock->shouldReceive('render');
            $mock->shouldReceive('fragment')->once()->with('target')->andReturn('foo');
        });
        $middleware = new Htmx();
        $response = new IlluminateResponse($view);
        $this->assertSame(
            'foo',
            $middleware->handle($this->mockRequest('target'), static fn () => $response)->getContent()
        );
    }

    public function testDoesNothingWhenHeaderMissing(): void
    {
        $middleware = new Htmx();
        $response = new Response();
        $this->assertSame($response, $middleware->handle($this->mockRequest(), static fn () => $response));
    }

    public function testDoesNothingForNonBladeResponse(): void
    {
        $middleware = new Htmx();
        $response = new IlluminateResponse();
        $this->assertSame($response, $middleware->handle($this->mockRequest('foo'), static fn () => $response));
    }

    private function mockRequest(?string $target = null): Request
    {
        $request = Request::create('/');

        if ($target !== null) {
            $request->headers->set('hx-target', $target);
        }
        return $request;
    }
}
