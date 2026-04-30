@extends('layouts.app')

@section('title', 'Vérification Email')

@section('content')

<div style="min-height:100vh;background:#f0f4f8;display:flex;align-items:center;justify-content:center;padding:20px;">

<div style="width:100%;max-width:460px;background:#fff;border-radius:18px;padding:40px;
box-shadow:0 15px 45px rgba(27,58,107,.08);">

    <div style="text-align:center;margin-bottom:25px;">

        <div style="width:70px;height:70px;border-radius:18px;background:#1B3A6B;
        margin:auto;display:flex;align-items:center;justify-content:center;">

            <span style="font-size:28px;color:#F5A623;">✉</span>

        </div>

        <h2 style="margin-top:15px;color:#1B3A6B;font-weight:800;">
            Vérifiez votre email
        </h2>

        <p style="font-size:14px;color:#777;">
            Entrez le code reçu par email.
        </p>

    </div>

    @if($errors->any())
        <div style="background:#ffeaea;padding:12px;border-radius:10px;color:#d63031;font-size:13px;margin-bottom:15px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('verify.check') }}">
        @csrf

        <input type="hidden" name="email" value="{{ session('email') }}">

        <label style="font-size:12px;font-weight:700;color:#1B3A6B;">
            Code de vérification
        </label>

        <input type="text"
               name="code"
               maxlength="6"
               placeholder="123456"
               style="width:100%;margin-top:8px;padding:14px;
               border:1px solid #ddd;border-radius:12px;
               font-size:24px;text-align:center;letter-spacing:8px;
               font-weight:700;outline:none;">

        <button type="submit"
                style="width:100%;margin-top:20px;padding:14px;
                border:none;border-radius:30px;
                background:#1B3A6B;color:#fff;
                font-weight:700;font-size:15px;cursor:pointer;">

            Vérifier mon compte

        </button>

    </form>

    <div style="text-align:center;margin-top:18px;">
        <a href="#" style="font-size:13px;color:#F5A623;text-decoration:none;">
            Renvoyer le code
        </a>
    </div>

</div>

</div>

@endsection