<?php
/**
 * @author Stephen Roca
 * @since 06/30/2012
 */
$this->extend("/Common/common");
$this->start('sidebar');
$this->end();
$this->assign("title", "Resources");
$this->Html->addCrumb('Resources', '/resources');
$this->start('middle');

?>
<script type="text/javascript"
        src="http://maps.google.com/maps/api/js?key=AIzaSyAQBiaV8MmcqlYgJmxhiTao9TJt7_xYNGY&libraries=geometry,places"></script>
<script>
    var geocoder;
    var map;
    var price = $('#price');
    var quantity = $('#quantity');
    var total = $('#total');

    function initialize() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(33.774704, -84.397465);
        var mapOptions = {
            zoom: 15,
            center: latlng,
            scrollwheel: false
        };
        map = new google.maps.Map(document.getElementById("map"), mapOptions);
    }

    google.maps.event.addDomListener(window, 'load', initialize);
    initialize();

    function calculate() {
        //$('#results').toggle();
        var travelMode = $('input:radio[name=driveorfly]:checked').val();
        initialize(); //remove old directions
        if (travelMode == "Driving") {
            calcDriving();
        } else {
            calcFlying();
        }
        $('#price').val('$0.00');
        $('#quantity').val(0);
        $('#total').val('$0.00');
    }

    function calcDriving() {
        var origin = document.getElementById("drivingfrom").value,
            destination = document.getElementById("drivingto").value,
            service = new google.maps.DistanceMatrixService();

        service.getDistanceMatrix(
            {
                origins: [origin],
                destinations: [destination],
                travelMode: google.maps.TravelMode.DRIVING,
                avoidHighways: false,
                avoidTolls: false,
                unitSystem: google.maps.UnitSystem.IMPERIAL
            },
            callbackDrive
        );
    }

    function callbackDrive(response, status) {
        var numStu = document.getElementById("numStudents");

        if (!response.rows[0].elements[0].distance) {
            alert("Unable to find the entered destination");
        } else if (status == "OK") {
            var miles = parseFloat(response.rows[0].elements[0].distance.text.replace(',', ''));
            //dest.value = miles;
            if (miles < 150 && miles > 30) {
                alert("SGA does not fund travel within 150 miles of Georgia Tech's Atlanta Campus. This distance is only " + miles.toFixed(2) + " miles one-way.");
                price.value = '$0.00';
                quantity.value = 0;
                total.value = '$0.00';
            } else {
                if (miles <= 30) {
                    alert("SGA does not fund travel within 150 miles of Georgia Tech's Atlanta Campus. This distance is only " + miles.toFixed(2) + " miles one-way.\n\nCommunity Service organizations may receive funding if the following conditions are met:\n - Travel involves 4+ students\n - Travel occurs 5+ different times per semester but not necessarily to the same location\n - Travel does not exceed 30 miles one-way");
                }
                var funding = 0.05 * 0.535 * (miles * 2);
                if (funding > 75) {
                    funding = 75;
                }
                funding = funding.toFixed(2);
                var fundingFormat = funding.replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                var totFunding = (funding * numStu.value).toFixed(2);
                var totFundingFormat = totFunding.replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                $('#price').val('$' + fundingFormat);
                $('#quantity').val(numStu.value);
                $('#total').val('$' + totFundingFormat);
                //Display route
                var directionsDisplay = new google.maps.DirectionsRenderer();// also, constructor can get "DirectionsRendererOptions" object
                directionsDisplay.setMap(map); // map should be already initialized.

                var request = {
                    origin: response.originAddresses[0],
                    destination: response.destinationAddresses[0],
                    travelMode: google.maps.TravelMode.DRIVING
                };
                var directionsService = new google.maps.DirectionsService();
                directionsService.route(request, function (response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                    }
                });
            }

        } else {
            alert("Error: " + status);
        }


    }

    var lat1, lng1, lat2, lng2;
    function calcFlying() {
        //var geocoder = new google.maps.Geocoder();
        var source = $('#drivingfrom').val();//document.getElementById("drivingfrom").value;
        var destination = $('#drivingto').val();//document.getElementById("drivingto").value;
        var ready = false;
        //alert(source);
        //alert(destination);
        /*geocoder.geocode( { 'address': source}, function(results, status) {
         //alert(google.maps.GeocoderStatus);
         if (status == google.maps.GeocoderStatus.OK) {
         lat1 = results[0].geometry.location.lat();
         //alert(lat1);
         lng1 = results[0].geometry.location.lng();
         } else {
         alert("Geocode was not successful for the following reason: " + status);
         lat1=-1;lng1 = -1;
         }
         });*/
        /*geocoder.geocode( { 'address': destination}, function(results, status) {
         if (status == google.maps.GeocoderStatus.OK) {
         lat2 = results[0].geometry.location.lat();
         //alert(lat2);
         lng2 = results[0].geometry.location.lng();
         ready = true;
         } else {
         //alert("Geocode was not successful for the following reason: " + status);
         lat2=-1;lng2 = -1;
         ready = false;
         }

         });*/

        var service = new google.maps.places.PlacesService(map);
        service.textSearch({
            query: source
        }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                lat1 = results[0].geometry.location.lat();
                //alert(lat1);
                lng1 = results[0].geometry.location.lng();
            } else {
                alert("Geocode was not successful for the following reason: " + status);
                lat1 = -1;
                lng1 = -1;
            }
        });
        service.textSearch({
            query: destination
        }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                lat2 = results[0].geometry.location.lat();
                //alert(lat1);
                lng2 = results[0].geometry.location.lng();
                ready = true;
            } else {
                alert("Geocode was not successful for the following reason: " + status);
                lat2 = -1;
                lng2 = -1;
                ready = false;
            }
        });

        setTimeout("checkLoaded()", 1000);
    }


    function calcFlying2() {
        //alert("vals:  " + lat1 + " " + lng1 + " " + lat2 + " " + lng2);
        if (lat2 == -1 && lng2 == -1) {
            alert("Unable to find the entered destination");
        } else {

            var marker1 = new google.maps.Marker({
                map: map,
                draggable: false,
                position: {lat: lat1, lng: lng1}
            });
            //var marker1 = map.addMarker(new MarkerOptions().position(new LatLng(lat1, lng1)).title("Source"));

            var marker2 = new google.maps.Marker({
                map: map,
                draggable: false,
                position: {lat: lat2, lng: lng2}
            });

            var bounds = new google.maps.LatLngBounds(marker1.getPosition());//, marker2.getPosition());
            bounds.extend(marker2.getPosition());
            map.fitBounds(bounds);

            var geodesicPoly = new google.maps.Polyline({
                strokeColor: '#CC0099',
                strokeOpacity: 1.0,
                strokeWeight: 3,
                geodesic: true,
                map: map
            });

            var path = [marker1.getPosition(), marker2.getPosition()];
            //geodesicPoly.setPath(path);

            var numStu = document.getElementById("numStudents");
            var meters = google.maps.geometry.spherical.computeDistanceBetween(marker1.getPosition(), marker2.getPosition());//get geodesic distance
            var miles = meters * 0.000621371; //convert meters to miles
            if (miles < 350) {
                alert("Flying formula can only be used if distance exceeds 350 miles one-way.\nThis distance is only " + miles.toFixed(2) + " one-way.");
                price.value = '$0.00';
                quantity.value = 0;
                total.value = '$0.00';
            } else {
                var funding = 0.094 * (miles * 2);
                if (funding > 150) {
                    funding = 150;
                }
                funding = funding.toFixed(2);
                var fundingFormat = funding.replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                var totFunding = (funding * numStu.value).toFixed(2);
                var totFundingFormat = totFunding.replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                $('#price').val('$' + fundingFormat);
                $('#quantity').val(numStu.value);
                $('#total').val('$' + totFundingFormat);
                geodesicPoly.setPath(path);
            }
        }
    }

    function checkLoaded() {
        if (lng2) {
            calcFlying2();
        }
    }
