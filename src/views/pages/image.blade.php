@extends('liveChat::layouts.header')

<!-- Navbar -->
<nav class="main-header navbar navbar-expand bg-dark bg-gradient" style="margin-left: 0;">

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        {{-- zoom-in --}}
      <li class="nav-item">
        <a class="nav-link text-white" href="#" id="zoom-in">
            <i class="fa-solid fa-magnifying-glass-plus"></i>
        </a>
      </li>
        {{-- zoom-out --}}
      <li class="nav-item">
        <a class="nav-link text-white" href="#" id="zoom-out">
            <i class="fa-solid fa-magnifying-glass-minus"></i>
        </a>
      </li>
      {{-- zoom level --}}
      <li class="nav-item">
        <div class="nav-link border border-white rounded text-white" id="zoomData" style="cursor:pointer">100 %</div>
      </li>
        {{-- Edit --}}
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fa fa-pen text-white"></i>
            </a>
        </li>
        {{-- highlight --}}
        <li class="nav-item">
            <a class="nav-link text-white" href="#">
                <i class="fa-solid fa-highlighter"></i>
            </a>
        </li>
        {{-- save --}}
        <li class="nav-item">
            <a class="nav-link text-white" href="#" id="save">
                <i class="fa-solid fa-download"></i>
            </a>
        </li>
        {{-- pdf --}}
        <li class="nav-item">
            <a class="nav-link text-white" href="#" onclick="pdfDown()">
                <i class="fa-solid fa-file-pdf"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->



<div class="container mt-3 mb-3">
<div class="d-flex justify-content-center">
    <img src="{{ asset($img) }}" alt="" id="img">
</div>
</div>

  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>


<script>

    let img =  document.querySelector('#img');
    let zoomData =  document.querySelector('#zoomData');

    // zoom-in
    let zoomIn =  document.querySelector('#zoom-in');
    zoomIn.onclick = function(){
        updateZoom(0.1);
    }

    // zoom-out
    let zoomOut =  document.querySelector('#zoom-out');
    zoomOut.onclick = function(){
        updateZoom(-0.1);
    }

    var zoomLevel = 1;

    var updateZoom = function(zoom) {
        zoomLevel += zoom;
        img.style.zoom = zoomLevel;
        zoomData.innerHTML = Math.round(zoomLevel*100)+' % ';
    }

    zoomData.onclick= ()=>{
        updateZoom(-(zoomLevel-1));
    }



    // save image
    var downloadBtn = document.getElementById('save');
    var imageElement = document.getElementById('img');

    downloadBtn.addEventListener('click', function() 
    {
        var imageUrl = imageElement.src;
        var fileName = 'image.jpg'; // Replace with the desired file name for the downloaded image

        downloadImage(imageUrl, fileName);
    });

    function downloadImage(imageUrl, fileName) 
    {
        var link = document.createElement('a');
        link.href = imageUrl;
        link.download = fileName;
        link.click();
    }

    // download image as pdf
    // function pdfDown()
    // {
    //     var doc = new jsPDF();
    //     doc.addImage(imageElement,10 ,10);
    //     doc.save('image.pdf');
    // }

    function pdfDown() 
    {
        var doc = new jsPDF();
        var pageWidth = doc.internal.pageSize.getWidth() - 20; // Subtracting 20 to leave some margin

        var imageWidth = imageElement.naturalWidth;
        var imageHeight = imageElement.naturalHeight;

        if (imageWidth > 700) 
        {
            var scaleFactor = pageWidth / imageWidth;
            imageWidth *= scaleFactor;
            imageHeight *= scaleFactor;
            doc.addImage(imageElement, 10, 10, imageWidth, imageHeight);
        } else {
            doc.addImage(imageElement, 10, 10);
        }

        doc.save('image.pdf');
    }

</script>


