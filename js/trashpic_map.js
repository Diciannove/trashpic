jQuery(document).ready(function () {
	
	
//    var trashpic_setting = JSON.parse(jQuery('#trashpic_setting').val());
	//var ts = JSON.parse(trashpic_setting);
	// Posizione iniziale della mappa
    var lat = trashpic_setting.latitude;
    var lon = trashpic_setting.longitude;
    var zoom = trashpic_setting.zoom;
    var polygon = trashpic_setting.polygon;
    var tmarkers = trashpic_setting.tmarkers;
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
    
    	map = new OpenLayers.Map('map');
    	map.addLayer(new OpenLayers.Layer.OSM());
		//var mapnik = new OpenLayers.Layer.OSM("OpenStreetMap (Mapnik)");
    	//map.addLayer(mapnik);

		
		
		
		var vectorLayer = new OpenLayers.Layer.Vector("Simple Geometry", {
               style: layer_style,
               renderers: renderer
           });
 
 
        var lonLat = new OpenLayers.LonLat( lon ,lat )
        .transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());


        
        var pointsB = [];      //for Geometry.Point Objects
        var lonlatsB = [];      //for LonLat Objects
        
        // Define polygon area, using LonLat-Objects to create Polygon
        
        for(var p in polygon){
        	lonlatsB.push(new OpenLayers.LonLat(polygon[p].lon ,polygon[p].lat))
        }
        
        
        if(lonlatsB.length > 0 ){
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
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        

       var markers = new OpenLayers.Layer.Markers( "Markers" );
       map.addLayer(markers);		
       var size = new OpenLayers.Size(21,25);
       var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
       var icon = new OpenLayers.Icon('http://www.openlayers.org/dev/img/marker.png',size,offset);

       
       var popupHandler = function(marker,img){
    	   return function(e){
    		          var mylonLat = new OpenLayers.LonLat( marker.lonlat.lon ,marker.lonlat.lat )
    		          .transform( map.getProjectionObject(),new OpenLayers.Projection("EPSG:4326"));

    		   		  var text = "Lat: " +  mylonLat.lat.toFixed(4) + "</br>";
    		   		  text += "Lon: " +  mylonLat.lon.toFixed(4) + "</br></br>";
    		   		  if(img){
    		   			  text += "<a target='_blank' class='fancybox' href='"+img+"'><img src='"+img+"' width='200' /></a>";
    		   		  }	else text += "no img";
    		   		  
    	               popup = new OpenLayers.Popup.FramedCloud("chicken",
    	                                                 marker.lonlat, new OpenLayers.Size(530, 530),
    	                                                 text, null, true);
    	              
    	              map.addPopup(popup);
    	              
    	   }
    	 }        
       
       
       for(var m in tmarkers){
       
    	  var newic = icon.clone()
    	  var marker = new OpenLayers.Marker(new OpenLayers.LonLat(tmarkers[m].lon,tmarkers[m].lat).transform(
    			          new OpenLayers.Projection("EPSG:4326"), 
                            map.getProjectionObject()),
                              newic
    	  					 );
    	 markers.addMarker(marker);
    	 marker.events.register("mousedown", marker, popupHandler(marker,tmarkers[m].img));
    	 
        }

       
       	/*
       var marker = new OpenLayers.Marker(lonLat,icon.clone());
  
    	   markers.addMarker(marker);

    	   
    	   marker.events.register("mousedown", marker, function(e){
    		   popup = new OpenLayers.Popup.FramedCloud("chicken",
                       marker.lonlat,
                       new OpenLayers.Size(200, 200),
                       "example popup",
                       null, true);

    		   map.addPopup(popup);
    	  });
       */
       
		
        
		map.setCenter (lonLat, zoom);

});
    
 