</script>

<style>

    @import url(http://fonts.googleapis.com/css?family=Open+Sans:300,400,700);

    * {
        margin: 0;
        padding: 0;
    }

    body {
        /*background:#567;*/
        font-family: 'Open Sans', sans-serif;
        /*width: 40%;!*455px;*!
        display: table;*/
        margin: auto;
    }

    .button {
        width: 80%; /*100px;*/
        background: #3399cc;
        display: block;
        margin: 0 auto;
        margin-top: 1%;
        padding: 10px;
        text-align: center;
        text-decoration: none;
        color: #fff;
        cursor: pointer;
        transition: background .3s;
        -webkit-transition: background .3s;
    }

    .toggle {
        margin-top: 20px;
    }

    .button:hover {
        background: #2288bb;
    }

    #login, #register, #searches, #results, #friends, .section {
        width: 400px;
        margin: 0 auto;
        margin-top: 3px;
        margin-bottom: 2%;
        transition: opacity 1s;
        -webkit-transition: opacity 1s;
        clear: both;
        padding: 4%;
    }

    #results {
        width: 400px !important;
        margin-top: -16px !important;
    }

    .display {
        width: 400px;
        margin: 0 auto;
        margin-bottom: 2%;
        text-align: center;
        border-collapse: collapse;
        transition: opacity 1s;
        -webkit-transition: opacity 1s;
    }

    .display td {
        padding: 10px;
    }

    .display tr {
        border: solid;
        border-width: 1px 0;
        border-color: #CCCCCC;
    }

    #searchestable tr:hover, #friendstable tr:hover, #salestable tr:hover, #resultstable tr:hover {
        background-color: #DDDDFF;
    }

    .desc {
        font-size: 11px;
    }

    #searchestable, #friendstable, #salestable, #resultstable tr {
        cursor: pointer;
    }

    #searchestable {
        margin-bottom: -1px;
    }

    /*#frienddetail tr {
      cursor: none;
    }*/

    #triangle, .triangle {
        width: 0;
        border-top: 12px solid transparent;
        border-right: 12px solid transparent;
        border-bottom: 12px solid #3399cc;
        border-left: 12px solid transparent;
    }

    #triangle {
        margin: 0 auto;
    }

    #lefttriangle {
        margin-left: 52px;
    }

    #righttriangle {
        margin-left: 325px;
    }

    #centertriangle {
        margin-left: 185px;
    }

    #login h1 {
        background: #3399cc;
        padding: 20px 0;
        font-size: 140%;
        font-weight: 300;
        text-align: center;
        color: #fff;
    }

    form, table {
        background: #f0f0f0;
        padding: 2%;
        line-height: normal;
    }

    input[type="email"], input[type="password"], input[type="text"], input[type="number"], input[type="date"], textarea, select {
        width: 92%;
        background: #fff;
        margin-bottom: 4%;
        border: 1px solid #ccc;
        padding: 4%;
        font-family: 'Open Sans', sans-serif;
        font-size: 95%;
        color: #555;
    }

    input[type="submit"], input[type="button"] {
        width: 100%;
        background: #3399cc;
        border: 0;
        padding: 4%;
        font-family: 'Open Sans', sans-serif;
        font-size: 100%;
        color: #fff;
        cursor: pointer;
        transition: background .3s;
        -webkit-transition: background .3s;
    }

    input[type="submit"]:hover, input[type="button"]:hover {
        background: #2288bb;
    }

    .actionbutton {
        text-align: center;
        width: 50%;
        background: #3399cc;
        border: 0;
        padding: 2.5%;
        font-family: 'Open Sans', sans-serif;
        font-size: 100%;
        color: #fff;
        cursor: pointer;
        transition: background .3s;
        -webkit-transition: background .3s;
    }

    span {
        display: table !important;
        vertical-align: top;
    }

    .left {
        float: left;
    }

    .right {
        float: right;
    }

    #spans {
        width: 88%;
        margin: 0 auto;
    }

    .noformat {
        background: none;
        width: 100%;
        padding: 0;
    }

    #funding {
        padding-top: 0;
    }

    #travelContent {
        width: 802px;
        margin: auto;
    }

    .formContent {
        border: 1px solid #3399cc;
    }

    textarea {
        height: 150px;
    }

    #contactsubmit {
        width: 92%;
    }

    #data {
        padding-bottom: 15px;
    }
