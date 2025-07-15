@extends('layouts.app')

@section('title', 'Manage Rules')

@section('content')
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0" id="form-title">Create Rule</h5>
        </div>
        <div class="card-body">
          <form id="rule-form">
            @csrf
            <input type="hidden" id="rule-id" value="">
            <div class="mb-3">
              <label class="form-label">Serial Number</label>
              <input type="text" id="serial_number" class="form-control-plaintext" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Rule Name <span class="text-danger">*</span></label>
              <input type="text" id="name" class="form-control" placeholder="Enter rule name" required>
              <div id="name-error" class="text-danger mt-1 d-none"></div>
            </div>
            <div class="d-flex justify-content-between">
              <button type="button" id="cancel-edit" class="btn btn-secondary d-none">Cancel</button>
              <button type="submit" class="btn btn-success">Save Rule</button>
            </div>
          </form>
          <div id="success-message" class="alert alert-success mt-3 d-none">
            ✅ Rule saved successfully!
          </div>
        </div>
      </div>

      <div class="card shadow">
        <div class="card-header bg-secondary text-white">
          <h5 class="mb-0">Rules List</h5>
        </div>
        <div class="card-body p-0">
          <table class="table mb-0">
            <thead>
              <tr>
                <th>Serial</th>
                <th>Name</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="rules-list">
              <!-- Dynamically filled -->
            </tbody>
          </table>
          <div id="list-empty" class="text-center p-3">No rules found.</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('rule-form');
  const nameInput = document.getElementById('name');
  const nameError = document.getElementById('name-error');
  const successMessage = document.getElementById('success-message');
  const serialInput = document.getElementById('serial_number');
  const ruleIdInput = document.getElementById('rule-id');
  const formTitle = document.getElementById('form-title');
  const cancelEditBtn = document.getElementById('cancel-edit');
  const rulesList = document.getElementById('rules-list');
  const listEmpty = document.getElementById('list-empty');

  let rules = [];

  fetchRules();

  form.onsubmit = e => {
    e.preventDefault();
    nameError.classList.add('d-none');
    successMessage.classList.add('d-none');

    const name = nameInput.value.trim();
    if (!name) return;

    const id = ruleIdInput.value;
    const url = id ? `/api/rules/${id}` : '/api/rules';
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
      },
      body: JSON.stringify({ name })
    })
    .then(async res => {
      const data = await res.json();
      if (res.ok) {
        successMessage.textContent = id ? '✅ Rule updated successfully!' : '✅ Rule created successfully!';
        successMessage.classList.remove('d-none');
        form.reset();
        ruleIdInput.value = '';
        formTitle.textContent = 'Create Rule';
        cancelEditBtn.classList.add('d-none');
        fetchRules();
      } else {
        nameError.textContent = data.errors?.name?.[0] || 'Something went wrong';
        nameError.classList.remove('d-none');
      }
    })
    .catch(() => {
      nameError.textContent = 'Network error. Please try again.';
      nameError.classList.remove('d-none');
    });
  };

  cancelEditBtn.onclick = () => {
    form.reset();
    ruleIdInput.value = '';
    formTitle.textContent = 'Create Rule';
    cancelEditBtn.classList.add('d-none');
    nameError.classList.add('d-none');
    successMessage.classList.add('d-none');
    fetchSerial();
  };

  function fetchRules() {
    fetch('/api/rules')
      .then(res => res.json())
      .then(data => {
        rules = data;
        renderRules();
        fetchSerial();
      })
      .catch(() => {
        serialInput.value = 'N/A';
        rulesList.innerHTML = '';
        listEmpty.style.display = 'block';
      });
  }

  function fetchSerial() {
    const max = rules.length ? Math.max(...rules.map(r => r.serial_number || 0)) : 0;
    serialInput.value = max + 1;
  }

  function renderRules() {
    if (rules.length === 0) {
      listEmpty.style.display = 'block';
      rulesList.innerHTML = '';
      return;
    }
    listEmpty.style.display = 'none';
    rulesList.innerHTML = rules.map(rule => `
      <tr>
        <td>${rule.serial_number}</td>
        <td>${rule.name}</td>
        <td>
          <button class="btn btn-sm btn-info btn-edit" data-id="${rule.id}">Edit</button>
          <button class="btn btn-sm btn-danger btn-delete" data-id="${rule.id}">Delete</button>
        </td>
      </tr>
    `).join('');

    document.querySelectorAll('.btn-edit').forEach(btn =>
      btn.onclick = () => startEdit(btn.getAttribute('data-id'))
    );

    document.querySelectorAll('.btn-delete').forEach(btn =>
      btn.onclick = () => deleteRule(btn.getAttribute('data-id'))
    );
  }

  function startEdit(id) {
    const rule = rules.find(r => r.id == id);
    if (!rule) return;

    ruleIdInput.value = rule.id;
    nameInput.value = rule.name;
    formTitle.textContent = 'Edit Rule';
    cancelEditBtn.classList.remove('d-none');
    nameError.classList.add('d-none');
    successMessage.classList.add('d-none');
    serialInput.value = rule.serial_number;
  }

  function deleteRule(id) {
    if (!confirm('Are you sure you want to delete this rule?')) return;

    fetch(`/api/rules/${id}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value }
    })
    .then(res => {
      if (res.ok) {
        fetchRules();
      } else {
        alert('Failed to delete the rule.');
      }
    })
    .catch(() => alert('Network error. Please try again.'));
  }
});
</script>
@endsection
