<div data-repeater-item>
  <div class="row d-flex align-items-end">

      <div class="col-md-3 col-12">
          <div class="form-group">
              <label for="select-doctor">Doctor</label>
              <select class="form-control" name="doctor_id_{{$count+1}}" required>
                  <option value="">Select doctor</option>
                  @foreach ($doctors as $doctor)
                      <option value="{{$doctor->id}}">{{$doctor->name}}</option>
                  @endforeach                                 
              </select>
          </div>
      </div>

      <div class="col-md-3 col-12">
          <div class="form-group">
              <label for="select-clinic">Clinic</label>
              <select class="form-control" name="clinic_ids[{{$count+1}}]" required>
                  
              </select>
          </div>
      </div>
      
      <div class="col-md-3 col-12">
          <div class="form-group">
              <label for="itemvisits">Visits</label>
              <input type="number" name="visits[{{$count+1}}]" class="form-control" id="itemvisits" aria-describedby="itemvisits" />
          </div>
      </div>

      <div class="col-md-3 col-12 mb-50">
          <div class="form-group">
              <button class="btn btn-outline-danger text-nowrap px-1"
                  data-repeater-delete type="button">
                  <i data-feather="x" class="mr-25"></i>
                  <span>Delete</span>
              </button>
          </div>
      </div>
  </div>
  <hr />
</div>


<script type="text/javascript">
    $("select[name='doctor_id_{{$count+1}}']").change(function(){
        var doctor_id = $(this).val();
        var token = $("input[name='_token']").val();
        $.ajax({
            url: "{{ route('dashboard.ajax.doctor-clinics') }}",
            method: 'GET',
            data: {doctor_id:doctor_id, _token:token},
            success: function(data) {
              $("select[name='clinic_ids[{{$count+1}}]'").html('');
              $("select[name='clinic_ids[{{$count+1}}]'").html(data.options);
            }
        });
    });
</script>