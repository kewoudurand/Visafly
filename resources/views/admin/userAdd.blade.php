@extends('layouts/admin')

@section('styles')
<style>
  #outer{
    width: auto;
    text-align: center;
  }
  .inner{
    display: inline-block;
  }
</style>
@endsection

@section('space-work')
  <!-- Main Content -->
  <div class="main-content">
    <section class="section">
      <div class="section-body">
        <div class="row">
          <div class="">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Users Details</h4>
                <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                  <i class="fas fa-plus"></i> Add</button>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped">
                    <tr>
                      <th class="pt-2">
                        <div class="custom-checkbox custom-checkbox-table custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad"
                            class="custom-control-input" id="checkbox-all">
                          <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                        </div>
                      </th>
                      <th>Name</th>
                      <th>Surname</th>
                      <th>Email</th>
                      <th>Status</th>
                    </tr>
                    <tr>
                      @if (count($users) > 0)
                          @foreach ($users as $user)
                              <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $user->frist_name }}</td>
                                  <td>{{ $user->last_name }}</td>
                                  <td>{{ $user->email }}</td>

                                  <td id="outer" class="d-flex justify-content-center align-items-center">

                                    <button type="submit" class="inner m-2 btn btn-sm btn-warning" data-toggle="modal" data-target="#changeRoleModal">
                                      <i class="fas fa-edit"></i></button>

                                    {{-- Form for deleting user --}}
                                    <form method="POST" action="{{route('admin.user.destroy',$user->id)}}">
                                      @csrf
                                      @method('DELETE')
                                      
                                      <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                      </button>
                                    </form>
                                  </td>
                                  
                              </tr>
                          @endforeach
                      @else
                          <tr>
                              <div class="alert alert-warning">
                                <p>User not Found!</p>
                              </div>
                          </tr>
                      @endif
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Modal with form -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="formModal">Add  new User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('register.post')}}" method="post"  class="needs-validation" novalidate="">
            @csrf
            <div class="row">
              <div class="form-group col-6">
                <label for="name">First Name</label>
                <input id="name" type="text" class="form-control" name="frist_name" autofocus>
              </div>
              <div class="form-group col-6">
                <label for="surname">Last Name</label>
                <input id="surname" type="text" class="form-control" name="last_name">
              </div>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="email" class="form-control" name="email">
              <div class="invalid-feedback">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-6">
                <input id="password" type="hidden" class="form-control pwstrength" data-indicator="pwindicator"
                  name="password" value="12345678">
                <div id="pwindicator" class="pwindicator">
                  <div class="bar"></div>
                  <div class="label"></div>
                </div>
              </div>
              <div class="form-group col-6">
                <input id="password_confirmation" type="hidden" class="form-control" name="password_confirmation" value="12345678">
              </div>
            </div>
             <!-- 🔥 Sélecteur de rôle -->
              <div class="form-group">
                  <label for="role">Rôle de l'utilisateur</label>
                  <select name="role" class="form-control" required>
                      <option value="">-- Sélectionnez un rôle --</option>
                      <option value="admin">Admin</option>
                      <option value="secretaire">Secrétaire</option>
                  </select>
              </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg btn-block">
                Register
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


@endsection