<!--resources/views/admin/accounts.blade.php-->
@extends('admin.layout')

@section('content')
<div class="">
<div class="">
  <h1>Account Management</h1>
  <p>Manage user accounts, roles, and permissions here.</p>
</div>

<div class="">
  <!--search and filter controls-->
  <div class="search-trigger">

  </div>
  <div class="filter-trigger">
  
  </div>
  <div class="Add-user-btn">

  </div>

</div>

<!--user accounts table-->
<div class="table-card">
  <table class="accounts-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Actions</th> <!--make this clickable to open action dropdown-->
      </tr>
    </thead>
    <tbody>
      <!--user rows will be dynamically generated here-->
    </tbody>
  </table>

  <!--pagination controls-->
  <div class="pagination">
    <button class="prev-page" disabled>Previous</button>
    <span class="page-info">Page 1 of 10</span>
    <button class="next-page">Next</button>
  </div>
</div>

<!--user action dropdown (edit, delete, view)-->
<div class="action-dropdown">
  <ul>
    <li class="edit-user">Edit</li>
    <li class="delete-user">Delete</li>
    <li class="view-user">View Details</li>
  </ul>
</div> <!--use svg icons for the dropdown trigger and actions-->

<!--modals-->
<div class="modals">
  <div class="add-user-modal">

  </div>

  <div class="edit-user-modal">
  </div>

  <div class="delete-user-modal">
  </div>

  <div class="view-user-modal">

  </div>
</div>

<!--validation, error, and success messages-->
<div class="validation-modal">
  <div class="succesfully-created">

  </div>
  <div class="succesfully-updated">
  </div>

  <div class="succesfully-deleted">
  </div>

  <div class="error-message">
  </div>

  <div class="validation-errors">
  </div>

  <div class="validation-delete-modal">

  </div>

  <div class="validation-edit-modal">
  </div>

</div>

<!--loading spinners-->
<div class="loading-spinner">
  <div class="spinner">
<!--pagmay isasave, idedelete or iuupdate-->
  </div>
</div>

</div>
@endsection