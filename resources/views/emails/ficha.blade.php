<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 10px; }
        .header { color: #007bff; }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="header">¡Hola! Alguien quiere compartir esta tarea contigo</h2>
        <hr>
        <p><strong>Tarea:</strong> {{ $tarea->nombre }}</p>
        <p><strong>Descripción:</strong> {{ $tarea->descripcion }}</p>
        <p><strong>Categoría:</strong> {{ $tarea->categoria->nombre }}</p>
        <br>
        <p>Puedes ver más detalles entrando en la plataforma.</p>
    </div>
</body>
</html>