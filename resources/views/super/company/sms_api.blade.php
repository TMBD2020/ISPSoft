<a class="nav-link back pull-right"><i class="la la-arrow-left"></i> Back To List</a>
<hr style="overflow: hidden; clear:both;">
<div class="card">
    <div class="card-body">
        <div class="col-md-8">
            <table>
                <tr>
                    <td>Company Name</td>
                    <td>:</td>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <td>Mobile</td>
                    <td>:</td>
                    <td>{{ $user->mobile }}</td>
                </tr>
                <tr>
                    <td>Email Address</td>
                    <td>:</td>
                    <td>{{ $user->email_id }}</td>
                </tr>
                <tr>
                    <td>Contact Address</td>
                    <td>:</td>
                    <td>{{ $user->address }}</td>
                </tr>

            </table>

        </div>
    </div>
    <div class="card-footer">
        <form id="APIForm" method="post">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id" value="{{ $user->admin->id }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="approval">SMS API <span class="text-danger">*</span> </label>
                        <select name="sms_api_id" class="form-control select3" required>
                            <option value="">Select One</option>
                            @foreach ($sms_api as $item)
                                <option value="{{ $item->id }}" @if ($item->id == $user->sms_api_id) selected @endif>
                                    {{ $item->api_name }} - {{ $item->api_sender }} </option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="approval">Masking Rate <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="{{ $user->masking_rate }}" name="masking_rate"
                            id="masking_rate" placeholder="Masking Rate">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="approval">Non-asking Rate <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="{{ $user->non_masking_rate }}"
                            name="non_masking_rate" id="non_masking_rate" placeholder="Non-masking Rate">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary mt-2 save">Save</button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    $(".select3").select2();
    $(document).off('submit', "#APIForm").on('submit', "#APIForm", function(e) {
        e.preventDefault();

        if (confirm("Are you sure to save this?")) {
            $(".save").text("Saving...").prop("disabled", true);
            $.ajax({
                type: "POST",
                url: "{{ route('super.sms_api_set') }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    $(".save").text("Save").prop("disabled", false);
                    // console.log(response);
                    if (response.status) {
                        toastr.success(response.message, 'Success');
                    } else {
                        toastr.warning(response.message, 'Warning');
                    }
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                    toastr.warning('Server Error! Try again.', 'Warning');
                    $(".save").text("Save").prop("disabled", false);
                }
            });
        }
        return false;
    });
</script>
