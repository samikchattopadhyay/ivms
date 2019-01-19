@extends('layouts.app-front') 

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <img src="{{ Avatar::create($name)->toBase64() }}" style="height: 130px;">
            </div>
            <div class="col-md-9">
                <h2> Hello {{ $name }},</h2>
                <p style="font-size: 18px;">Thanks for completing the Hiring Test. We will review your answers and will get back to you. Wish you all the best for your test result! 🤞</p>
            </div>
        </div>
    </section>
    <!-- /.content -->
    
@endsection
