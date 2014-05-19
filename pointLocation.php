<?php

class pointLocation {
    var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices

    function pointLocation() {
    }
    
    
        function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        $this->pointOnVertex = $pointOnVertex;
        
        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = array(); 
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex); 
        }
        
        // Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return "vertex";
        }
        
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0; 
        $vertices_count = count($vertices);
    
        //echo "$vertices_count <br>";
        
        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
            	//echo "boundary";
                return "boundary";
            }
            
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
            //echo "<br>py ". $point['y'] ." - min v12y".min($vertex1['y'], $vertex2['y'])." - man v12y".max($vertex1['y'], $vertex2['y']). " - px".$point['x'] . " - maxv21x<br>";
            	
            	
            	$xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
							//echo $xinters."<br>";
            	
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                	//echo "boundary";
                    return "boundary";
                    
                }
                //echo "<br>".$vertex2['x'];
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++; 
               			//echo "intersection";     
                }
            } 
        } 
        // If the number of edges we passed through is even, then it's in the polygon.
        //$int = $intersections % 2;
        //echo  "intersection ".$intersections ."<br>".$int;
        if ($intersections % 2 != 0) {
            return "inside";
        } else {
            return "outside";
        }
    }

    
    
    function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    
    }
        
    
    function pointStringToCoordinates($pointString) {
    	if($pointString){
        $coordinates = explode(",", $pointString);
        //return array("x" => (float)$coordinates[0], "y" => (float)$coordinates[1]);
        return array("y" => (float)$coordinates[0], "x" => (float)$coordinates[1]);
    	}
    }
    
    
}
?>