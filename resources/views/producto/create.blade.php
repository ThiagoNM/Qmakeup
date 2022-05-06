<html>
    <body>
        <form method="post" action="{{route('files.store')}}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file">
            <button type="submit" >Enviar</button>
        </form>    </body>
</html>
