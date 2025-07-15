@extends('layouts.app')

@section('title', 'Edit Rule')

@section('content')
    <form action="{{ route('rules.update', $rule->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Rule Name</label>
            <input type="text" name="name" class="form-control" value="{{ $rule->name }}" required>
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('rules.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
