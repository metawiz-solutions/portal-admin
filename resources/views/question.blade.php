@extends('layouts.app')
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$question->question_id}}</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <table class="table table-striped table-sm">
                            <tbody>
                            <tr>
                                <td><strong>Question ID</strong></td>
                                <td>{{$question->question_id}}</td>
                            </tr>
                            <tr>
                                <td><strong>Question Content</strong></td>
                                <td>{{$question->question_content}}</td>
                            </tr>
                            <tr>
                                <td><strong>Question Status</strong></td>
                                <td>{{$question->question_status}}</td>
                            </tr>
                            <tr>
                                <td><strong>Question From</strong></td>
                                <td>{{$author}}</td>
                            </tr>
                            <tr>
                                <td><strong>Question Grade</strong></td>
                                <td>{{$question->question_grade}}</td>
                            </tr>
                            <tr>
                                <td><strong>Question Status</strong></td>
                                <td>
                                    @if($question->question_status === '0')
                                        <h5><span class="badge badge-danger">pending</span></h5>
                                    @elseif($question->question_status === '1')
                                        <h5><span class="badge badge-success">approved</span></h5>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Question Type</strong></td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" id="type" class="form-control" value="{{$question->type}}">
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-outline-dark btn-sm" onclick="getTypeSuggestion()"><i data-feather="filter"></i> Suggest!!</button>
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-outline-primary btn-sm" onclick="saveType()"><i data-feather="save"></i> Save</button>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td><strong>Actions</strong></td>
                                <td>
                                    <div class="row">
                                        <button onclick="approveQuestion()" {{$question->question_status === '1' ? 'disabled': ''}} class="ml-3 btn btn-outline-success mr-4"><i
                                                data-feather="check-circle"></i> Approve
                                        </button>
                                        <button class="btn btn-outline-danger" onclick="deleteQuestion()"><i data-feather="trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function approveQuestion() {
            if (confirm('Are you sure?')) {
                axios.post('{{route('question.approve')}}', {
                    id: parseInt('{{$question->question_id}}')
                }).then(res => {
                    toastr.success('Question Approved!');
                    setTimeout(() => {
                        location.reload()
                    }, 2000);
                }).catch(err => {
                    toastr.error('Error occured!');
                    console.log(err);
                })
            }
        }

        function getTypeSuggestion() {
            axios.post('{{route('question.get.type.suggestion')}}', {
                id: parseInt('{{$question->question_id}}')
            }).then(res => {
                toastr.success('Got suggestion: ' + res.data.type);
                $('#type').val(res.data.type);
            }).catch(err => {
                toastr.error('Error occured!');
                console.log(err);
            })
        }

        function deleteQuestion() {
            if (confirm('Are you sure?')) {
                axios.post('{{route('question.delete')}}', {
                    id: parseInt('{{$question->question_id}}')
                }).then(res => {
                    toastr.success('Question Deleted!');
                    setTimeout(() => {
                        window.location.href = '{{route('home')}}'
                    }, 2000);
                }).catch(err => {
                    toastr.error('Error occured!');
                    console.log(err);
                })
            }
        }

        function saveType() {
            axios.post('{{route('question.save.type')}}', {
                id: parseInt('{{$question->question_id}}'),
                value: $('#type').val()
            }).then(res => {
                toastr.success('Question Type Saved!');
                setTimeout(() => {
                    location.reload()
                }, 2000);
            }).catch(err => {
                toastr.error('Error occured!');
                console.log(err);
            })
        }

    </script>

@endsection
