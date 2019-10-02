@extends('layouts.app')
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{route('summarize')}}">Summarize</a></li>
                <li class="breadcrumb-item active">{{$note->id}} | {{$note->title}}</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <table class="table table-striped table-borderless">
                            <tr>
                                <td><strong>URL</strong></td>
                                <td>{{$note->url}}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                @if($note->status === 1)
                                    <td><h4><span class="badge badge-success">scrape success</span></h4></td>
                                @elseif($note->status === 2)
                                    <td><h4><span class="badge badge-danger">scrape failed</span></h4></td>
                                @elseif($note->status === 3)
                                    <td><span class="badge badge-success">summarization success</span></td>
                                @elseif($note->status === 4)
                                    <td><span class="badge badge-danger">summarization fail</span></td>
                                @endif
                            </tr>
                            <tr>
                                <td><strong>Title</strong></td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-11">
                                            <input type="text" name="title" id="titleText" class="form-control" value="{{$note->title}}">
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-primary btn-sm mt-1" onclick="updateField('title','titleText')">Save</button>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td><strong>Grade</strong></td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-11">
                                            <input type="text" name="grade" id="gradeText" class="form-control" value="{{$note->grade}}">
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-primary btn-sm mt-1" onclick="updateField('grade','gradeText')">Save</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Subject</strong></td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-11">
                                            <input type="text" name="subject" id="subjectText" class="form-control" value="{{$note->subject}}">
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-primary btn-sm mt-1" onclick="updateField('subject','subjectText')">Save</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr style="background: rgb(222, 255, 236)">
                                <td><strong>Scraped Text</strong></td>
                                <td style="font-size: 10px;">
                                    <div style="max-height: 150px; overflow-y: scroll">
                                        {{$note->scraped_text}}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Added By</strong></td>
                                <td>{{$note->added_by}}</td>
                            </tr>
                            <tr>
                                <td><strong>Actions</strong></td>
                                <td>
                                    @if($note->status === 2)
                                        <button class="btn btn-outline-info btn-sm mr-4"><i data-feather="refresh-ccw"></i> Retry</button>
                                    @else
                                        <button onclick="summarize()" class="btn btn-outline-primary btn-sm mr-4"><i data-feather="align-center"></i> Summarize</button>
                                    @endif

                                    <a href="" class="btn btn-outline-danger btn-sm" onclick="return deleteNote()"><i data-feather="trash"></i> Delete</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateField(fieldName, element_id) {
            let value = document.getElementById(element_id).value;
            axios.post('{{route('update.note.attributes',['id'=>$note->id])}}', {
                fieldName: fieldName,
                value: value
            }).then(res => {
                if (res.data.code === 200) {
                    toastr.success(`${fieldName} saved.`)
                } else {
                    toastr.success(`Server sent an error!`)
                }
            }).catch(err => {
                console.log(err);
                toastr.error(err.toString());
            })
        }

        function summarize() {
            axios.get('{{route('summarize.note',['id'=>$note->id])}}').then(res => {
                window.location.href = '{{route('show.note',['id' => $note->id])}}';
            }).catch(err => {
                console.log(err);
                toastr.error('Error in Retry!');
            });
        }

        function deleteNote() {
            if (window.confirm('Are you sure?')) {
                axios.delete('{{route('delete.note',['id' => $note->id])}}').then(() => {
                    window.location.href = '{{route('summarize')}}';
                }).catch(err => {
                    toastr.error('Error deleting Note');
                })
            } else {
                console.log('cancelled');
            }
        }

    </script>

@endsection
