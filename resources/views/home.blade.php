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
                    <div class="card-header">Scrape and Summarize</div>
                    <div class="row card-body justify-content-between">
                        <a href="{{route('scrape')}}" class="btn btn-outline-secondary  ml-5">Scrape Content</a>
                        <a href="{{route('summarize')}}" class="btn btn-outline-success">Summarize Content</a>
                        <a href="{{route('notes')}}" class="btn btn-outline-danger mr-5">View Notes</a>
                    </div>
                </div>

                <div class="card mt-4 shadow">
                    <div class="card-header">Manage Questions</div>
                    <div class="row card-body justify-content-between">
                        <table class="table table-striped table-sm">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($questions as $question)
                                <tr>
                                    <td>{{$question->question_id}}</td>
                                    <td>{{str_limit($question->question_content,200)}}</td>
                                    <td>{{$question->question_date}}</td>
                                    <td>
                                        @if($question->question_status === '0')
                                            <h5><span class="badge badge-danger">pending</span></h5>
                                        @elseif($question->question_status === '1')
                                            <h5><span class="badge badge-success">approved</span></h5>
                                        @endif
                                    </td>
                                    <td><a href="{{route('show.question',['id'=>$question->question_id])}}" class="btn btn-dark btn-sm"><i data-feather="eye"></i> view</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
