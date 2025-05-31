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
                <tr>
                    <td>Registration Date</td>
                    <td>:</td>
                    <td>{{ date('d M, Y', strtotime($user->created_at)) }}</td>
                </tr>
                <tr>
                    <td>Approved Date</td>
                    <td>:</td>
                    <td>
                        @if ($user->approve_date && $user->approval == 1)
                            {{ date('d M, Y', strtotime($user->approve_date)) }}
                        @endif
                    </td>
                </tr>
            </table>

        </div>
    </div>
    <div class="card-footer">
        <form id="DataForm" method="post">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id" value="{{ $user->admin->id }}">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="approval">Client Status</label>
                        <select id="approval" class="form-control"
                            @if ($user->approval == 2) name="approval" @else disabled @endif>
                            <option value="">Select One</option>
                            <option value="1" @if ($user->approval == 1) selected @endif>Approved</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status">Login Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Select One</option>
                            <option value="1" @if ($user->admin->is_active == 1) selected @endif>Active</option>
                            <option value="2" @if ($user->admin->is_active == 2) selected @endif>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary mt-2 save">Save</button>
                </div>
            </div>
        </form>
        <form id="resetPassword" method="post">
            {{ csrf_field() }}
            <div> <b class="newpass"></b></div>
            <input type="hidden" id="id" name="id" value="{{ $user->admin->id }}">
            <button type="submit" class="btn btn-danger mt-1 reset"><i class="la la-key"></i> Reset Password</button>
        </form>
    </div>
</div>

<script>
    $(document).off('submit', "#DataForm").on('submit', "#DataForm", function(e) {
        e.preventDefault();

        if (confirm("Are you sure to save this?")) {
            $(".save").text("Saving...").prop("disabled", true);
            $.ajax({
                type: "POST",
                url: "{{ route('super.save_company') }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    $(".save").text("Save").prop("disabled", false);
                    console.log(response);
                    if (response.id) {
                        toastr.success('Data Saved Successfully!', 'Success');
                        reload();
                        loadCompany()
                    } else {

                        msg = ''

                        toastr.warning("Data Cannot Saved! Try again.", 'Warning');
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

    $(document).off('submit', "#resetPassword").on('submit', "#resetPassword", function(e) {
        e.preventDefault();
        $(".newpass").empty();
        if (confirm("Are you sure to reset password?")) {
            $(".reset").html("Resetting...").prop("disabled", true);
            $.ajax({
                type: "POST",
                url: "{{ route('super.reset_password') }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    $(".reset").html('<i class="la la-key"></i> Reset Password').prop("disabled",
                        false);
                    // console.log(response);
                    if (response[0]) {
                        toastr.success('Password Reset Successfully!', 'Success');
                        $(".newpass").html(`New password is ${response[1]}`);
                    } else {
                        toastr.warning("Failed to reset password! Try again.", 'Warning');
                    }
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                    toastr.warning('Server Error. Try aging!', 'Warning');
                    $(".reset").html('<i class="la la-key"></i> Reset Password').prop("disabled",
                        false);
                    $(".newpass").empty();
                }
            });
        }
        return false;
    });

    function reload() {
        $.ajax({
            type: "POST",
            url: "{{ route('super.company_profile') }}",
            data: {
                id: "{{ $user->id }}",
                tab: "profile",
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
              //  console.log(response)
                $("#operation").html(response);
            },
            error: function(request, status, error) {
                console.log(request.responseText);
            }
        });
    }
</script>
