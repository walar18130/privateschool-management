@extends('layouts.app')

@section('title', 'Rule Management')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Rules</h5>
            <button class="btn btn-light btn-sm" onclick="resetForm()">+ New Rule</button>
        </div>
        <div class="card-body">
            <form id="rule-form" class="mb-4">
                @csrf
                <input type="hidden" id="rule_id">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <input type="text" id="serial_number" class="form-control-plaintext" readonly>
                    </div>
                    <div class="col-md-8">
                        <input type="text" id="name" class="form-control" placeholder="Enter rule name">
                        <small id="name-error" class="text-danger d-none"></small>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped" id="rule-table">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div id="success-msg" class="alert alert-success d-none">✅ Success!</div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('rule-form');
        const idInput = document.getElementById('rule_id');
        const nameInput = document.getElementById('name');
        const serialInput = document.getElementById('serial_number');
        const error = document.getElementById('name-error');
        const success = document.getElementById('success-msg');
        const tableBody = document.querySelector('#rule-table tbody');

        function fetchRules() {
            fetch('/api/rules')
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    const maxSerial = Math.max(0, ...data.map(r => r.serial_number || 0));
                    serialInput.value = maxSerial + 1;

                    data.forEach(rule => {
                        tableBody.innerHTML += `
                            <tr>
                                <td>${rule.serial_number}</td>
                                <td>${rule.name}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick='editRule(${JSON.stringify(rule)})'>Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick='deleteRule(${rule.id})'>Del</button>
                                </td>
                            </tr>
                        `;
                    });
                });
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            error.classList.add('d-none');
            success.classList.add('d-none');

            const id = idInput.value;
            const name = nameInput.value.trim();
            const url = id ? `/api/rules/${id}` : '/api/rules';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ name })
            })
            .then(res => res.json().then(data => ({ ok: res.ok, data })))
            .then(({ ok, data }) => {
                if (ok) {
                    success.classList.remove('d-none');
                    form.reset();
                    idInput.value = '';
                    fetchRules();
                } else {
                    error.textContent = data.errors?.name?.[0] || 'Error';
                    error.classList.remove('d-none');
                }
            });
        });

        window.editRule = function (rule) {
            idInput.value = rule.id;
            nameInput.value = rule.name;
            serialInput.value = rule.serial_number;
        };

        window.deleteRule = function (id) {
            if (confirm('Are you sure to delete?')) {
                fetch(`/api/rules/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                }).then(() => fetchRules());
            }
        };

        window.resetForm = function () {
            form.reset();
            idInput.value = '';
            fetchRules();
        };

        fetchRules();
    });
</script>
@endsection
