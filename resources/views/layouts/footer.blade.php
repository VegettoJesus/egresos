<footer>
    <script id="permisos-json" type="application/json">
        {!! json_encode([
            'nuevo' => ($permiso_nuevo[0]->Valor ?? null) === "1",
            'modificar' => ($permiso_modificar[0]->Valor ?? null) === "1",
            'eliminar' => ($permiso_eliminar[0]->Valor ?? null) === "1"
        ]) !!}
    </script>

    <script src="{{ asset('js/main.js') }}"></script>
    @isset($script)
        <script src="{{ asset($script) }}"></script>
    @endisset
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</footer>
