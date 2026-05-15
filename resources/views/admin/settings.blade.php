<!-- resources/views/admin/accounts.blade.php -->
@extends('admin.layout')
@section('content')
<style>
  /* Dark Theme CSS */
  :root {
    --primary: #8b5cf6;
    --primary-dark: #7c3aed;
    --primary-light: #a78bfa;
    --bg-primary: #0f0f11;
    --bg-secondary: #1a1a1f;
    --bg-tertiary: #22222a;
    --bg-hover: #2a2a32;
    --text-primary: #f3f4f6;
    --text-secondary: #9ca3af;
    --text-muted: #6b7280;
    --border: #2a2a32;
    --border-light: #33333d;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
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
    background: var(--bg-primary);
    color: var(--text-primary);
  }

  /* Main Container */
  .settings-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
  }

  /* Header Section */
  .settings-header {
    margin-bottom: 2rem;
    text-align: center;
  }

  .settings-header h1 {
    font-size: 2rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin-bottom: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
  }

  .settings-header p {
    font-size: 0.875rem;
    color: var(--text-secondary);
  }

  /* Settings Card */
  .settings-card {
    background: var(--bg-secondary);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
  }

  /* Profile Section */
  .profile-section {
    padding: 2rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
  }

  .profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 600;
    color: white;
    box-shadow: 0 0 20px rgba(139, 92, 246, 0.3);
  }

  .profile-info h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
  }

  .profile-info p {
    font-size: 0.875rem;
    color: var(--text-secondary);
  }

  .profile-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: rgba(139, 92, 246, 0.2);
    color: var(--primary-light);
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 0.5rem;
  }

  /* Update Info Section */
  .update-info {
    padding: 2rem;
  }

  .section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .section-title i {
    color: var(--primary);
  }

  /* Form Styles */
  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
  }

  .form-group label i {
    margin-right: 0.5rem;
    color: var(--primary);
    width: 18px;
  }

  .form-group input {
    width: 100%;
    padding: 0.75rem 1rem;
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 0.875rem;
    color: var(--text-primary);
    transition: all 0.3s ease;
  }

  .form-group input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
  }

  .form-group input::placeholder {
    color: var(--text-muted);
  }

  /* Password Hint */
  .password-hint {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .password-hint i {
    font-size: 0.75rem;
  }

  /* Button Styles */
  .button-group {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    flex-wrap: wrap;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.5rem;
    border-radius: var(--radius);
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    text-decoration: none;
  }

  .btn-primary {
    background: var(--primary);
    color: white;
  }

  .btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
  }

  .btn-secondary {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border: 1px solid var(--border);
  }

  .btn-secondary:hover {
    background: var(--bg-hover);
    transform: translateY(-1px);
  }

  .btn-danger {
    background: transparent;
    color: var(--danger);
    border: 1px solid var(--danger);
  }

  .btn-danger:hover {
    background: rgba(239, 68, 68, 0.1);
    transform: translateY(-1px);
  }

  /* Edit Mode Toggle */
  .edit-mode-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(139, 92, 246, 0.1);
    border-radius: var(--radius);
    font-size: 0.75rem;
    color: var(--primary-light);
    margin-bottom: 1rem;
  }

  /* Success Message */
  .success-message {
    display: none;
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: var(--radius);
    padding: 1rem;
    margin-bottom: 1.5rem;
    color: var(--success);
    font-size: 0.875rem;
    align-items: center;
    gap: 0.75rem;
  }

  .success-message.show {
    display: flex;
    animation: slideDown 0.3s ease;
  }

  /* Loading Spinner */
  .loading-spinner {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
  }

  .loading-spinner.show {
    display: flex;
  }

  .spinner {
    width: 50px;
    height: 50px;
    border: 3px solid var(--border);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }

  /* Animations */
  @keyframes slideDown {
    from {
      transform: translateY(-20px);
      opacity: 0;
    }

    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

  /* Readonly/Disabled State */
  input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background: var(--bg-tertiary);
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .settings-container {
      padding: 1rem;
    }

    .profile-section {
      flex-direction: column;
      text-align: center;
    }

    .button-group {
      flex-direction: column;
    }

    .btn {
      width: 100%;
      justify-content: center;
    }
  }

  /* Scrollbar */
  ::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }

  ::-webkit-scrollbar-track {
    background: var(--bg-tertiary);
  }

  ::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 4px;
  }

  ::-webkit-scrollbar-thumb:hover {
    background: var(--border-light);
  }
