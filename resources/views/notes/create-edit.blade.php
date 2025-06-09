@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Note') }}</div>

                    <div class="card-body">
                        <div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                
                            @endif
                        </div>
                        <form action="{{ $isEdit ? route('notes.update', $note->id) : route('notes.store') }}" method="POST">
                            @csrf
                            @if ($isEdit)
                                @method('PUT')
                            @endif
                            <div class="row mb-3">
                                <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Title: ') }}</label>

                                <div class="col-md-6">
                                    <input id="title" type="text" class="form-control" name="title" value="{{ $isEdit ? $note->title  : old('title')}}" required placeholder="Enter your note title!">

                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Description: ') }}</label>

                                <div class="col-md-6">
                                    <textarea id="description" type="text" class="form-control" name="description" required placeholder="Enter your note description!!!">{{ $isEdit ? $note->description : old('description') }}</textarea>

                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Share With Others') }}</label>

                                <div class="col-md-6">
                                    <select name="share[]" id="share" multiple>
                                        @foreach (App\Models\User::all()->except(Auth::id()) as $user)
                                            <option value="{{ $user->id }}" {{ $isEdit ? ($note->shared->contains($user) ? 'selected' : '') : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit">{{ $isEdit ? "Update" : "Create" }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
