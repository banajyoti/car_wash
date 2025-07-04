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
    <a href="{{ route('get-processing') }}">Sale Processing</a>
  </div>

  <div class="content">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Dashboard</span>
      </div>
    </nav>

    <nav class="navbar navbar-light bg-light justify-content-between">
    <a class="navbar-brand">Admin Dashboard</a>
    <form class="form-inline" method="POST" action="{{ route('logout') }}">
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
      <h2>Welcome to Car Wash</h2>
      <p>This is your dashboard. Select a menu item to manage your car wash operations.</p>
    </div>

    <div class="mt-5">
    </div>
  </div>

  <!-- Add Customer Modal -->

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
