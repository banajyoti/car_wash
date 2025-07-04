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
   <form class="form-inline" method="POST" action="{{ route('logout') }}" style="position: fixed; top: 20px; right: 20px;">
    @csrf
    <button class="btn btn-outline-danger" type="submit">Logout</button>
</form>


    @if(session('success'))
      <div class="alert alert-success mt-3">
        {{ session('success') }}
      </div>
    @endif

    <div class="mt-4">
      <h2>Sale Processing</h2>
    </div>

    <!-- Cards -->
    <div class="row mt-4 justify-content-center">

      <!-- Basic Plan -->
      <div class="col-md-6 mb-4">
        <div class="card">
          <!-- <div class="card-header bg-primary text-white">Basic Plan</div> -->
          <div class="card-body">
            <h5 class="card-title">Customer Lookup</h5>
            <p class="card-text">Includes exterior wash and tire shine. Ideal for quick clean-ups.</p>

            <!-- Search Input -->
            <div class="input-group mb-3">
              <input type="text" id="basicPlanSearch" class="form-control" placeholder="Search by phone, name, or car no.">
              <button class="btn btn-outline-primary" type="button" id="searchBasicBtn">Lookup</button>
            </div>

            <!-- <a href="#" class="btn btn-outline-primary">Configure</a> -->
          </div>
        </div>
      </div>

      <!-- Search Result Modal with Form -->
      <div class="modal fade" id="searchResultModal" tabindex="-1" aria-labelledby="searchResultModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="{{ route('submit.premium.selection') }}">
            @csrf
            <input type="hidden" name="customer_id" id="modalCustomerId">

            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Lookup Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <div id="searchModalBody">
                  <!-- JS will inject selected customer info here -->
              </div>

              <div class="mb-3">
                <label class="form-label">Segment</label>
                  <select name="sagment_id" class="form-select" id="sagmentSelect" required>
                    <!-- <option value="" disabled selected>Select Sagment</option> -->
                    @foreach ($segments as $sagment)
                      <option value="{{ $sagment->id }}">{{ $sagment->name }}</option>
                    @endforeach
                  </select>
              </div>

              <div class="mb-3">
                  <label class="form-label">Wash Type</label>
                  <input type="text" name="wash_type_name" id="washTypeInput" class="form-control" readonly required>
                  <input type="hidden" name="wash_type_id" id="washTypeId">
              </div>
                
              <div class="mb-3" id="priceWrapper">
                <label class="form-label">Price</label>
                <input type="text" name="prices" id="priceInput" class="form-control" readonly required>
              </div>
              </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-success">Calculate Price</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div> <!-- row -->
  </div> <!-- content -->

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

  <script>
    $('#searchBasicBtn').on('click', function () {
      const query = $('#basicPlanSearch').val().trim();

      if (!query) {
        alert('Please enter a search term.');
        return;
      }

      $.ajax({
        url: '{{ route("search.basic.plan") }}',
        method: 'GET',
        data: { query },
        success: function (response) {
          let html = '';

          if (response.length > 0) {
            response.forEach(user => {
              html += `<div class="border p-2 mb-2">
                <strong>ID:</strong> ${user.id}<br>
                <strong>Name:</strong> ${user.name}<br>
                <strong>Phone:</strong> ${user.phone}<br>
                <strong>Car No:</strong> ${user.car_no}<br>
                <strong>Total Washes:</strong> ${user.washes_count}<br>
                <button type="button" class="btn btn-sm btn-primary mt-2"
                  onclick="selectCustomer('${user.id}', '${user.name}', '${user.phone}', '${user.car_no}', ${user.washes_count})">
                  Select Customer
                </button>
              </div>`;
            });
          } else {
            html = '<p>No results found.</p>';
          }

          $('#searchModalBody').html(html);
          const modal = new bootstrap.Modal(document.getElementById('searchResultModal'));
          modal.show();
        },
        error: function () {
          $('#searchModalBody').html('<p class="text-danger">Something went wrong.</p>');
          const modal = new bootstrap.Modal(document.getElementById('searchResultModal'));
          modal.show();
        }
      });
    });

    function selectCustomer(id, name, phone, carNo, washesCount) {
      // Set hidden field
      $('#modalCustomerId').val(id);
      console.log($('#modalCustomerId').val(id))

      // Show confirmation
      const info = `
        <div class="alert alert-info">
          <strong>Selected Customer:</strong><br>
          Name: ${name}<br>
          Phone: ${phone}<br>
          Car No: ${carNo}<br>
          Washes: ${washesCount}
        </div>
      `;
      $('#searchModalBody').html(info);
      const priceWrapper = document.getElementById('priceWrapper');
      if (washesCount > 3) {
        priceWrapper.style.display = 'none';
      } else {
        priceWrapper.style.display = 'block';
      }
    }
  </script>
 <script>
    document.getElementById('sagmentSelect').addEventListener('change', function () {
    const sagmentId = this.value;

    // Clear existing input values
    document.getElementById('washTypeInput').value = '';
    document.getElementById('priceInput').value = '';
    document.getElementById('washTypeId').value = '';

    fetch(`/api/sagment/${sagmentId}/data`)
      .then(res => res.json())
      .then(data => {
        if (data.length > 0) {
          const firstItem = data[0]; // Select first result or adjust logic for multiple
          document.getElementById('washTypeInput').value = formatWashType(firstItem.name);
          document.getElementById('washTypeId').value = firstItem.id; // or firstItem.wash_type_id
          document.getElementById('priceInput').value = firstItem.prices;
        } else {
          document.getElementById('washTypeInput').value = 'No data';
          document.getElementById('priceInput').value = '0';
        }
      })
      .catch(err => {
        console.error(err);
        alert('Error fetching data');
      });
  });

  function formatWashType(name) {
    switch (name) {
      case 'full_vacuum': return 'Full + Vacuum';
      case 'full_only': return 'Full Only';
      case 'outside_only': return 'Outside Only';
      default: return name;
    }
  }
</script>


</body>
</html>
