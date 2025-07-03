<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
          <h4 class="mb-0">Edit Customer</h4>
        </div>

        <div class="card-body">

          @if(session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label">Customer Name</label>
              <input type="text" name="customerName" class="form-control" value="{{ $customer->name }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Phone Number</label>
              <input type="text" name="customerPhone" class="form-control" value="{{ $customer->phone }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Car Number</label>
              <input type="text" name="car_no" class="form-control" value="{{ $customer->car_no }}" required>
            </div>

            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-success">Update</button>
              <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back</a>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">