@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <h1 style="text-align: center; font-weight: bold;">Foto Actual</h1>
        <div style="width: 50%; margin: 0% auto">
            <img style="width: 100%; height: auto" alt="foto del fichero" class="img-fluid" src="{{asset("storage/$file->filepath")}}" title="image preview"/>
            <form method="post" action="{{route('files.destroy',$file)}}">
                @csrf
                @method('delete')
                <div style="text-align: center; padding-top: 20px">
                    <a style="margin-right: 100px" href="{{route("files.edit",$file)}}" type="button" class="btn btn-primary">Editar</a>
                    <button class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>

    </div>
@endsection