</style>

<div class="settings-container">
  <!-- Header -->
  <div class="settings-header">
    <h1>
      <i class="fas fa-cog"></i>
      Settings
    </h1>
    <p>Manage your account settings and preferences here.</p>
  </div>

  <!-- Settings Card -->
  <div class="settings-card">
    <!-- Profile Section -->
    <div class="profile-section">
      <div class="profile-avatar" id="avatarDisplay">
        JD
      </div>
      <div class="profile-info">
        <h3 id="displayFullName">John Doe</h3>
        <p id="displayEmail">john.doe@example.com</p>
        <span class="profile-badge">
          <i class="fas fa-shield-alt"></i> Administrator
        </span>
      </div>
    </div>

    <!-- Update Info Section -->
    <div class="update-info">
      <div class="section-title">
        <i class="fas fa-user-edit"></i>
        Account Information
      </div>

      <!-- Edit Mode Indicator -->
      <div class="edit-mode-indicator" id="editModeIndicator" style="display: none;">
        <i class="fas fa-pen"></i>
        Edit Mode Active
      </div>

      <!-- Success Message -->
      <div class="success-message" id="successMessage">
        <i class="fas fa-check-circle"></i>
        <span>Your changes have been saved successfully!</span>
      </div>

      <!-- Edit Form -->
      <form action="#" method="POST" id="settingsForm" class="space-y-4">
        @csrf

        <div class="form-group">
          <label for="full_name">
            <i class="fas fa-user"></i>
            Full Name
          </label>
          <input type="text"
            name="full_name"
            id="full_name"
            class="form-input"
            placeholder="Enter your full name"
            value="John Doe"
            disabled>
        </div>

        <div class="form-group">
          <label for="email">
            <i class="fas fa-envelope"></i>
            Email Address
          </label>
          <input type="email"
            name="email"
            id="email"
            class="form-input"
            placeholder="Enter your email"
            value="john.doe@example.com"
            disabled>
        </div>

        <div class="form-group">
          <label for="password">
            <i class="fas fa-lock"></i>
            Password
          </label>
          <input type="password"
            name="password"
            id="password"
            class="form-input"
            placeholder="Enter new password (leave blank to keep current)"
            disabled>
          <div class="password-hint">
            <i class="fas fa-info-circle"></i>
            Password must be at least 8 characters long
          </div>
        </div>

        <div class="button-group">
          <button type="button" class="btn btn-primary" id="editBtn">
            <i class="fas fa-edit"></i>
            Edit Profile
          </button>
          <button type="submit" class="btn btn-primary" id="saveBtn" style="display: none;">
            <i class="fas fa-save"></i>
            Save Changes
          </button>
          <button type="button" class="btn btn-secondary" id="cancelBtn" style="display: none;">
            <i class="fas fa-times"></i>
            Cancel
          </button>
          <button type="button" class="btn btn-danger" id="resetBtn" style="display: none;">
            <i class="fas fa-undo-alt"></i>
            Reset
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Loading Spinner -->
<div class="loading-spinner" id="loadingSpinner">
  <div class="spinner"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>
