@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Staking Plans</h3>
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#createPlanModal">
                        Add New Plan
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Duration (days)</th>
                                <th>Interest Rate (%)</th>
                                <th>Min Amount</th>
                                <th>Max Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans as $plan)
                            <tr>
                                <td>{{ $plan->name }}</td>
                                <td>{{ $plan->duration }}</td>
                                <td>{{ $plan->interest_rate }}</td>
                                <td>{{ $plan->minimum_amount }}</td>
                                <td>{{ $plan->maximum_amount }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-btn"
                                            data-plan="{{ $plan }}"
                                            data-toggle="modal"
                                            data-target="#editPlanModal">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.plans.destroy', $plan) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
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
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createPlanModal" tabindex="-1" aria-labelledby="createPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPlanModalLabel">Create New Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.plans.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration (days)</label>
                        <input type="number" name="duration" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="interest_rate" class="form-label">Interest Rate (%)</label>
                        <input type="number" step="0.01" name="interest_rate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="minimum_amount" class="form-label">Minimum Amount</label>
                        <input type="number" step="0.01" name="minimum_amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="maximum_amount" class="form-label">Maximum Amount</label>
                        <input type="number" step="0.01" name="maximum_amount" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlanModalLabel">Edit Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPlanForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_duration" class="form-label">Duration (days)</label>
                        <input type="number" name="duration" id="edit_duration" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_interest_rate" class="form-label">Interest Rate (%)</label>
                        <input type="number" step="0.01" name="interest_rate" id="edit_interest_rate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_minimum_amount" class="form-label">Minimum Amount</label>
                        <input type="number" step="0.01" name="minimum_amount" id="edit_minimum_amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_maximum_amount" class="form-label">Maximum Amount</label>
                        <input type="number" step="0.01" name="maximum_amount" id="edit_maximum_amount" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.edit-btn').click(function() {
        const plan = $(this).data('plan');
        const form = $('#editPlanForm');

        form.attr('action', `/admin/plans/${plan.id}`);
        $('#edit_name').val(plan.name);
        $('#edit_duration').val(plan.duration);
        $('#edit_interest_rate').val(plan.interest_rate);
        $('#edit_minimum_amount').val(plan.minimum_amount);
        $('#edit_maximum_amount').val(plan.maximum_amount);
    });
});
</script>
@endpush
