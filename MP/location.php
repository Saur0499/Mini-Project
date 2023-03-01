<?php
include_once 'includes/header.php';
include_once 'includes/dbcon.php';

if(isset($_POST["submitlocation"]))
{
    $location=mysqli_real_escape_string($con,$_POST["location"]);
    $location=explode("(",$location,2);
    $location=explode(")",$location[1],2);
    $location=explode(",",$location[0],2);
}
?>
<section>
    <div id="map" style="height:75%; width:80%; left:10%; top:50px;"></div>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDslrStOct7ey62gWE754DTCm7lc8nUXOY&callback=initMap&libraries=&v=weekly"
        defer
    ></script>

    <script>
        function initMap() 
        {
            var location = { lat:<?php echo"$location[0]"?>, lng: <?php echo"$location[1]"?>};
            const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: location,
        });
        const marker = new google.maps.Marker({
            position:location,
            map: map,
        });
    }
  </script>
</section>


<?php
    include_once 'includes/footer.php';
?>