@extends('layouts.app')
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Scrape</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <form method="POST" action="{{route('scrape.process')}}">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Scrape Address</label>
                                <input type="text" name="url" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="www.nasa.gov/blogs/3fec5.html">
                                <small id="emailHelp" class="form-text text-muted">This URL will be scraped and persisted</small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Retry again if failed</label>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary btn-block">Add Job</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
