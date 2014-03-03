jQuery(document).ready(function () {
	
	
//    var trashpic_setting = JSON.parse(jQuery('#trashpic_setting').val());
	//var ts = JSON.parse(trashpic_setting);
	// Posizione iniziale della mappa
    var lat = trashpic_setting.latitude;
    var lon = trashpic_setting.longitude;
    var zoom = trashpic_setting.zoom;
    
    //alert(trashpic_setting);
    
   // var books = JSON.parse( trashpic_setting );

   // alert( books.latitude ); // JavaScript: The Definitive Guide
    var style = { strokeColor: '#0000ff',
   	     strokeOpacity: 1,
	     strokeWidth: 5,
	     fillOpacity: 0.5
	  };
    
    /*
     * Layer style
     */
    // we want opaque external graphics and non-opaque internal graphics
    var layer_style = OpenLayers.Util.extend({}, OpenLayers.Feature.Vector.style['default']);
    layer_style.fillOpacity = 0.2;
    layer_style.graphicOpacity = 1;

    // allow testing of specific renderers via "?renderer=Canvas", etc
    var renderer = OpenLayers.Util.getParameters(window.location.href).renderer;
    renderer = (renderer) ? [renderer] : OpenLayers.Layer.Vector.prototype.renderers;
    
        map = new OpenLayers.Map ("map", {
            controls:[ 
			new OpenLayers.Control.Navigation(),
                       new OpenLayers.Control.PanZoomBar(),
                       new OpenLayers.Control.ScaleLine(),
                       new OpenLayers.Control.Permalink('permalink'),
                       new OpenLayers.Control.MousePosition(),                    
                       new OpenLayers.Control.Attribution()
				      ],
            projection: new OpenLayers.Projection("EPSG:900913"),
            displayProjection: new OpenLayers.Projection("EPSG:4326")
            } );
 
		var mapnik = new OpenLayers.Layer.OSM("OpenStreetMap (Mapnik)");
		   var vectorLayer = new OpenLayers.Layer.Vector("Simple Geometry", {
               style: layer_style,
               renderers: renderer
           });
 
		map.addLayer(mapnik);
 
        var lonLat = new OpenLayers.LonLat( lon ,lat )
          .transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
            map.getProjectionObject() // to Spherical Mercator Projection
          );
 
        var pointsB = [];      //for Geometry.Point Objects
        var lonlatsB = [];      //for LonLat Objects
        
        // Define polygon area, using LonLat-Objects to create Polygon
        
        var lonlatObject1 = new OpenLayers.LonLat(8.23432 ,44.19632);
        var lonlatObject2 = new OpenLayers.LonLat(8.23741 ,44.18968);
        var lonlatObject3 = new OpenLayers.LonLat(8.22883 ,44.18007);
        var lonlatObject4 = new OpenLayers.LonLat(8.23329 ,44.16998);
        var lonlatObject5 = new OpenLayers.LonLat(8.27998 ,44.14609);
        var lonlatObject6 = new OpenLayers.LonLat(8.30127 ,44.15520);
        var lonlatObject7 = new OpenLayers.LonLat(8.28410 ,44.18106);
        var lonlatObject8 = new OpenLayers.LonLat(8.25595 ,44.20124);
        
        
        //add LonLats to Array
        lonlatsB.push(lonlatObject1);
        lonlatsB.push(lonlatObject2);
        lonlatsB.push(lonlatObject3);
        lonlatsB.push(lonlatObject4);
        lonlatsB.push(lonlatObject5);
        lonlatsB.push(lonlatObject6);
        lonlatsB.push(lonlatObject7);
        lonlatsB.push(lonlatObject8);
        
        //alert(map.getProjectionObject());
        //projecting LonLats to Points
        for (var i=0; i<lonlatsB.length; i++) {
         point = new OpenLayers.Geometry.Point(lonlatsB[i].lon, lonlatsB[i].lat);
         
         point.transform(
          new OpenLayers.Projection("EPSG:4326"),         //from
          map.getProjectionObject()                                    //to
           );
         //alert(point.x);
         pointsB.push(point);
        }
        pointsB.push(pointsB[0]);

        // create a polygon feature from a list of points
        var linearRingB = new OpenLayers.Geometry.LinearRing(pointsB);
        //var polygon = new OpenLayers.Geometry.Polygon([linearRingB]);
        //var polygonFeatureB = new OpenLayers.Feature.Vector(polygon,null,style);

        var polygonFeature = new OpenLayers.Feature.Vector(
                new OpenLayers.Geometry.Polygon([linearRingB]));
        
        
		map.addLayer(vectorLayer);
        
        vectorLayer.addFeatures([polygonFeature]);  
        
        
		map.setCenter (lonLat, zoom);

});
    
 