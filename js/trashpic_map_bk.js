
	
	
	
	
	
	
// Posizione iniziale della mappa
    var lat=44.16621;
    var lon=8.27123;
    var zoom=13;
 
    
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
        var lonlatObject1 = new OpenLayers.LonLat(44.17649,8.22461);
        var lonlatObject2 = new OpenLayers.LonLat(44.17834,8.27593);
        var lonlatObject3 = new OpenLayers.LonLat(44.15741,8.27576);
        var lonlatObject4 = new OpenLayers.LonLat(44.16418,8.33018);
        
        //add LonLats to Array
        lonlatsB.push(lonlatObject1);
        lonlatsB.push(lonlatObject2);
        lonlatsB.push(lonlatObject3);
        lonlatsB.push(lonlatObject4);
        alert(map.getProjectionObject());
        //projecting LonLats to Points
        for (var i=0; i<lonlatsB.length; i++) {
         point = new OpenLayers.Geometry.Point(lonlatsB[i].lon, lonlatsB[i].lat);
         
         point.transform(
          new OpenLayers.Projection("EPSG:4326"),         //from
          map.getProjectionObject()                                    //to
           );
         alert(point.x);
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
 

		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		jQuery(document).ready(function () {
			
			
			
			
			map = new OpenLayers.Map('map');
		    var layer = new OpenLayers.Layer.WMS( "OpenLayers WMS",
		            "http://vmap0.tiles.osgeo.org/wms/vmap0", {layers: 'basic'} );
		    map.addLayer(layer);

		    // allow testing of specific renderers via "?renderer=Canvas", etc
		    var renderer = OpenLayers.Util.getParameters(window.location.href).renderer;
		    renderer = (renderer) ? [renderer] : OpenLayers.Layer.Vector.prototype.renderers;

		    /*
		     * Layer style
		     */
		    // we want opaque external graphics and non-opaque internal graphics
		    var layer_style = OpenLayers.Util.extend({}, OpenLayers.Feature.Vector.style['default']);
		    layer_style.fillOpacity = 0.2;
		    layer_style.graphicOpacity = 1;

		    /*
		     * Blue style
		     */
		    var style_blue = OpenLayers.Util.extend({}, layer_style);
		    style_blue.strokeColor = "blue";
		    style_blue.fillColor = "blue";
		    style_blue.graphicName = "star";
		    style_blue.pointRadius = 10;
		    style_blue.strokeWidth = 3;
		    style_blue.rotation = 45;
		    style_blue.strokeLinecap = "butt";

		    /*
		     * Green style
		     */
		    var style_green = {
		        strokeColor: "#00FF00",
		        strokeWidth: 3,
		        strokeDashstyle: "dashdot",
		        pointRadius: 6,
		        pointerEvents: "visiblePainted",
		        title: "this is a green line"
		    };

		    /*
		     * Mark style
		     */
		    var style_mark = OpenLayers.Util.extend({}, OpenLayers.Feature.Vector.style['default']);
		    // each of the three lines below means the same, if only one of
		    // them is active: the image will have a size of 24px, and the
		    // aspect ratio will be kept
		    // style_mark.pointRadius = 12;
		    // style_mark.graphicHeight = 24; 
		    // style_mark.graphicWidth = 24;

		    // if graphicWidth and graphicHeight are both set, the aspect ratio
		    // of the image will be ignored
		    style_mark.graphicWidth = 24;
		    style_mark.graphicHeight = 20;
		    style_mark.graphicXOffset = 10; // default is -(style_mark.graphicWidth/2);
		    style_mark.graphicYOffset = -style_mark.graphicHeight;
		    style_mark.externalGraphic = "../img/marker.png";
		    // title only works in Firefox and Internet Explorer
		    style_mark.title = "this is a test tooltip";

		    var vectorLayer = new OpenLayers.Layer.Vector("Simple Geometry", {
		        style: layer_style,
		        renderers: renderer
		    });
		    
		    
		    var lonLat = new OpenLayers.LonLat( 44.16621, 8.27123 )
		    .transform(
		      new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
		      map.getProjectionObject() // to Spherical Mercator Projection
		    );
		    
		    
		    
		    
		    var pointList = [];
		    

		    var point = new OpenLayers.Geometry.Point(lonLat.lat,lonLat.lon);
		    alert (point.x);
		    var pointList = [];
		    for(var p=0; p<6; ++p) {
		        var a = p * (2 * Math.PI) / 7;
		        var r = Math.random(1) + 1;
		        var newPoint = new OpenLayers.Geometry.Point(point.x + (r * Math.cos(a)),
		                                                     point.y + (r * Math.sin(a)));
		        pointList.push(newPoint);
		    }
		    pointList.push(pointList[0]);

		    var linearRing = new OpenLayers.Geometry.LinearRing(pointList);
		    var polygonFeature = new OpenLayers.Feature.Vector(
		        new OpenLayers.Geometry.Polygon([linearRing]));


		    map.addLayer(vectorLayer);
		    map.setCenter(new OpenLayers.LonLat(point.x, point.y), 5);
		    vectorLayer.addFeatures([polygonFeature]);    
			
		});
		    
		 