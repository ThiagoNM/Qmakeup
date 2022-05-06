@extends('layouts.app');

@section('content')
    <style>
        input[type=file]{
            padding:10px;
            background:#2d2d2d;}
    </style>
        <div class="container">
            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
            <h1 style="text-align: center; margin-bottom: 5%">Editar por nueva foto</h1>
            <div style="float: left; width: 50%">
                <h2 style="text-align: center">Foto actual</h2>
                <img alt="foto del fichero" class="img-fluid" src="{{asset("storage/$file->filepath")}}" title="image preview"/>
            </div>
            <div style="float: left">
                <h2 style="text-align: center">Nueva foto</h2>
                <form method="post" action="{{route('files.update',$file)}}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <label>Fichero: <input id='inputFile'type="file" name="file" class="custom-file-input"  onchange="readURL(this);"></label>
                    <button type="submit" class="btn btn-primary">Editar</button>
                </form>
            </div>
                <img id="blah" src="http://placehold.it/180" alt="your image" />
        </div>
    <script>
        inputF = document.getElementById('inputFile')
        inputF.onchange = evt => {
            const [file] = inputF.files
            if (file) {
                blah.src = URL.createObjectURL(file)
            }

        }

    </script>
@endsection


