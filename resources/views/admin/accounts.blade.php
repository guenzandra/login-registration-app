{{-- resources/views/admin/accounts.blade.php --}}
@extends('admin.layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
  /* Dark Theme CSS Variables */
  :root {
    --primary: #8b5cf6;
    --primary-dark: #7c3aed;
    --primary-light: #a78bfa;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --info: #3b82f6;
    --bg-primary: #0f0f11;
    --bg-secondary: #1a1a1f;
    --bg-tertiary: #22222a;
    --bg-hover: #2a2a32;
    --text-primary: #f3f4f6;
    --text-secondary: #9ca3af;
    --text-muted: #6b7280;
    --border: #2a2a32;
    --border-light: #33333d;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
    --radius: 0.5rem;
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background: var(--bg-primary);
    color: var(--text-primary);
  }

  .accounts-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
  }

  .page-header {
    margin-bottom: 2rem;
  }

  .page-header h1 {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
  }

  .page-header p {
    font-size: 0.875rem;
    color: var(--text-secondary);
  }

  .toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
  }

  .search-filter-group {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    flex: 1;
  }

  .search-box {
    position: relative;
    flex: 1;
    min-width: 250px;
  }

  .search-box input {
    width: 100%;
    padding: 0.625rem 1rem 0.625rem 2.5rem;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 0.875rem;
    transition: all 0.3s;
    background: var(--bg-tertiary);
    color: var(--text-primary);
  }

  .search-box input::placeholder {
    color: var(--text-muted);
  }

  .search-box input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
  }

  .search-box i {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.875rem;
  }

  .filter-select {
    padding: 0.625rem 1rem;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 0.875rem;
    background: var(--bg-tertiary);
    color: var(--text-primary);
    cursor: pointer;
    transition: all 0.3s;
  }

  .filter-select:focus {
    outline: none;
    border-color: var(--primary);
  }

  .btn-add {
    background: var(--primary);
    color: white;
    border: none;
    padding: 0.625rem 1.5rem;
    border-radius: var(--radius);
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
  }

  .btn-add:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
  }

  .table-card {
    background: var(--bg-secondary);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow-x: auto;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border);
  }

  .accounts-table {
    width: 100%;
    border-collapse: collapse;
  }

  .accounts-table thead {
    background: var(--bg-tertiary);
    border-bottom: 2px solid var(--border);
  }

  .accounts-table th {
    padding: 1rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-secondary);
  }

  .accounts-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border);
    font-size: 0.875rem;
    color: var(--text-primary);
  }

  .accounts-table tbody tr {
    cursor: pointer;
    transition: background 0.3s;
  }

  .accounts-table tbody tr:hover {
    background: var(--bg-hover);
  }

  /* Table Loading Overlay */
  .table-loading {
    position: relative;
  }

  .table-loading .loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: var(--radius);
  }

  .table-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid var(--border);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }

  .user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
  }

  .user-details {
    display: flex;
    flex-direction: column;
  }

  .user-name {
    font-weight: 600;
    color: var(--text-primary);
  }

  .user-email {
    font-size: 0.75rem;
    color: var(--text-secondary);
  }

  .badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
  }

  .badge-admin {
    background: rgba(139, 92, 246, 0.2);
    color: #a78bfa;
  }

  .badge-mod {
    background: rgba(245, 158, 11, 0.2);
    color: #fbbf24;
  }

  .badge-user {
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa;
  }

  .status {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
  }

  .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
  }

  .status-active {
    color: var(--success);
  }

  .status-active .status-dot {
    background: var(--success);
    box-shadow: 0 0 5px var(--success);
  }

  .status-inactive {
    color: var(--text-muted);
  }

  .status-inactive .status-dot {
    background: var(--text-muted);
  }

  .status-banned {
    color: var(--danger);
  }

  .status-banned .status-dot {
    background: var(--danger);
    box-shadow: 0 0 5px var(--danger);
  }

  .pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    flex-wrap: wrap;
    gap: 1rem;
    border-top: 1px solid var(--border);
  }

  .pagination button {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border);
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border-radius: var(--radius);
    cursor: pointer;
    transition: all 0.3s;
    font-size: 0.875rem;
  }

  .pagination button:hover:not(:disabled) {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
  }

  .pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .page-info {
    font-size: 0.875rem;
    color: var(--text-secondary);
  }

  .modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(4px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
  }

  .modal.show {
    display: flex;
    animation: fadeIn 0.3s;
  }

  .modal-content {
    background: var(--bg-secondary);
    border-radius: var(--radius);
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s;
    border: 1px solid var(--border);
  }

  .modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .modal-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
  }

  .modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-secondary);
    transition: color 0.3s;
  }

  .modal-close:hover {
    color: var(--text-primary);
  }

  .modal-body {
    padding: 1.5rem;
  }

  .modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
  }

  .form-group {
    margin-bottom: 1rem;
  }

  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
  }

  .form-group input,
  .form-group select {
    width: 100%;
    padding: 0.625rem;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 0.875rem;
    transition: all 0.3s;
    background: var(--bg-tertiary);
    color: var(--text-primary);
  }

  .form-group input::placeholder {
    color: var(--text-muted);
  }

  .form-group input:focus,
  .form-group select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
  }

  .password-wrapper {
    position: relative;
  }

  .password-wrapper input {
    padding-right: 2.5rem;
  }

  .password-toggle {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 1rem;
    transition: color 0.3s;
  }

  .password-toggle:hover {
    color: var(--text-primary);
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  .btn {
    padding: 0.625rem 1.25rem;
    border: none;
    border-radius: var(--radius);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
  }

  .btn-primary {
    background: var(--primary);
    color: white;
  }

  .btn-primary:hover {
    background: var(--primary-dark);
  }

  .btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .btn-secondary {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border: 1px solid var(--border);
  }

  .btn-secondary:hover {
    background: var(--bg-hover);
  }

  .btn-danger {
    background: var(--danger);
    color: white;
  }

  .btn-danger:hover {
    background: #dc2626;
  }

  .toast-container {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 10000;
  }

  .toast {
    background: var(--bg-secondary);
    border-radius: var(--radius);
    padding: 1rem;
    margin-bottom: 0.5rem;
    box-shadow: var(--shadow-lg);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 300px;
    animation: slideInRight 0.3s;
    border: 1px solid var(--border);
    border-left-width: 4px;
  }

  .toast.success {
    border-left-color: var(--success);
  }

  .toast.error {
    border-left-color: var(--danger);
  }

  .toast.info {
    border-left-color: var(--info);
  }

  .toast.warning {
    border-left-color: var(--warning);
  }

  .toast-icon {
    font-size: 1.25rem;
  }

  .toast-content {
    flex: 1;
  }

  .toast-title {
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
  }

  .toast-message {
    font-size: 0.813rem;
    color: var(--text-secondary);
  }

  .toast-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: var(--text-secondary);
    transition: color 0.3s;
  }

  .toast-close:hover {
    color: var(--text-primary);
  }

  .view-field {
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .view-field:last-child {
    border-bottom: none;
  }

  .view-label {
    font-weight: 600;
    color: var(--text-secondary);
  }

  .view-value {
    color: var(--text-primary);
  }

  .action-buttons {
    display: flex;
    gap: 0.75rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
  }

  .action-buttons .btn {
    flex: 1;
    justify-content: center;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }

    to {
      opacity: 1;
    }
  }

  @keyframes slideUp {
    from {
      transform: translateY(50px);
      opacity: 0;
    }

    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  @keyframes slideInRight {
    from {
      transform: translateX(100%);
      opacity: 0;
    }

    to {
      transform: translateX(0);
      opacity: 1;
    }
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

  ::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }

  ::-webkit-scrollbar-track {
    background: var(--bg-tertiary);
    border-radius: 4px;
  }

  ::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 4px;
  }

  ::-webkit-scrollbar-thumb:hover {
    background: var(--border-light);
  }

  @media (max-width: 768px) {
    .accounts-container {
      padding: 1rem;
    }

    .toolbar {
      flex-direction: column;
    }

    .search-filter-group {
      width: 100%;
    }

    .search-box {
      min-width: auto;
    }

    .form-row {
      grid-template-columns: 1fr;
    }

    .accounts-table th,
    .accounts-table td {
      padding: 0.75rem;
    }

    .user-info {
      flex-direction: column;
      align-items: flex-start;
    }

    .toast {
      min-width: auto;
      width: calc(100vw - 2rem);
    }

    .action-buttons {
      flex-direction: column;
    }
  }

  @media (max-width: 640px) {
    .accounts-table {
      font-size: 0.75rem;
    }

    .accounts-table th,
    .accounts-table td {
      padding: 0.5rem;
    }

    .badge,
    .status {
      font-size: 0.688rem;
    }
  }
