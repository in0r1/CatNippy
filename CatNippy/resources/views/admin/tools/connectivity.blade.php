@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <div class="text-uppercase small mb-2">
                    CatNippy / OPERATIONS
                </div>

                <h1 class="h2 mb-2">Service Health Check</h1>

                <p class="text-secondary">
                    Check hostname resolution from the CatNippy application server.
                </p>
            </div>

            <div class="card border-secondary bg-dark text-light">
                <div class="card-body p-4">
                    <form method="POST"
                          action="{{ route('tools.connectivity.check') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="host" class="form-label">
                                Hostname
                            </label>

                            <input
                                type="text"
                                id="host"
                                name="host"
                                class="form-control bg-black text-light border-secondary"
                                placeholder="e.g. localhost"
                                value="{{ old('host', $host) }}"
                                maxlength="200"
                                required
                            >

                            @error('host')
                                <div class="text-danger small mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit"
                                class="btn btn-primary"
                                style="background: #a855f7; border-color: #a855f7;">
                            Run health check
                        </button>
                    </form>
                </div>
            </div>

            @if ($output !== null)
                <div class="card border-secondary bg-dark text-light mt-4">
                    <div class="card-header border-secondary">
                        Check results
                    </div>

                    <div class="card-body">
                        <p class="text-secondary small mb-2">
                            Output from the server
                        </p>

                        <pre class="text-light mb-0"
                             style="white-space: pre-wrap; overflow-wrap: anywhere;">{{ $output }}</pre>
                    </div>
                </div>
            @endif

            <p class="text-secondary small mt-3">
                Internal development utility. Use only with systems you own
                or are authorized to test.
            </p>
        </div>
    </div>
</div>
@endsection
