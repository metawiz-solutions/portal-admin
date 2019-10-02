@extends('layouts.app')
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Notes</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <table class="table table-sm text-center">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">URL</th>
                                <th scope="col">Last updated</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($notes as $note)
                                <tr>
                                    <th scope="row">{{$note->id}}</th>
                                    <td>{{$note->url}}</td>
                                    <td>{{$note->updated_at->diffForHumans()}}</td>
                                    @if($note->status === 1)
                                        <td><h4><span class="badge badge-success">scrape success</span></h4></td>
                                    @elseif($note->status === 2)
                                        <td><h4><span class="badge badge-danger">scrape failed</span></h4></td>
                                    @elseif($note->status === 3)
                                        <td><h4><span class="badge badge-success">summarization success</span></h4></td>
                                    @elseif($note->status === 4)
                                        <td><h4><span class="badge badge-danger">summarization fail</span></h4>/td>
                                    @endif
                                    <td>
                                        <a href="{{route('show.note',['id'=>$note->id])}}" class="btn btn-success btn-sm"><i data-feather="eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{$notes->links()}}
            </div>
        </div>
    </div>
@endsection
