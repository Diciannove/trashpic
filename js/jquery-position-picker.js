/**
 A JQUERY LOCATION PICKER

 version 0.0.2

 Copyright 2012  Alessandro Staniscia ( alessandro@staniscia.net )

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */




var OLLatLonPicker = (function () {

    var _self = this;

    // VARS
    _self.vars = {
        map:null,
        markers:null,
        latLongProj:new OpenLayers.Projection("EPSG:4326"),
        form:null
    };

    /**
     * Search and set the address value for a position
     *
     * @param lat
     * @param lng
     */
    var searchAndSetAddress = function (lat_EPSG_4326, lng_EPSG_4326) {
        (function ($) {
            $.ajax({
                url:'http://maps.googleapis.com/maps/api/geocode/json?latlng=' + encodeURIComponent(lat_EPSG_4326) + ',' + encodeURIComponent(lng_EPSG_4326) + '&sensor=false',
                dataType:'json'
            }).done(function (data) {
                    if (data['status'] == 'OK') {
                        setAddressField(data['results'][0].formatted_address);
                    } else {
                        setAddressField("");
                    }
                });
        })(jQuery);
    }

    /**
     * Search and Set position information for a address
     *
     * @param addressToFind
     */
    var searchAndSetMarker = function (address) {
        (function ($) {
            $.ajax({
                url:'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=' + encodeURIComponent(address),
                dataType:'json'
            }).done(function (data) {
                    if (data['status'] == 'OK') {
                        lat = data['results'][0].geometry.location.lat;
                        lng = data['results'][0].geometry.location.lng;
                        var location = new OpenLayers.LonLat(lng, lat).transform(_self.vars.latLongProj, _self.vars.map.getProjectionObject());
                        setMarkerMap(location.lat, location.lon, 10);
                        setMarkerField(location.lat, location.lon, _self.vars.map.getZoom());
                        searchAndSetAddress(lat, lng);
                        searchAndSetElevation(lat, lng);
                    } else {
                        alert("No position found");
                    }
                });
        })(jQuery);
    }


    /**
     * Search and Set the elevation value for a position
     *
     * @param lat_EPSG_4326
     * @param lng_EPSG_4326
     */
    var searchAndSetElevation = function (lat_EPSG_4326, lng_EPSG_4326) {
        (function ($) {
            $.ajax({
                url:'http://maps.googleapis.com/maps/api/elevation/json?sensor=false&locations=' + lat_EPSG_4326 + ',' + lng_EPSG_4326,
                dataType:'json'
            }).done(function (data) {
                    if (data['status'] == 'OK') {
                        elevation = data['results'][0].elevation;
                        $(_self.vars.form).find(".gllpElevation").val(elevation.toFixed(3));
                        onElevationChanged();
                    }
                });
        })(jQuery);
    };


    /**
     * set Marker field
     * @param lat_MapProjection
     * @param lng_MapProjection
     * @param zoom
     */
    var setMarkerField = function (lat_MapProjection, lng_MapProjection, zoom) {
        var location = new OpenLayers.LonLat(lng_MapProjection, lat_MapProjection).transform(_self.vars.map.getProjectionObject(), _self.vars.latLongProj);
        jQuery(_self.vars.form).find(".gllpLongitude").val(location.lon.toFixed(5));
        jQuery(_self.vars.form).find(".gllpLatitude").val(location.lat.toFixed(5));
        jQuery(_self.vars.form).find(".gllpZoom").val(zoom);
    }

    /**
     * Set the address Field
     *
     * @param address
     */
    var setAddressField = function (address) {
    	jQuery(_self.vars.form).find(".gllpLocationName").val(address);
        onLocationNameChanged();
    }


    /**
     * Set Marker On Map
     *
     * @param lat_MapProjection
     * @param lng_MapProjection
     * @param zoom
     */
    var setMarkerMap = function (lat_MapProjection, lng_MapProjection, zoom) {
        _self.vars.markers.clearMarkers();
        var location = new OpenLayers.LonLat(lng_MapProjection, lat_MapProjection);
        var size = new OpenLayers.Size(21, 25);
        var offset = new OpenLayers.Pixel(-(size.w / 2), -size.h);
        var icon = new OpenLayers.Icon('http://www.openlayers.org/dev/img/marker.png', size, offset);
        _self.vars.markers.addMarker(new OpenLayers.Marker(location, icon.clone()));
        _self.vars.map.panTo(location);
        _self.vars.map.zoomTo(zoom);
        onLocationChanged();
    };


    /**
     * Set Default Position
     *
     * @param lat_EPSG_4326
     * @param lng_EPSG_4326
     * @param zoom
     */
    var setDefault = function (lat_EPSG_4326, lng_EPSG_4326, zoom) {
        var location = new OpenLayers.LonLat(lng_EPSG_4326, lat_EPSG_4326).transform(_self.vars.latLongProj, _self.vars.map.getProjectionObject());
        setMarkerMap(location.lat, location.lon, zoom);
        setMarkerField(location.lat, location.lon, zoom);
        searchAndSetAddress(lat_EPSG_4326, lng_EPSG_4326);
        searchAndSetElevation(lat_EPSG_4326, lng_EPSG_4326);
    };


    /**
     * Fire location_changed event
     */
    var onLocationChanged = function () {
    	jQuery(_self.vars.form).trigger("location_changed", jQuery(_self.vars.cssID));
    }

    /**
     * Fire location_name_changed event
     */
    var onLocationNameChanged = function () {
    	jQuery(_self.vars.form).trigger("location_name_changed", jQuery(_self.vars.cssID));
    }

    /**
     * Fire elevation_changed event
     */
    var onElevationChanged = function () {
    	jQuery(_self.vars.form).trigger("elevation_changed", jQuery(_self.vars.cssID));
    }


    /**
     *  Update location and zoom values based on input field's value
     * @param center
     */
    var bindUpdateMapAction = function (center) {

    	jQuery(_self.vars.form).find(".gllpUpdateButton").bind("click", function () {
            var lat = jQuery(_self.vars.form).find(".gllpLatitude").val();
            var lng = jQuery(_self.vars.form).find(".gllpLongitude").val();
            var zoom = jQuery(_self.vars.form).find(".gllpZoom").val();
            var location = new OpenLayers.LonLat(lng, lat).transform(_self.vars.latLongProj, _self.vars.map.getProjectionObject());
            setMarkerMap(location.lat, location.lon, zoom);
            searchAndSetAddress(lat, lng);
            searchAndSetElevation(lat, lng);
        });
    }

    /**
     *  Search function by search button
     */
    var bindSearchAddressAction = function () {
    	jQuery(_self.vars.form).find(".gllpSearchButton").bind("click", function () {
            searchAndSetMarker($(_self.vars.form).find(".gllpSearchField").val(), false);
        });
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////
    // PUBLIC FUNCTIONS  //////////////////////////////////////////////////////////////////////////
    var publicfunc = {

    		
    		
    		
    		
    		
    		
    		
    		
    		
    		
        // INITIALIZE MAP ON DIV //////////////////////////////////////////////////////////////////
        init:function (object) {

            _self.vars.form = object;

            defLat = jQuery(_self.vars.form).find(".gllpLatitude").val() ? jQuery(_self.vars.form).find(".gllpLatitude").val() : trashpic_setting.latitude;
            defLng = jQuery(_self.vars.form).find(".gllpLongitude").val() ? jQuery(_self.vars.form).find(".gllpLongitude").val() : trashpic_setting.longitude;
            defZoom = jQuery(_self.vars.form).find(".gllpZoom").val() ? parseInt(jQuery(_self.vars.form).find(".gllpZoom").val()) : trashpic_setting.zoom;

            _self.vars.map = new OpenLayers.Map(jQuery(_self.vars.form).find(".gllpMap").get(0), {
                theme:null
            });

            
            
            
            
            
            
            
            
            var style_green = {
                    strokeColor: "#FF0000",
                    strokeWidth: 3,
                    strokeDashstyle: "dashdot",
                    pointRadius: 6,
                    pointerEvents: "visiblePainted",
                    title: "this is a green line"
                };


            
            
            var baseLayer = new OpenLayers.Layer.OSM();
            _self.vars.map.addLayer(baseLayer);

            var center = new OpenLayers.LonLat(defLng, defLat);
            _self.vars.map.setCenter(center, defZoom);
            _self.vars.markers = new OpenLayers.Layer.Markers("Position");
            _self.vars.markers.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat("44.17649","8.22461")));
            _self.vars.map.addLayer(_self.vars.markers);
            
            
            
    
            
            
            var LLPClickControl = OpenLayers.Class(OpenLayers.Control, {
                defaultHandlerOptions:{
                    'single':true,
                    'double':false,
                    'pixelTolerance':0,
                    'stopSingle':false,
                    'stopDouble':false
                },
                initialize:function (options) {
                    this.handlerOptions = OpenLayers.Util.extend(
                        {}, this.defaultHandlerOptions
                    );
                    OpenLayers.Control.prototype.initialize.apply(
                        this, arguments
                    );
                    this.handler = new OpenLayers.Handler.Click(
                        this, {
                            'click':this.trigger
                        }, this.handlerOptions
                    );
                },

                trigger:function (e) {
                    lonlat = _self.vars.map.getLonLatFromPixel(e.xy)
                    setMarkerMap(lonlat.lat, lonlat.lon, _self.vars.map.zoom);
                    setMarkerField(lonlat.lat, lonlat.lon, _self.vars.map.zoom);
                    var location = new OpenLayers.LonLat(lonlat.lon, lonlat.lat).transform(_self.vars.map.getProjectionObject(), _self.vars.latLongProj);
                    searchAndSetAddress(location.lat, location.lon);
                    searchAndSetElevation(location.lat, location.lon);
                }

            });


            var clickControl = new LLPClickControl();
            _self.vars.map.addControl(clickControl);
            clickControl.activate();

            setDefault(defLat, defLng, defZoom);

            bindUpdateMapAction(center);
            bindSearchAddressAction();
            
            
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
           alert(_self.vars.map.getProjectionObject());
            //projecting LonLats to Points
            for (var i=0; i<lonlatsB.length; i++) {
             point = new OpenLayers.Geometry.Point(lonlatsB[i].lon, lonlatsB[i].lat);
             
             point.transform(
              new OpenLayers.Projection("EPSG:4326"),         //from
              _self.vars.map.getProjectionObject()                                    //to
               );
             alert(point.x);
             pointsB.push(point);
            }
           // pointsB.push(pointsB[0]);

            // create a polygon feature from a list of points
            var linearRingB = new OpenLayers.Geometry.LinearRing(pointsB);
            var polygon = new OpenLayers.Geometry.Polygon(linearRingB);
            var polygonFeatureB = new OpenLayers.Feature.Vector(polygon, null, style_green);
            //var vectorLayer = new OpenLayers.Layer.OSM();
            var vectorLayer = new OpenLayers.Layer.Vector("Test");
            
            vectorLayer.addFeatures(polygonFeatureB);  
            
            
            
            _self.vars.map.addLayer(vectorLayer);
            
                    
          
            
            
            
        }

    }

    return publicfunc;
});


(function ($) {

    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function () {
    };


    $(document).ready(function () {
        $(".gllpLatlonPicker").each(function () {
            (new OLLatLonPicker()).init($(this));
        });
    });

    $(document).bind("location_changed", function (event, object) {
        console.log(object);
        console.log(event);
    });

    $(document).bind("location_name_changed", function (event, object) {
        console.log(object);
        console.log(event);
    });

    $(document).bind("elevation_changed", function (event, object) {
        console.log(object);
        console.log(event);
    });
})(jQuery);


