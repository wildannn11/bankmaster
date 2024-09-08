@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if(Auth::user()->roles[0]->name == 'bank')
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">{{ __('Table To Action') }}</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Request</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wallet_requests as $key => $wallet_request)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $wallet_request->user->name }}</td>
                                        <td>{{ $wallet_request->description }}</td>
                                        <td>{{ $wallet_request->income - $wallet_request->outcome }}</td>
                                        <td>
                                            <a href="{{ route('acceptWalletRequest', $wallet_request->id) }}" class="btn btn-success">Accept</a>
                                            <a href="{{ route('rejectWalletRequest', $wallet_request->id) }}" class="btn btn-danger">Reject</a>
                                        </td>
                                    </tr>   
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Income</th>
                                    <th>Outcome</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historyWallets as $key => $historyWallet)
                                <tr data-key="{{ $key }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $historyWallet->user->name }}</td>
                                    <td>{{ $historyWallet->income }}</td>
                                    <td>{{ $historyWallet->outcome }}</td>
                                    <td>{{ $historyWallet->status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            @if(Auth::user()->roles && Auth::user()->roles[0]->name === 'customer')
    <div class="container my-5">
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-header text-center">
                <h4 class="text-gradient">Wallet</h4>
            </div>
            <div class="card-body text-center">
                <h2 class="mb-4">Saldo: <span class="text-success fw-bold fs-2">{{ number_format($saldo) }}</span></h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <!-- Button trigger modal Top Up Saldo -->
                        <button type="button" class="btn btn-primary w-100 btn-icon" data-bs-toggle="modal" data-bs-target="#topUpSaldo">
                            <i class="bi bi-plus-circle me-2"></i> Top Up Saldo
                        </button>

                        <!-- Modal Top Up Saldo -->
                        <div class="modal fade" id="topUpSaldo" tabindex="-1" aria-labelledby="topUpSaldoLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="topUpSaldoLabel">Top Up Saldo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('topUpSaldo') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nominal" class="form-label">Nominal</label>
                                                <input type="number" id="nominal" name="nominal" class="form-control form-control-lg" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Top Up</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Button trigger modal Tarik Tunai -->
                        <button type="button" class="btn btn-success w-100 btn-icon" data-bs-toggle="modal" data-bs-target="#TarikTunai">
                            <i class="bi bi-cash me-2"></i> Tarik Tunai
                        </button>

                        <!-- Modal Tarik Tunai -->
                        <div class="modal fade" id="TarikTunai" tabindex="-1" aria-labelledby="TarikTunaiLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="TarikTunaiLabel">Tarik Tunai</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('TarikTunai') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nominal" class="form-label">Nominal</label>
                                                <input type="number" id="nominal" name="nominal" class="form-control form-control-lg" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success">Withdraw</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
        </div>
    </div>
</div>

<style>
    .hover-effect:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        transform: translateY(-2px);
        transition: all 0.3s ease-in-out;
    }

    .card-header {
        background-color: #007bff;
        color: #fff;
        font-size: 1.25rem;
        text-align: center;
    }

    .card-body {
        text-align: center;
    }

    .card-footer {
        padding: 1rem;
    }

    .btn {
        border-radius: 50px;
        padding: 0.75rem;
        font-size: 1rem;
    }
</style>

@endsection
