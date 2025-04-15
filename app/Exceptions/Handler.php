public function render($request, Throwable $exception)
{
    if ($request->expectsJson()) {
        $status = 500;
        $message = 'Une erreur inattendue est survenue.';

        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $status = 404;
            $message = 'Ressource non trouvée.';
        } elseif ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            $status = 403;
            $message = 'Accès non autorisé.';
        } elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
            $status = 422;
            $message = 'Erreur de validation.';
            return response()->json([
                'message' => $message,
                'errors' => $exception->errors(),
            ], $status);
        }

        return response()->json(['message' => $message], $status);
    }

    return parent::render($request, $exception);
}
