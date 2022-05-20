<!-- @extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '404')
@section('message', __($exception->getMessage() ?: 'Forbidden')) -->

<body>
    @section('message', __($exception->getMessage() ?: 'Forbidden'))
    <h1>ERROR 404 </h1>
</body>