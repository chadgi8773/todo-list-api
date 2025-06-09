@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('notes.create') }}">Create new</a>
                <table id="notes_table" class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Created By </th>
                            <th>Created At </th>
                            <th>Actions </th>
                        </tr>
                    </thead>
                    <hr>
                    <tbody>
                        {{-- @foreach ($notes as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{App\Models\User::find($item->user_id)-> namespace}}</td>
                                <td>{{ $item->created_at }}</td>
                                <td> 
                                    <a href="{{ route('notes.show', $item->id) }}">Show</a> 
                                    <a href="{{ route('notes.edit', $item->id) }}">Edit</a> 
                                    <form method="POST" action="{{ route('notes.destroy', $item->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button>Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
        $('#notes_table').dataTable({
            "serverSide": true,
            "responsive": true,
            "ajax": "{{ route('notes.data') }}"
        });
    });
    </script>
@endsection
