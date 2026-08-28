@extends('layouts.app')

@section('content')
<!-- Custom Styles for DataTable & UI -->
<style>
    /* Card & Container Aesthetics */
    .filter-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    .table-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* Form Controls */
    .form-control-sm, .form-select-sm {
        border-radius: 6px;
        border: 1px solid #e0e6ed;
        padding: 0.45rem 0.75rem;
        font-size: 0.85rem;
        transition: all 0.2s ease-in-out;
    }
    .form-control-sm:focus, .form-select-sm:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* DataTable Table Styling */
    #disburse_table {
        border-collapse: separate !important;
        border-spacing: 0;
        width: 100% !important;
    }
    #disburse_table thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 12px 16px;
        border-bottom: 2px solid #e2e8f0 !important;
        white-space: nowrap;
    }
    #disburse_table tbody td {
        padding: 12px 16px;
        font-size: 0.875rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    #disburse_table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    /* DataTable Controls Customization */
    .dataTables_wrapper .dataTables_length select {
        border-radius: 6px;
        border: 1px solid #e0e6ed;
        padding: 4px 8px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #4f46e5 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 6px !important;
        font-weight: 600;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e0e7ff !important;
        color: #4338ca !important;
        border: none !important;
        border-radius: 6px !important;
    }

    /* UI Elements */
    .badge-service {
        background-color: #e0e7ff;
        color: #4338ca;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        display: inline-block;
    }
    .disburse-link {
        font-weight: 600;
        color: #4f46e5;
        text-decoration: none;
        background: #f5f3ff;
        padding: 3px 8px;
        border-radius: 6px;
        border: 1px solid #ddd6fe;
        transition: all 0.2s;
    }
    .disburse-link:hover {
        background: #4f46e5;
        color: #ffffff;
    }
    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .btn-action-edit {
        background: #ecfeff;
        color: #0891b2;
        border: 1px solid #cff4fc;
    }
    .btn-action-edit:hover {
        background: #0891b2;
        color: #ffffff;
    }
    .btn-action-delete {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fee2e2;
    }
    .btn-action-delete:hover {
        background: #dc2626;
        color: #ffffff;
    }
    .text-amount {
        font-weight: 600;
        color: #0f172a;
    }
    .text-profit {
        font-weight: 600;
        color: #16a34a;
    }
</style>

<div class="container-fluid py-4 px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #0f172a;">Disbursement Overview</h4>
<style>
    /* Force table to respect explicit widths and handle text overflow gracefully */
    #disburse_table {
        table-layout: fixed !important;
        width: 100% !important;
    }
    
    #disburse_table th, 
    #disburse_table td {
        {{-- white-space: nowrap; --}}
        {{-- overflow: hidden;
        text-overflow: ellipsis; --}}
    }