</style>

<div class="accounts-container">
  <div class="page-header">
    <h1><i class="fas fa-users" style="margin-right: 0.75rem; color: var(--primary);"></i> Account Management</h1>
    <p>Manage user accounts, roles, and permissions here. Click on any row to view details.</p>
  </div>

  <div class="toolbar">
    <div class="search-filter-group">
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Search by name or email...">
      </div>
      <select id="roleFilter" class="filter-select">
        <option value="">All Roles</option>
        <option value="admin">Admin</option>
        <option value="mod">Moderator</option>
        <option value="user">User</option>
      </select>
      <select id="statusFilter" class="filter-select">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
        <option value="banned">Banned</option>
      </select>
    </div>
    <button class="btn-add" onclick="openAddModal()">
      <i class="fas fa-plus"></i> Add User
    </button>
  </div>

  <div class="table-card" id="tableCard">
    <div style="position: relative;">
      <div id="tableLoadingOverlay" style="display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 10; border-radius: var(--radius);">
        <div class="table-spinner"></div>
      </div>
      <table class="accounts-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          <tr>
            <td colspan="4" style="text-align: center; padding: 3rem;">
              <div class="table-spinner"></div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="pagination">
      <button class="prev-page" onclick="changePage(-1)"><i class="fas fa-chevron-left"></i> Previous</button>
      <span class="page-info" id="pageInfo">Page 1 of 1</span>
      <button class="next-page" onclick="changePage(1)">Next <i class="fas fa-chevron-right"></i></button>
    </div>
  </div>
