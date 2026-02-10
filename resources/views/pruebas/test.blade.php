@extends ('layouts.app')
@section('contenido')

<div class="container">
    <h2>mi seccion de pruebas</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody>
            @foreach($viewData['tareas'] as $tarea)
             <tr>
            <td>{{tarea->id}}</td>
            <td>{{tarea->nombre}}</td>
            
             <td>
                <form action="{{route ('tareas.delete' $tarea->id)}}" method="POST">
                    @csrf
                    @method('Delete')
                    <button type="submit" class="btn btn-danger btn -sm">
                        Eliminar
                    </button>
                </form>
             </td>

        </tbody>

    </table>
</div>
@endsection