</style>

<br>

<div id="travelContent" class="formContent">
    <table align="center">
        <tr>
            <td>
                <div id="login">
                    <!--<div id="lefttriangle" class="triangle"></div>-->
                    <h1>SGA Travel Calculator</h1>
                    <form id="drivingform" onSubmit="calculate(); return false;">
                        From<br>
                        <input id="drivingfrom" type="text" value="Georgia Tech" required/><br>
                        To<br>
                        <input id="drivingto" type="text" placeholder="Destination" required/><br>
                        Number of Students<br>
                        <input id="numStudents" type="number" min=1 placeholder="# of Students" value=1 required/><br>
                        <input name="driveorfly" type="radio" value="Driving" required checked="checked"/> Driving<br>
                        <input name="driveorfly" type="radio" value="Flying"/> Flying <br><br>
                        <input id="drivingsubmit" type="submit" value="Calculate"/>
                    </form>
                </div>
            </td>
            <!--  <td>
              <div id="middlespace">&nbsp;&nbsp;&nbsp;&nbsp;<br><br></div>
              </td>-->
            <td>
                <div id="results">
                    <!--<div id="googleMap" style="width:500px;height:380px;"></div>-->
                    <div id="data">
                        <table id="funding">
                            <tr>
                                <td>
                                    Cost/Unit
                                    <input id="price" type="text" disabled/>
                                </td>
                                <td>
                                    Quantity
                                    <input id="quantity" type="text" disabled/>
                                </td>
                                <td>
                                    Total Allocation
                                    <input id="total" type="text" disabled/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="map" style="width:357px;height:357px;"></div>
                </div>
            </td>
        </tr>
    </table>
</div>

<?php
$this->end();
?>
