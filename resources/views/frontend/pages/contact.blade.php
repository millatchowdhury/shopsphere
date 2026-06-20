@extends('layouts.frontend')
@section('title', 'Contact')
@section('content')
<section class="container py-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <h1 class="section-title">Contact Us</h1>
            <p class="text-muted">This form is a frontend placeholder. Wire it to mail or a support table when you are ready.</p>
            <form class="bg-white border rounded p-4">
                <label class="form-label">Name</label><input class="form-control mb-3">
                <label class="form-label">Email</label><input type="email" class="form-control mb-3">
                <label class="form-label">Message</label><textarea class="form-control mb-3" rows="5"></textarea>
                <button class="btn btn-dark" type="button">Send Message</button>
            </form>
        </div>
        <div class="col-lg-6"><div class="bg-white border rounded p-4 h-100"><h2 class="h5">Store Details</h2><p>Email: support@shopsphere.test</p><p>Phone: +1 555 0100</p><p>Address: 100 Commerce Street</p></div></div>
    </div>
</section>
@endsection