</style>
            <p class="text-muted small mb-0">Manage and track loan disbursements, commissions, and agent profits.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card filter-card border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
                <i class="fa fa-filter text-primary me-2"></i>
                <h6 class="fw-bold mb-0" style="color: #334155;">Filter Records</h6>
            </div>
            <div class="row g-3">
                <div class="col-xl-2 col-md-4">
                    <label class="form-label small fw-semibold text-secondary">Agent Name</label>
                    <select id="filter_agent_id" class="form-select form-select-sm">
                        <option value="">All Agents</option>
                        @foreach($agent_list as $row)
                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-2 col-md-4">
                    <label class="form-label small fw-semibold text-secondary">Bank Name</label>
                    <select id="filter_bank_name" class="form-select form-select-sm">
                        <option value="">All Banks</option>
                        <option value="Yes Bank">Yes Bank</option>
                        <option value="SBI Bank">SBI Bank</option>
                        <option value="PNB Bank">Panjab National Bank</option>
                    </select>
                </div>
                <div class="col-xl-2 col-md-4">
                    <label class="form-label small fw-semibold text-secondary">Services</label>
                    <select id="filter_services" class="form-select form-select-sm">
                        <option value="">All Services</option>
                        <option value="New">New</option>
                        <option value="Used">Used</option>
                        <option value="Top-Up">Top-Up</option>
                        <option value="B.T. Top-Up">B.T. Top-Up</option>
                        <option value="Purchase">Purchase</option>
                        <option value="Refinance">Refinance</option>
                    </select>
                </div>
                <div class="col-xl-2 col-md-4">
                    <label class="form-label small fw-semibold text-secondary">From Date</label>
                    <input type="date" id="filter_from_date" class="form-control form-control-sm" />
                </div>
                <div class="col-xl-2 col-md-4">
                    <label class="form-label small fw-semibold text-secondary">To Date</label>
                    <input type="date" id="filter_to_date" class="form-control form-control-sm" />
                </div>
                <div class="col-xl-2 col-md-4 d-flex align-items-end gap-2">
                    <button type="button" id="btn_filter" class="btn btn-sm btn-primary w-100 fw-semibold shadow-sm" style="background-color: #4f46e5; border: none; padding: 0.45rem;">
                        <i class="fa fa-search me-1"></i> Search
                    </button>
                    <button type="button" id="btn_reset" class="btn btn-sm btn-light w-100 fw-semibold" style="border: 1px solid #e2e8f0; padding: 0.45rem;">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card table-card border-0">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table align-middle" id="disburse_table">
                    <thead>
                        <tr>
                            <th>Disburse ID</th>   
                            <th>Loan Acc.</th>
                            <th>Agent Name</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Vehicle</th>
                            <th>Bank</th>
                            <th>Services</th>
                            <th>Loan Amount</th>
                            <th>Total Com.</th>
                            <th>Agent Com.</th>
                            <th>Profit</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    var table = $("#disburse_table").DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ordering": false,
        "pageLength": 10,
        "dom": '<"d-flex justify-content-between align-items-center mb-3 px-2"l>rt<"d-flex justify-content-between align-items-center mt-3 px-2"ip>',
        "ajax": {
            "url": "{{ route('disburse.get_all') }}",
            "type": "POST",
            "data": function (d) {
                d._token = "{{ csrf_token() }}";
                d.agent_id = $('#filter_agent_id').val();
                d.bank_name = $('#filter_bank_name').val();
                d.services = $('#filter_services').val();
                d.from_date = $('#filter_from_date').val();
                d.to_date = $('#filter_to_date').val();
            }
        },
        "columns": [
            {
                "data": "disburse_id",
                "render": function(data, type, row) {
                    return '<a class="disburse-link" href="{{ url("disburse/single_case") }}/' + row.case_id + '">' + row.disburse_id + '</a>';
                }
            },   
            { 
                "data": "loan_acc","width": "150px",
                "render": function(data) {
                    return '<span class="fw-semibold text-dark">' + data + '</span>';
                }
            },
            { "data": "agent_name" },
            { "data": "customer_name" },
            { "data": "contact" },
            { "data": "vehicle_model" },
            { "data": "bank_name" },
            {
                "data": "services",
                "render": function(data) {
                    return '<span class="badge-service">' + data + '</span>';
                }
            },
            {
                "data": "loan_amount",
                "render": function(data) {
                    return '<span class="text-amount">₹' + parseFloat(data).toLocaleString('en-IN') + '</span>';
                }
            },
            {
                "data": "total_commission",
                "render": function(data) {
                    return '₹' + parseFloat(data).toLocaleString('en-IN');
                }
            },
            {
                "data": "agent_commission",
                "render": function(data) {
                    return '₹' + parseFloat(data).toLocaleString('en-IN');
                }
            },
            {
                "data": "profit",
                "render": function(data) {
                    return '<span class="text-profit">₹' + parseFloat(data).toLocaleString('en-IN') + '</span>';
                }
            },
            {
                "data": null,
                "className": "text-center",
                "render": function(data, type, row) {
                    return '<div class="d-flex justify-content-center gap-1">' +
                           '<a href="{{ url("disburse/edit") }}/' + row.case_id + '" class="btn-action btn-action-edit" title="Edit"><i class="fa fa-pen" style="font-size: 0.75rem;"></i></a>' +
                           '<a href="javascript:;" class="btn-action btn-action-delete del-sale" data-row-id="' + row.case_id + '" title="Delete"><i class="fa fa-trash" style="font-size: 0.75rem;"></i></a>' +
                           '</div>';
                }
            }
        ],
        "language": {
            "processing": "<div class='spinner-border text-primary spinner-border-sm' role='status'></div> Loading...",
            "paginate": {
                "next": '<i class="fa fa-chevron-right"></i>',
                "previous": '<i class="fa fa-chevron-left"></i>'
            }
        }
    });

    $('#btn_filter').click(function() {
        table.draw();
    });

    $('#btn_reset').click(function() {
        $('#filter_agent_id, #filter_bank_name, #filter_services, #filter_from_date, #filter_to_date').val('');
        table.draw();
    });
});
</script>
@endsection