</div>

<!-- Add User Modal -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3><i class="fas fa-user-plus" style="margin-right: 0.5rem; color: var(--primary);"></i> Add New User</h3>
      <button class="modal-close" onclick="closeModal('addModal')">&times;</button>
    </div>
    <form id="addUserForm">
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group">
            <label>First Name</label>
            <input type="text" id="addFirstName" placeholder="Enter first name" required>
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" id="addLastName" placeholder="Enter last name" required>
          </div>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" id="addEmail" placeholder="user@example.com" required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Role</label>
            <select id="addRole">
              <option value="user">User</option>
              <option value="mod">Moderator</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select id="addStatus">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="banned">Banned</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Password</label>
          <div class="password-wrapper">
            <input type="password" id="addPassword" placeholder="Min. 8 characters with 1 uppercase, 1 lowercase, 1 number" required>
            <button type="button" class="password-toggle" onclick="togglePassword('addPassword', this)">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
        <div class="form-group">
          <label>Confirm Password</label>
          <div class="password-wrapper">
            <input type="password" id="addConfirmPassword" placeholder="Confirm your password" required>
            <button type="button" class="password-toggle" onclick="togglePassword('addConfirmPassword', this)">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
        <div id="passwordStrength" style="font-size: 0.75rem; margin-top: -0.5rem; margin-bottom: 0.5rem;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Cancel</button>
        <button type="submit" class="btn btn-primary" id="submitAddBtn">Create User</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3><i class="fas fa-edit" style="margin-right: 0.5rem; color: var(--primary);"></i> Edit User</h3>
      <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
    </div>
    <form id="editUserForm">
      <input type="hidden" id="editUserId">
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group">
            <label>First Name</label>
            <input type="text" id="editFirstName" required>
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" id="editLastName" required>
          </div>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" id="editEmail" required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Role</label>
            <select id="editRole">
              <option value="user">User</option>
              <option value="mod">Moderator</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select id="editStatus">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="banned">Banned</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- View User Modal -->
