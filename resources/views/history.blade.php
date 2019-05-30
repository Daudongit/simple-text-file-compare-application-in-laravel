@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                       History of previous subjects
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>First Student</th>
                                    <th>Second Student</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @forelse ($histories as $history)
                                <tr>
                                    <td>{{$loop->index + 1}}</td>
                                    <td>{{$history->first_student}}</td>
                                    <td>{{$history->second_student}}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" 
                                            href="{{route('compare.show',$history->id)}}">
                                            <i class="fa fa-eyes" aria-hidden="true"></i>
                                            view
                                        </a>
                                        <a class="btn btn-success btn-sm" 
                                            href="{{route('compare.update',$history->id)}}">
                                            <i class="fa fa-eyes" aria-hidden="true"></i>
                                            re-compute
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div>
                                            No history has been found. Try to add one 
                                            <a href="{{route('compare.create')}}">Now</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                        <div class="text-center">{{$histories->render()}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection