<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Class</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="calssUpdateForm"
                    data-store-url="{{ route('classes.update', ['id' => 'ID']) }}"
                    data-index-url="{{ route('classes.classindex') }}">
                    <div class="form-group">
                        <label for="exampleIputName1">Class</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Name"
                            name="class_name" id="class_name" value="{{ $class->class_name }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="updateClass('{{ $class->id }}')">Save
                            changes</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

