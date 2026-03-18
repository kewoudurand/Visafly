@extends('layouts/admin')


@section('space-work')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">

                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Liste des Consultations</h4>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">

                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Téléphone</th>
                                                <th>Projet</th>
                                                <th>Pays souhaité</th>
                                                <th>Date d'inscription</th>
                                                <th>Traitement</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($consultations as $consult)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $consult->full_name }}</td>
                                                    <td>{{ $consult->email }}</td>
                                                    <td>{{ $consult->phone }}</td>
                                                    <td>{{ $consult->project_type }}</td>
                                                    <td>{{ $consult->destination_country }}</td>
                                                    <td>{{ $consult->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        <form action="{{ route('admin.statut', $consult->id) }}" method="POST">
                                                            @csrf
                                                            <button class="btn btn-primary btn-sm">
                                                                Terminer
                                                            </button>
                                                        </form>
                                                    </td>

                                                    <td class="d-flex">

                                                        <a href="{{route('admin.seeUser',$consult->id)}}"
                                                        class="btn btn-success btn-sm m-1">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <form action="{{route('admin.delete',$consult->id)}}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-danger btn-sm m-1">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>

                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        Aucune consultation trouvée.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const buttons = document.querySelectorAll('.finish-btn');

            buttons.forEach(btn => {
                btn.addEventListener('click', function () {

                    btn.outerHTML = `
                        <span class="badge badge-success p-2">
                            Déjà traité
                        </span>
                    `;
                });
            });
        });
    </script>


@endsection

