@if(!empty($options))

    @foreach($options as $key => $value)

        <div class="options_doctor alert-success" style="padding: 8px;" data-doctor="{{ $key }}"><a>{{ $value }}</a></div>

    @endforeach

@elseif(isset($error))

    <p>{{$error}}</p>

@endif
<script type="text/javascript">
    $(".options_doctor").click(function(){
        console.log('ff');
        var doctor_id = $(this).data('doctor');
        var token = $("input[name='_token']").val();
        $.ajax({
            url: "{{ route('dashboard.ajax.doctor-clinics') }}",
            method: 'GET',
            data: {doctor_id:doctor_id, _token:token},
            success: function(data) {
                $("#clinic-id").html('');
                $("#clinic-id").html(data.options);
            }
        });
    });
</script>