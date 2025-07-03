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
        + Add Plan
      </button>
    </div>

    @if(session('success'))
      <div class="alert alert-success mt-3">
        {{ session('success') }}
      </div>
    @endif

    <div class="mt-4">
      <h2>Pricing Matrix</h2>
    </div>

    <div class="mt-5">
      <h4>Customer List</h4>
     <table id="customerTable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Segment Name</th>
      <th>Segment Type</th>
      <th>Price</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($sagments as $s)
      <tr>
        <td>{{ $s->sg }}</td>
        <td>
          @if ($s->name == 'full_vacuum')
            Full + Vacuum
          @elseif ($s->name == 'full_only')
            Full Only
          @else
            Outside Only
          @endif
        </td>
        <td>{{ $s->prices }}</td>
        <td>
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $s->id }}">
            Edit
          </button>
        </td>
      </tr>

      <!-- Edit Modal -->
      <div class="modal fade" id="editModal{{ $s->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $s->id }}" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="{{ route('plans.update', $s->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $s->id }}">Edit Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Segment Name</label>
                  <input type="text" name="sg" class="form-control" value="{{ $s->sg }}" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Segment Type</label>
                  <select name="name" class="form-select" required>
                    <option value="full_vacuum" {{ $s->name == 'full_vacuum' ? 'selected' : '' }}>Full + Vacuum</option>
                    <option value="full_only" {{ $s->name == 'full_only' ? 'selected' : '' }}>Full Only</option>
                    <option value="outside_only" {{ $s->name == 'outside_only' ? 'selected' : '' }}>Outside Only</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Price</label>
                  <input type="number" name="prices" class="form-control" value="{{ $s->prices }}" required>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    @endforeach
  </tbody>
</table>

    </div>
  </div>

  <!-- Add Customer Modal -->
  <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- resources/views/plans/create.blade.php -->

<form method="POST" action="{{ route('plans.store') }}">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Add New Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">

        <div class="mb-3">
            <label class="form-label">Segment</label>
            <select name="sagment_id" class="form-select" required>
                <option value="" disabled selected>Select Segment</option>
                @foreach ($segments as $segment)
                    <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Segment Type</label>
            <select name="name" class="form-select" required>
                <option value="" disabled selected>Select Segment Type</option>
                <option value="full_vacuum">Full + Vacuum</option>
                <option value="full_only">Full Only</option>
                <option value="outside_only">Outside Only</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="prices" class="form-control" required>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Plan</button>
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
</body>
</html>
