@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{asset('css/table.css')}}">
@endpush
@section('content')
<div class="container">
    @isset($results)
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Number of similar lines:<strong>{{$results['similar']}}</strong>
                        Number of difference lines:<strong>{{$results['difference']}}</strong>
                    </div>
                </div>
            </div>
        </div>
    @endisset

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="{{isset($results['table'])?'':'active'}}">
                            <a data-toggle="tab" href="#compare">Compare</a>
                        </li>
                        <li class="{{isset($results['table'])?'active':''}}">
                            <a data-toggle="tab" href="#result">Result</a>
                        </li>
                    </ul>
                </div>

                <div class="panel-body">
                    @if ($errors->any())
                        <div class="alert alert-warning">
                            ! @foreach($errors->all() as $error)
                                {{$error}} 
                              @endforeach
                        </div>
                    @endif

                    <div class="tab-content">
                        <div id="compare" class="tab-pane {{isset($results['table'])?'':'active'}}">
                            <form class="form-horizontal" method="POST" action="{{ route('compare.store') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
        
                                <div class="form-group{{ $errors->has('first_student') ? ' has-error' : '' }}">
                                    <label for="first_student" class="col-md-4 control-label">First Student Name</label>
        
                                    <div class="col-md-6">
                                        <input id="first_student" type="text" class="form-control" name="first_student" value="{{ old('first_student') }}" required autofocus>
        
                                        @if ($errors->has('first_student'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('first_student') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group{{ $errors->has('first_file') ? ' has-error' : '' }}">
                                    <label for="first_file" class="col-md-4 control-label">First Student File</label>
        
                                    <div class="col-md-6">
                                        <input id="first_file" type="file" class="form-control" name="first_file" required>
        
                                        @if ($errors->has('first_file'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('first_file') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('second_student') ? ' has-error' : '' }}">
                                    <label for="first_student" class="col-md-4 control-label">Second Student Name</label>
        
                                    <div class="col-md-6">
                                        <input id="second_student" type="text" class="form-control" name="second_student" value="{{ old('second_student') }}" required autofocus>
        
                                        @if ($errors->has('second_student'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('second_student') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group{{ $errors->has('second_file') ? ' has-error' : '' }}">
                                    <label for="second_file" class="col-md-4 control-label">Second Student File</label>
        
                                    <div class="col-md-6">
                                        <input id="second_file" type="file" class="form-control" name="second_file" required>
        
                                        @if($errors->has('second_file'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('second_file') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Compare
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="result" class="tab-pane {{isset($results['table'])?'active':''}}">
                            {!! isset($results['table'])?$results['table']:'' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
