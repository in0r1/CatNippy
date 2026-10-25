@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    <div class="mb-4">
        <h1 class="mb-1">My account</h1>
        <p class="text-muted mb-0">
            Manage your profile and account information.
        </p>
    </div>

    @if (session('status') === 'avatar-updated')
        <div class="alert alert-success">
            Your profile photo has been updated.
        </div>
    @elseif (session('status') === 'profile-updated')
        <div class="alert alert-success">
            Your account information has been saved.
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center gap-4">
                @if ($user->avatar_path)
                    <img
                        src="{{ asset('storage/' . $user->avatar_path) }}"
                        alt="Profile photo"
                        width="112"
                        height="112"
                        class="rounded-circle border"
                        style="object-fit: cover;"
                    >
                @else
                    <div
                        class="rounded-circle d-flex align-items-center justify-content-center border"
                        style="width: 112px; height: 112px; font-size: 2.5rem; background: #263b30; color: #fff;"
                        aria-label="Default profile photo"
                    >
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div class="flex-grow-1">
                    <h2 class="h5 mb-1">{{ $user->name }}</h2>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    <form
                        action="{{ route('profile.avatar.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <label for="avatar" class="form-label">
                            Profile photo
                        </label>

                        <input
                            id="avatar"
                            name="avatar"
                            type="file"
                            class="form-control @error('avatar') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.gif,.webp,.svg"
                            required
                        >

                        @error('avatar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-label mb-3">
                            JPG, PNG, GIF, WEBP or SVG. Maximum file size: 2 MB.
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Update photo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <h2 class="h5 mb-4">Personal information</h2>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="name" class="form-label">Full name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}"
                        autocomplete="name"
                        required
                    >

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">Email address</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}"
                        autocomplete="email"
                        required
                    >

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Save changes
                </button>

                <a
                    href="{{ route('orders.index') }}"
                    class="btn btn-outline-secondary ms-2"
                >
                    My orders
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