<script>
  // Original data storage
  let originalData = {
    full_name: 'John Doe',
    email: 'john.doe@example.com'
  };

  let currentData = {
    ...originalData
  };
  let isEditMode = false;

  // DOM Elements
  const editBtn = document.getElementById('editBtn');
  const saveBtn = document.getElementById('saveBtn');
  const cancelBtn = document.getElementById('cancelBtn');
  const resetBtn = document.getElementById('resetBtn');
  const fullNameInput = document.getElementById('full_name');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const settingsForm = document.getElementById('settingsForm');
  const editModeIndicator = document.getElementById('editModeIndicator');
  const successMessage = document.getElementById('successMessage');
  const loadingSpinner = document.getElementById('loadingSpinner');
  const displayFullName = document.getElementById('displayFullName');
  const displayEmail = document.getElementById('displayEmail');
  const avatarDisplay = document.getElementById('avatarDisplay');

  // Helper Functions
  function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
  }

  function updateDisplay() {
    displayFullName.textContent = currentData.full_name;
    displayEmail.textContent = currentData.email;
    avatarDisplay.textContent = getInitials(currentData.full_name);
  }

  function toggleEditMode(enable) {
    isEditMode = enable;

    if (enable) {
      fullNameInput.disabled = false;
      emailInput.disabled = false;
      passwordInput.disabled = false;
      editBtn.style.display = 'none';
      saveBtn.style.display = 'inline-flex';
      cancelBtn.style.display = 'inline-flex';
      resetBtn.style.display = 'inline-flex';
      editModeIndicator.style.display = 'inline-flex';

      // Set focus on first field
      fullNameInput.focus();
    } else {
      fullNameInput.disabled = true;
      emailInput.disabled = true;
      passwordInput.disabled = true;
      editBtn.style.display = 'inline-flex';
      saveBtn.style.display = 'none';
      cancelBtn.style.display = 'none';
      resetBtn.style.display = 'none';
      editModeIndicator.style.display = 'none';
    }
  }

  function showLoading() {
    loadingSpinner.classList.add('show');
  }

  function hideLoading() {
    loadingSpinner.classList.remove('show');
  }

  function showSuccessMessage() {
    successMessage.classList.add('show');
    setTimeout(() => {
      successMessage.classList.remove('show');
    }, 3000);
  }

  function resetForm() {
    fullNameInput.value = currentData.full_name;
    emailInput.value = currentData.email;
    passwordInput.value = '';
  }

  function validateEmail(email) {
    const re = /^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/;
    return re.test(email);
  }

  function validateForm() {
    const fullName = fullNameInput.value.trim();
    const email = emailInput.value.trim();
    const password = passwordInput.value;

    if (!fullName) {
      showToast('Validation Error', 'Full name is required.', 'error');
      return false;
    }

    if (!email) {
      showToast('Validation Error', 'Email address is required.', 'error');
      return false;
    }

    if (!validateEmail(email)) {
      showToast('Validation Error', 'Please enter a valid email address.', 'error');
      return false;
    }

    if (password && password.length < 8) {
      showToast('Validation Error', 'Password must be at least 8 characters long.', 'error');
      return false;
    }

    return true;
  }

  // Toast Notification
  function showToast(title, message, type = 'success') {
    // Check if toast container exists, if not create it
    let container = document.querySelector('.toast-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'toast-container';
      document.body.appendChild(container);

      // Add styles for toast container
      const style = document.createElement('style');
      style.textContent = `
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
        .toast.success { border-left-color: var(--success); }
        .toast.error { border-left-color: var(--danger); }
        .toast.warning { border-left-color: var(--warning); }
        .toast.info { border-left-color: var(--info); }
        .toast-icon { font-size: 1.25rem; }
        .toast-content { flex: 1; }
        .toast-title { font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem; color: var(--text-primary); }
        .toast-message { font-size: 0.813rem; color: var(--text-secondary); }
        .toast-close { background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--text-secondary); }
        @keyframes slideInRight {
          from { transform: translateX(100%); opacity: 0; }
          to { transform: translateX(0); opacity: 1; }
        }
      `;
      document.head.appendChild(style);
    }

    container = document.querySelector('.toast-container');
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
        <div class="toast-title">${title}</div>
        <div class="toast-message">${message}</div>
      </div>
      <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
      if (toast.parentElement) toast.remove();
    }, 5000);
  }

  // Event Handlers
  editBtn.addEventListener('click', () => {
    toggleEditMode(true);
  });

  cancelBtn.addEventListener('click', () => {
    resetForm();
    toggleEditMode(false);
    showToast('Cancelled', 'Edit mode has been cancelled.', 'info');
  });

  resetBtn.addEventListener('click', () => {
    resetForm();
    showToast('Reset', 'Form has been reset to original values.', 'info');
  });

  settingsForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!validateForm()) {
      return;
    }

    showLoading();

    // Simulate API call
    setTimeout(() => {
      const newData = {
        full_name: fullNameInput.value.trim(),
        email: emailInput.value.trim()
      };

      currentData = newData;
      updateDisplay();

      toggleEditMode(false);
      hideLoading();
      showSuccessMessage();
      showToast('Success', 'Your profile has been updated successfully!', 'success');

      // Clear password field
      passwordInput.value = '';
    }, 1000);
  });

  // Initialize
  updateDisplay();

  // Add keyboard shortcut (Ctrl+E to edit)
  document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
      e.preventDefault();
      if (!isEditMode) {
        toggleEditMode(true);
      }
    }
  });
</script>
@endsection