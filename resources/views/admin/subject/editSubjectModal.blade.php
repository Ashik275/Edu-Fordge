<!-- Modal -->
<div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="subjectModalLabel">Edit Subject</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="subjectUpdateForm">
                    <div class="form-group">
                        <label for="exampleIputName1">Subject</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Name"
                            name="sub_name" id="sub_name" value="{{ $subject->sub_name }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="updateSubject('{{ $subject->id }}')">Save
                            changes</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

