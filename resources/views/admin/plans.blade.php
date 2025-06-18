<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Staking Plans</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen p-6">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Staking Plans Management</h1>
                    <button onclick="showAddPlanForm()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Add New Plan
                    </button>
                </div>

                <!-- Add/Edit Plan Form (Hidden by default) -->
                <div id="planForm" class="hidden mb-8 bg-gray-50 rounded-lg p-4">
                    <h2 id="formTitle" class="text-lg font-semibold mb-4">Add New Plan</h2>
                    <form onsubmit="handleSubmit(event)" class="space-y-4">
                        <input type="hidden" id="planId">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Plan Name</label>
                                <input type="text" id="name" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Minimum Amount (TRX)</label>
                                <input type="number" id="minimum_amount" required step="0.000001"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Daily ROI (%)</label>
                                <input type="number" id="daily_roi" required step="0.01"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Duration (Days)</label>
                                <input type="number" id="duration_days" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total ROI (Multiplier)</label>
                                <input type="number" id="total_roi" required step="0.01"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="flex items-center">
                                <label class="flex items-center">
                                    <input type="checkbox" id="is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="hideForm()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Save Plan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Plans Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Daily ROI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total ROI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="plansTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Plans will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    let plans = [];

    async function loadPlans() {
        try {
            const response = await fetch('/admin/api/plans');
            plans = await response.json();
            renderPlans();
        } catch (error) {
            console.error('Error loading plans:', error);
        }
    }

    function renderPlans() {
        const tbody = document.getElementById('plansTableBody');
        tbody.innerHTML = plans.map(plan => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">${plan.name}</td>
                <td class="px-6 py-4 whitespace-nowrap">${plan.minimum_amount} TRX</td>
                <td class="px-6 py-4 whitespace-nowrap">${plan.daily_roi}%</td>
                <td class="px-6 py-4 whitespace-nowrap">${plan.duration_days} days</td>
                <td class="px-6 py-4 whitespace-nowrap">${plan.total_roi}x</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${plan.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${plan.is_active ? 'Active' : 'Inactive'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button onclick="editPlan(${plan.id})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                    <button onclick="deletePlan(${plan.id})" class="text-red-600 hover:text-red-900">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    function showAddPlanForm() {
        document.getElementById('formTitle').textContent = 'Add New Plan';
        document.getElementById('planId').value = '';
        document.getElementById('planForm').classList.remove('hidden');
        resetForm();
    }

    function hideForm() {
        document.getElementById('planForm').classList.add('hidden');
        resetForm();
    }

    function resetForm() {
        document.getElementById('name').value = '';
        document.getElementById('minimum_amount').value = '';
        document.getElementById('daily_roi').value = '';
        document.getElementById('duration_days').value = '';
        document.getElementById('total_roi').value = '';
        document.getElementById('is_active').checked = true;
    }

    function editPlan(id) {
        const plan = plans.find(p => p.id === id);
        if (!plan) return;

        document.getElementById('formTitle').textContent = 'Edit Plan';
        document.getElementById('planId').value = plan.id;
        document.getElementById('name').value = plan.name;
        document.getElementById('minimum_amount').value = plan.minimum_amount;
        document.getElementById('daily_roi').value = plan.daily_roi;
        document.getElementById('duration_days').value = plan.duration_days;
        document.getElementById('total_roi').value = plan.total_roi;
        document.getElementById('is_active').checked = plan.is_active;
        document.getElementById('planForm').classList.remove('hidden');
    }

    async function handleSubmit(event) {
        event.preventDefault();
        const planId = document.getElementById('planId').value;
        const formData = {
            name: document.getElementById('name').value,
            minimum_amount: document.getElementById('minimum_amount').value,
            daily_roi: document.getElementById('daily_roi').value,
            duration_days: document.getElementById('duration_days').value,
            total_roi: document.getElementById('total_roi').value,
            is_active: document.getElementById('is_active').checked
        };

        try {
            const response = await fetch(`/admin/api/plans${planId ? `/${planId}` : ''}`, {
                method: planId ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            if (response.ok) {
                hideForm();
                loadPlans();
            } else {
                throw new Error('Failed to save plan');
            }
        } catch (error) {
            console.error('Error saving plan:', error);
            alert('Failed to save plan. Please try again.');
        }
    }

    async function deletePlan(id) {
        if (!confirm('Are you sure you want to delete this plan?')) return;

        try {
            const response = await fetch(`/admin/api/plans/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                loadPlans();
            } else {
                throw new Error('Failed to delete plan');
            }
        } catch (error) {
            console.error('Error deleting plan:', error);
            alert('Failed to delete plan. Please try again.');
        }
    }

    // Load plans when page loads
    document.addEventListener('DOMContentLoaded', loadPlans);
    </script>
</body>
</html>
