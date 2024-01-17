@include("includes.header")
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Explorer</title>
    <link rel="stylesheet" href="{{asset("css/note/Overview.css")}}">
</head>
<body>

<h1>Arbre de notes de {{\Illuminate\Support\Facades\Auth::user()->name}}</h1>

<div class="file-explorer">
    @include('arbo.folder', ['contents' => $directoryContent])
</div>

<script>
    let folders = document.querySelectorAll(".folder");

    folders.forEach(f => {
        f.addEventListener("click", () => {
            let nestedList = f.nextElementSibling;
            if (nestedList) {
                nestedList.classList.toggle("active");
            }
        });
    });
</script>

</body>
</html>
