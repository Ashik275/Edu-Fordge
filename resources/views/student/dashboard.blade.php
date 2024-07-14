@extends('admin.app')
@section('content')
<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('admin-assets') }}/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                 
                @if(isset($exam_name))
                <h2 class="mb-5">Exam</h2>
                <h6 class="font-weight-normal mb-3">Exam Name : {{$exam_name}}</i>
                <h6 class="font-weight-normal mb-3">Subject Name : {{$subject_name}}</i>
                <h6 class="font-weight-normal mb-3">Exam Time : {{$exam_time}}</i>
                 @else
                 <h2 class="mb-5">No Exam Today</h2>
                 <p>There are no exams scheduled for today.</p>  
                 @endif
         
            </div>
        </div>
    </div>
</div>
@endsection
