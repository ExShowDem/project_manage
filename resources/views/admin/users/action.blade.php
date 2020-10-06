<div class="clearfix flex">
    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
        Edit
    </a>
    <form method="post" action="{{ route('users.destroy', $user->id) }}">
        <input name="_method" type="hidden" value="DELETE">
        {{ csrf_field() }}
        <button type="button" class="btn btn-danger buttonDeleteUser">
            Delete
        </button>
    </form>
</div>

<script>
    function confirmMessage(form) {
        swal({
            title: "Are you sure?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((success) => {
                if (success) {
                    form.submit();
                } else {
                }
            });
    }

    $(document).ready(function () {
        $('.buttonDeleteUser').on('click', function () {
            confirmMessage($(this).parent());
        });
    });
</script>
