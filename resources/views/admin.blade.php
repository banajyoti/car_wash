<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Car Wash Admin Panel</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

  <style>
    body { min-height: 100vh; display: flex; }
    .sidebar { width: 250px; background-color: #343a40; color: white; }
    .sidebar a { color: white; padding: 15px; display: block; text-decoration: none; }
    .sidebar a:hover { background-color: #495057; }
    .content { flex-grow: 1; padding: 20px; }
  </style>
</head>
<body>

  <div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white">Car Wash Admin</h4>
    <hr class="text-white" />
    <a href="#">Dashboard</a>
    <a href="{{ route('admin.dashboard') }}">Customers Information</a>
    <a href="{{ route('get-plan') }}">Plan Configurator</a>
    <a href="{{ route('get-processing') }}">Sale Processing</a>
    <a href="#">Reporting</a>
    <a href="#">Mobile Workflow</a>
  </div>

  <div class="content">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Dashboard</span>
      </div>
    </nav>

    <div class="mt-4 d-flex justify-content-start">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
        + Add Customer
      </button>
    </div>

    <nav class="navbar navbar-light bg-light justify-content-between">
  <a class="navbar-brand">Admin Dashboard</a>
 <form class="form-inline" method="POST" action="{{ route('logout') }}" style="position: fixed; top: 20px; right: 20px;">
    @csrf
    <button class="btn btn-outline-danger" type="submit">Logout</button>
</form>

</nav>


    @if(session('success'))
      <div class="alert alert-success mt-3">
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger mt-2">
        {{ session('error') }}
      </div>
    @endif

    <div class="mt-4">
      <h2>Welcome to Car Wash Admin Panel</h2>
      <p>This is your dashboard. Select a menu item to manage your car wash operations.</p>
    </div>

    <div class="mt-5">
      <h4>Customer List</h4>
      <table id="customerTable" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Car Number</th>
            <th>Total Washes</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($customers as $index => $customer)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $customer->name }}</td>
              <td>{{ $customer->phone }}</td>
              <td>{{ $customer->car_no }}</td>
              <td>{{ $customer->washes_count }}</td>
              <td>
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                    Delete
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add Customer Modal -->
  <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="{{ route('customers.store') }}">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Add New Customer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Customer Name</label>
              <input type="text" name="customerName" class="form-control" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Phone Number</label>
              <input type="text" onkeypress="return isNumber(event)" name="customerPhone" maxlength="10" minlength="10" class="form-control" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Car Number</label>
              <input type="text" name="car_no" class="form-control" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="text" name="password" class="form-control" required />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Customer</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#customerTable').DataTable();
    });
  </script>
  <script>
     function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

</script>
</body>
</html>
