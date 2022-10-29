@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Rocket Launch</div>

                    <div class="card-body">
                        {!! Form::open(['route' => 'rocket.est', 'method' => 'post', 'id' => 'page-form']) !!}
                            <div class="row mb-3">
                                <label for="rocket" class="col-md-3 col-form-label text-md-end">Rocket <span class='text-danger'>*</span></label>

                                <div class="col-md-6">
                                    {{ Form::select('rocket', ['' => 'Select Rocket', 'a' => 'Rocket A', 'b' => 'Rocket B', 'c' => 'Rocket C'], null, ['id' => 'rocket', 'class' => 'form-control']) }}
                                    <span class="invalid-feedback fw-bold" role="alert"></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="time" class="col-md-3 col-form-label text-md-end">Launch Time <span class='text-danger'>*</span></label>

                                <div class="col-md-6">
                                    {{ Form::text('time', null, ['id' => 'time', 'class' => 'form-control datetimepicker', "required" => true]) }}
                                    <span class="invalid-feedback fw-bold" role="alert"></span>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-3">
                                    <button type="submit" class="submit btn btn-primary mr-7">
                                        Estimate Time
                                    </button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
