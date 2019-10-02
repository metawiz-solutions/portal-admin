@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Home</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        You are logged in as {{auth()->user()->name}}
                    </div>
                </div>
                <div class="card mt-4 shadow">
                    <div class="card-header">Actions</div>
                    <div class="row card-body justify-content-between">
                        <a href="{{route('scrape')}}" class="btn btn-outline-secondary  ml-5">Scrape Content</a>
                        <a href="{{route('summarize')}}" class="btn btn-outline-success">Summarize Content</a>
                        <a href="{{route('notes')}}" class="btn btn-outline-danger mr-5">View Notes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
