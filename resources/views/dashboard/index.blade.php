@extends('layouts.default')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endsection
@section('script')
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script>
    let table_history_transaction_list = null;
    $(".flatpickr-input").flatpickr();

    (function() {
        loadHistoryTransaction();

        $('#disable_date').on('change', function() {
            if (this.value == 'false') {
                this.value = 'true';

                $('#start_date').attr('disabled', 'disabled');
                $('#end_date').attr('disabled', 'disabled');
            } else {
                this.value = 'false';

                $('#start_date').removeAttr('disabled');
                $('#end_date').removeAttr('disabled');
            }
        });
    })();

    function loadHistoryTransaction() {
        table_history_transaction_list = $('#history_transaction_list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
            ajax: {
                url: '{{ route("historyTransactionGetData") }}',
                method: 'GET',
                data: function(value) {
                    value.start_date = $('#start_date').val()
                    value.end_date = $('#end_date').val()
                    value.disable_date = $('#disable_date').val()
                    value.description = $('#description').val()
                    value.type_transaction = $('#type_transaction').val()
                },
            },
            columns: [
                { data: 'type', name: 'transactions.type', render: function(value, params, data) {
                    const string = value.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    })
                    return `${string}`;
                } },
                { data: 'amount', name: 'transactions.amount', render: function(value, params, data) {
                    return `<span class="badge bg-${data.type === 'topup' ? 'success' : 'danger'}">${formatRupiah(value.toString(), 'Rp. ')}</span>`;
                } },
                { data: 'description', name: 'transactions.description', render: function(value, params, data) {
                    return `${value}`;
                } }
            ],
        });
    }

    function drawTable() {
        table_history_transaction_list.draw();
    }

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>
@endsection

@section('content')
<div class="content-header row"></div>
<div class="content-body">
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="alert-body">
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-body">
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <section id="dashboard-ecommerce">
        <div class="row match-height">
            <!-- Statistics Card -->
            <div class="col-xl-12 col-md-12 col-12">
                <div class="card card-statistics">
                    <div class="card-header">
                        <h4 class="card-title">Balance</h4>
                        <div class="d-flex align-items-center">
                            <p class="card-text font-small-2 me-25 mb-0">&nbsp;</p>
                        </div>
                    </div>
                    <div class="card-body statistics-body">
                        <div class="row">
                            <div class="col-xl-3 col-sm-6 col-12">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-success me-2">
                                        <div class="avatar-content">
                                            <i data-feather="credit-card" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">Rp. {{ number_format(Auth::user()->balance, 2, ',', '.') }}</h4>
                                        <p class="card-text font-small-3 mb-0"><a href="{{ route('transaction') }}">Transaction</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Statistics Card -->
        </div>
    </section>

    <section id="responsive-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">History Balance</h4>
                    </div>
                    <div class="card-body mt-2">
                        <div class="row g-1 mb-md-1">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="false" id="disable_date" />
                                    <label class="form-check-label">Disable Date</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Start Date</label>
                                <input type="text" class="form-control flatpickr-input" id="start_date" name="start_date" value="{{ $start_date }}" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Date</label>
                                <input type="text" class="form-control flatpickr-input" id="end_date" name="end_date" value="{{ $end_date }}" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" placeholder="Description" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Type</label>
                                <select class="form-select select2" id="type_transaction">
                                    <option value="">-- Please Select --</option>
                                    <option value="topup">Topup</option>
                                    <option value="transaction">Transaction</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-1">
                            <div class="col-md-4">
                                <button class="btn btn-icon btn-primary" type="button" onClick="drawTable()">
                                    <i data-feather="search"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-datatable" style="margin: 0px 20px 0px 20px;">
                        <table class="dt-responsive table" id="history_transaction_list">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Descrption</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Descrption</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection