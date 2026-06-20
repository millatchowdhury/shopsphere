@extends('layouts.frontend')
@section('title', 'Register')
@section('content')
<section class="container py-5" style="max-width:640px">
    <h1 class="section-title">Create Account</h1>
    <form method="POST" action="{{ route('register.store') }}" class="bg-white border rounded p-4">
        @csrf
        <label class="form-label">Name</label><input class="form-control mb-3" name="name" value="{{ old('name') }}" required>
        <label class="form-label">Email</label><input class="form-control mb-3" type="email" name="email" value="{{ old('email') }}" required>
        <label class="form-label">Phone</label><input class="form-control mb-3" name="phone" value="{{ old('phone') }}">
        <label class="form-label">Password</label><input class="form-control mb-3" type="password" name="password" required>
        <label class="form-label">Confirm Password</label><input class="form-control mb-3" type="password" name="password_confirmation" required>
        <button class="btn btn-dark w-100">Register</button>
    </form>
</section>
@endsection
