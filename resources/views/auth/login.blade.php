@extends('layouts.frontend')
@section('title', 'Login')
@section('content')
<section class="container py-5" style="max-width:560px">
    <h1 class="section-title">Login</h1>
    <form method="POST" action="{{ route('login.store') }}" class="bg-white border rounded p-4">
        @csrf
        <label class="form-label">Email</label><input class="form-control mb-3" type="email" name="email" value="{{ old('email') }}" required>
        <label class="form-label">Password</label><input class="form-control mb-3" type="password" name="password" required>
        <label class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember"> Remember me</label>
        <button class="btn btn-dark w-100">Login</button>
        <p class="mt-3 mb-0">No account? <a href="{{ route('register') }}">Create one</a></p>
    </form>
</section>
@endsection