<div id="viewModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3><i class="fas fa-user-circle" style="margin-right: 0.5rem; color: var(--primary);"></i> User Details</h3>
      <button class="modal-close" onclick="closeModal('viewModal')">&times;</button>
    </div>
    <div class="modal-body">
      <div id="viewContent"></div>
      <div class="action-buttons">
        <button class="btn btn-primary" id="viewEditBtn">
          <i class="fas fa-edit"></i> Edit User
        </button>
        <button class="btn btn-danger" id="viewDeleteBtn">
          <i class="fas fa-trash-alt"></i> Delete User
        </button>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('viewModal')">Close</button>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3><i class="fas fa-trash-alt" style="margin-right: 0.5rem; color: var(--danger);"></i> Delete User</h3>
      <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
    </div>
    <div class="modal-body">
      <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
      <p style="color: var(--danger); font-size: 0.875rem; margin-top: 0.5rem;">This action cannot be undone.</p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
      <button class="btn btn-danger" id="confirmDeleteBtn"><i class="fas fa-trash"></i> Delete</button>
    </div>
  </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script>
  let currentPage = 1;
  let totalPages = 1;
  let isLoading = false;
  let currentViewUserId = null;
  let deleteUserId = null;

  // Initialize
  document.addEventListener('DOMContentLoaded', () => {
    loadUsers();
    setupEventListeners();
  });

  function setupEventListeners() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');

    let debounceTimer;
    searchInput.addEventListener('input', () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        currentPage = 1;
        loadUsers();
      }, 300);
    });

    roleFilter.addEventListener('change', () => {
      currentPage = 1;
      loadUsers();
    });

    statusFilter.addEventListener('change', () => {
      currentPage = 1;
      loadUsers();
    });
  }

  function showTableLoading() {
    const overlay = document.getElementById('tableLoadingOverlay');
    if (overlay) {
      overlay.style.display = 'flex';
    }
  }

  function hideTableLoading() {
    const overlay = document.getElementById('tableLoadingOverlay');
    if (overlay) {
      overlay.style.display = 'none';
    }
  }

  async function loadUsers() {
    if (isLoading) return;

    isLoading = true;
    showTableLoading();

    try {
      const search = document.getElementById('searchInput').value;
      const role = document.getElementById('roleFilter').value;
      const status = document.getElementById('statusFilter').value;

      const response = await fetch(`/admin/accounts/data?page=${currentPage}&search=${encodeURIComponent(search)}&role=${role}&status=${status}&per_page=5`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      });

      if (!response.ok) throw new Error('Failed to load users');

      const result = await response.json();

      if (result.success) {
        renderUsers(result.data);
        totalPages = result.total_pages;
        updatePagination();
      } else {
        showToast('Error', result.message, 'error');
      }
    } catch (error) {
      console.error('Error loading users:', error);
      showToast('Error', 'Failed to load users', 'error');
    } finally {
      isLoading = false;
      hideTableLoading();
    }
  }

  function renderUsers(users) {
    const tbody = document.getElementById('tableBody');

    if (!users || users.length === 0) {
      tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 3rem; color: var(--text-secondary);"><i class="fas fa-user-slash" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>No users found</td></tr>';
      return;
    }

    tbody.innerHTML = users.map(user => `
      <tr onclick="viewUser(${user.id})" style="cursor: pointer;">
        <td>
          <div class="user-info">
            <div class="user-avatar">${getInitials(user.first_name, user.last_name)}</div>
            <div class="user-details">
              <div class="user-name">${escapeHtml(user.first_name)} ${escapeHtml(user.last_name)}</div>
              <div class="user-email">${escapeHtml(user.email)}</div>
            </div>
          </div>
        </td>
        <td>${escapeHtml(user.email)}</td>
        <td>${getRoleBadge(user.role)}</td>
        <td>${getStatusBadge(user.status)}</td>
      </tr>
    `).join('');
  }

  function validateStrongPassword(password) {
    const minLength = 8;
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumbers = /\d/.test(password);

    if (password.length < minLength) {
      return {
        valid: false,
        message: 'Password must be at least 8 characters long'
      };
    }
    if (!hasUpperCase) {
      return {
        valid: false,
        message: 'Password must contain at least one uppercase letter'
      };
    }
    if (!hasLowerCase) {
      return {
        valid: false,
        message: 'Password must contain at least one lowercase letter'
      };
    }
    if (!hasNumbers) {
      return {
        valid: false,
        message: 'Password must contain at least one number'
      };
    }

    return {
      valid: true,
      message: 'Strong password!'
    };
  }

  function updatePasswordStrength() {
    const password = document.getElementById('addPassword').value;
    const strengthDiv = document.getElementById('passwordStrength');

    if (!password) {
      strengthDiv.innerHTML = '';
      strengthDiv.style.color = '';
      return;
    }

    const validation = validateStrongPassword(password);

    if (validation.valid) {
      strengthDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + validation.message;
      strengthDiv.style.color = 'var(--success)';
    } else {
      strengthDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + validation.message;
      strengthDiv.style.color = 'var(--warning)';
    }
  }

  function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }

  async function createUser(userData) {
    showTableLoading();

    try {
      const response = await fetch('/admin/accounts', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: JSON.stringify(userData)
      });

      const result = await response.json();

      if (result.success) {
        showToast('Success', result.message, 'success');
        closeModal('addModal');
        await loadUsers();
        return true;
      } else {
        if (result.errors) {
          const errors = Object.values(result.errors).flat().join('\n');
          showToast('Validation Error', errors, 'error');
        } else {
          showToast('Error', result.message, 'error');
        }
        return false;
      }
    } catch (error) {
      console.error('Error creating user:', error);
      showToast('Error', 'Failed to create user', 'error');
      return false;
    } finally {
      hideTableLoading();
    }
  }

  async function updateUser(id, userData) {
    showTableLoading();

    try {
      const response = await fetch(`/admin/accounts/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: JSON.stringify(userData)
      });

      const result = await response.json();

      if (result.success) {
        showToast('Success', result.message, 'success');
        closeModal('editModal');
        closeModal('viewModal');
        await loadUsers();
        return true;
      } else {
        if (result.errors) {
          const errors = Object.values(result.errors).flat().join('\n');
          showToast('Validation Error', errors, 'error');
        } else {
          showToast('Error', result.message, 'error');
        }
        return false;
      }
    } catch (error) {
      console.error('Error updating user:', error);
      showToast('Error', 'Failed to update user', 'error');
      return false;
    } finally {
      hideTableLoading();
    }
  }

  async function deleteUser(id) {
    showTableLoading();

    try {
      const response = await fetch(`/admin/accounts/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        }
      });

      const result = await response.json();

      if (result.success) {
        showToast('Success', result.message, 'success');
        closeModal('deleteModal');
        closeModal('viewModal');
        await loadUsers();
        return true;
      } else {
        showToast('Error', result.message, 'error');
        return false;
      }
    } catch (error) {
      console.error('Error deleting user:', error);
      showToast('Error', 'Failed to delete user', 'error');
      return false;
    } finally {
      hideTableLoading();
    }
  }

  // Add password strength listener
  document.getElementById('addPassword')?.addEventListener('input', updatePasswordStrength);

  document.getElementById('addUserForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const password = document.getElementById('addPassword').value;
    const confirmPassword = document.getElementById('addConfirmPassword').value;

    const passwordValidation = validateStrongPassword(password);
    if (!passwordValidation.valid) {
      showToast('Validation Error', passwordValidation.message, 'error');
      return;
    }

    if (password !== confirmPassword) {
      showToast('Validation Error', 'Passwords do not match', 'error');
      return;
    }

    const userData = {
      first_name: document.getElementById('addFirstName').value.trim(),
      last_name: document.getElementById('addLastName').value.trim(),
      email: document.getElementById('addEmail').value.trim(),
      password: password,
      role: document.getElementById('addRole').value,
      status: document.getElementById('addStatus').value
    };

    const submitBtn = document.getElementById('submitAddBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';

    await createUser(userData);

    submitBtn.disabled = false;
    submitBtn.innerHTML = 'Create User';
  });

  document.getElementById('editUserForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const id = parseInt(document.getElementById('editUserId').value);
    const userData = {
      first_name: document.getElementById('editFirstName').value.trim(),
      last_name: document.getElementById('editLastName').value.trim(),
      email: document.getElementById('editEmail').value.trim(),
      role: document.getElementById('editRole').value,
      status: document.getElementById('editStatus').value
    };

    await updateUser(id, userData);
  });

  document.getElementById('confirmDeleteBtn')?.addEventListener('click', async () => {
    if (deleteUserId) {
      await deleteUser(deleteUserId);
      deleteUserId = null;
    }
  });

  async function viewUser(id) {
    currentViewUserId = id;
    showTableLoading();

    try {
      const response = await fetch(`/admin/accounts/data?page=1&per_page=1000`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      });

      const result = await response.json();
      const user = result.data.find(u => u.id === id);

      if (user) {
        const viewContent = document.getElementById('viewContent');
        viewContent.innerHTML = `
          <div class="view-field">
            <span class="view-label"><i class="fas fa-user"></i> Full Name:</span>
            <span class="view-value">${escapeHtml(user.first_name)} ${escapeHtml(user.last_name)}</span>
          </div>
          <div class="view-field">
            <span class="view-label"><i class="fas fa-envelope"></i> Email:</span>
            <span class="view-value">${escapeHtml(user.email)}</span>
          </div>
          <div class="view-field">
            <span class="view-label"><i class="fas fa-tag"></i> Role:</span>
            <span class="view-value">${getRoleBadge(user.role)}</span>
          </div>
          <div class="view-field">
            <span class="view-label"><i class="fas fa-circle"></i> Status:</span>
            <span class="view-value">${getStatusBadge(user.status)}</span>
          </div>
          <div class="view-field">
            <span class="view-label"><i class="fas fa-calendar-alt"></i> Joined:</span>
            <span class="view-value">${formatDate(user.created_at)}</span>
          </div>
          <div class="view-field">
            <span class="view-label"><i class="fas fa-id-card"></i> User ID:</span>
            <span class="view-value">#${user.id}</span>
          </div>
        `;

        // Setup edit button
        const editBtn = document.getElementById('viewEditBtn');
        editBtn.onclick = () => {
          closeModal('viewModal');
          openEditModal(id);
        };

        // Setup delete button
        const deleteBtn = document.getElementById('viewDeleteBtn');
        deleteBtn.onclick = () => {
          closeModal('viewModal');
          openDeleteModal(id);
        };

        openModal('viewModal');
      }
    } catch (error) {
      console.error('Error viewing user:', error);
      showToast('Error', 'Failed to load user details', 'error');
    } finally {
      hideTableLoading();
    }
  }

  function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
      month: 'short',
      day: '2-digit',
      year: 'numeric'
    });
  }

  function getInitials(first, last) {
    return ((first || 'U')[0] + (last || '')[0]).toUpperCase();
  }

  function getRoleBadge(role) {
    const badges = {
      admin: '<span class="badge badge-admin"><i class="fas fa-crown" style="margin-right: 0.25rem;"></i> Admin</span>',
      mod: '<span class="badge badge-mod"><i class="fas fa-shield-alt" style="margin-right: 0.25rem;"></i> Moderator</span>',
      user: '<span class="badge badge-user"><i class="fas fa-user" style="margin-right: 0.25rem;"></i> User</span>'
    };
    return badges[role] || badges.user;
  }

  function getStatusBadge(status) {
    const statuses = {
      active: '<span class="status status-active"><i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.25rem;"></i> Active</span>',
      inactive: '<span class="status status-inactive"><i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.25rem;"></i> Inactive</span>',
      banned: '<span class="status status-banned"><i class="fas fa-ban" style="margin-right: 0.25rem;"></i> Banned</span>'
    };
    return statuses[status] || statuses.active;
  }

  function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }

  function showToast(title, message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;

    const icons = {
      success: '<i class="fas fa-check-circle" style="color: var(--success);"></i>',
      error: '<i class="fas fa-exclamation-circle" style="color: var(--danger);"></i>',
      warning: '<i class="fas fa-exclamation-triangle" style="color: var(--warning);"></i>',
      info: '<i class="fas fa-info-circle" style="color: var(--info);"></i>'
    };

    toast.innerHTML = `
      <div class="toast-icon">${icons[type] || icons.success}</div>
      <div class="toast-content">
        <div class="toast-title">${escapeHtml(title)}</div>
        <div class="toast-message">${escapeHtml(message)}</div>
      </div>
      <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
      if (toast.parentElement) toast.remove();
    }, 5000);
  }

  function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.remove('show');
      document.body.style.overflow = '';
    }
  }

  function changePage(direction) {
    const newPage = currentPage + direction;
    if (newPage >= 1 && newPage <= totalPages) {
      currentPage = newPage;
      loadUsers();
    }
  }

  function updatePagination() {
    const pageInfo = document.getElementById('pageInfo');
    const prevBtn = document.querySelector('.prev-page');
    const nextBtn = document.querySelector('.next-page');

    pageInfo.textContent = `Page ${currentPage} of ${totalPages || 1}`;
    if (prevBtn) prevBtn.disabled = currentPage === 1;
    if (nextBtn) nextBtn.disabled = currentPage === totalPages || totalPages === 0;
  }

  function openAddModal() {
    document.getElementById('addUserForm').reset();
    document.getElementById('passwordStrength').innerHTML = '';
    openModal('addModal');
  }

  async function openEditModal(id) {
    showTableLoading();

    try {
      const response = await fetch(`/admin/accounts/data?page=1&per_page=1000`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      });

      const result = await response.json();
      const user = result.data.find(u => u.id === id);

      if (user) {
        document.getElementById('editUserId').value = user.id;
        document.getElementById('editFirstName').value = user.first_name;
        document.getElementById('editLastName').value = user.last_name;
        document.getElementById('editEmail').value = user.email;
        document.getElementById('editRole').value = user.role;
        document.getElementById('editStatus').value = user.status;
        openModal('editModal');
      }
    } catch (error) {
      console.error('Error loading user for edit:', error);
      showToast('Error', 'Failed to load user data', 'error');
    } finally {
      hideTableLoading();
    }
  }

  function openDeleteModal(id) {
    deleteUserId = id;
    openModal('deleteModal');
  }

  window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
      closeModal(event.target.id);
    }
  };
</script>

@push('scripts')
<script>
  if (!document.querySelector('meta[name="csrf-token"]')) {
    const meta = document.createElement('meta');
    meta.name = 'csrf-token';
    meta.content = '{{ csrf_token() }}';
    document.head.appendChild(meta);
  }
</script>
@endpush
@endsection