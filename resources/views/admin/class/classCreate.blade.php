@extends('admin.app')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Class Creation</h4>
            <p class="card-description"> Class Creation </p>
            <form class="forms-sample" id="calssCreateForm" data-store-url="{{ route('classes.store') }}" data-index-url="{{ route('classes.classindex') }}">
                <div class="form-group">
                    <label for="exampleIputName1">Class</label>
                    <input type="text" class="form-control" id="exampleInputName1" placeholder="Name" name="class_name" id="class_name">
                </div>
                <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                <button class="btn btn-light">Cancel</button>
            </form>
        </div>
    </div>
</div>
@endsection