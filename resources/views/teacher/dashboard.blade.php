@extends('admin.app')
@section('content')
<div class="row">

    <div class="col-md-4 stretch-card grid-margin">
        @php
            $subjectNames = [];
            foreach (explode(',', $teacher->subject_id) as $subjectId) {
                $subject = \App\Models\Subjects::find($subjectId);
                if ($subject) {
                    $subjectNames[] = $subject->sub_name;
                }
            }
        @endphp
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('admin-assets') }}/assets/images/dashboard/circle.svg" class="card-img-absolute"
                    alt="circle-image" />

                <h2 class="mb-5">Subject</h2>
                <h6 class="font-weight-normal mb-3"> {{ implode(', ', $subjectNames) }}</i>
                </h6>
            </div>
        </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
        @php
            $classNames = [];
            foreach (explode(',', $teacher->class_id) as $classID) {
                $class = \App\Models\Classes::find($classID);
                if ($class) {
                    $classNames[] = $class->class_name;
                }
            }
        @endphp
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('admin-assets') }}/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />

                <h2 class="mb-5">Classes</h2>
                <h6 class="font-weight-normal mb-3"> {{ implode(', ', $classNames) }}</i>
            </div>
        </div>
    </div>
</div>
@endsection
