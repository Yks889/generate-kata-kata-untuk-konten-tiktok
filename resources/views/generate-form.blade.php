@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">🎥 Generator Konten TikTok Islami</h3>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="/generate" method="POST" class="card p-4 shadow-sm">
        @csrf
        <div class="mb-3">
            <label for="tema" class="form-label">Tema Konten</label>
            <input type="text" name="tema" id="tema" class="form-control" placeholder="Contoh: taubat" required>
        </div>

        <div class="mb-3">
            <label for="gaya" class="form-label">Gaya Penyampaian</label>
            <input type="text" name="gaya" id="gaya" class="form-control" placeholder="Contoh: anak muda" required>
        </div>

        <button type="submit" name="mode" value="gpt" class="btn btn-success w-100">
            <i class="bi bi-stars me-1"></i> Generate dengan AI
        </button>
    </form>
</div>
@endsection
