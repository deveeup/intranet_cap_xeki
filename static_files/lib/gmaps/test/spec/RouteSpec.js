var map_with_routes,route,routes;describe("Drawing a route",function(){beforeEach(function(){map_with_routes=map_with_routes||new GMaps({el:"#map-with-routes",lat:-12.0433,lng:-77.0283,zoom:12})}),it("should add a line in the current map",function(){var t;runs(function(){t=!1,map_with_routes.drawRoute({origin:[-12.044012922866312,-77.02470665341184],destination:[-12.090814532191756,-77.02271108990476],travelMode:"driving",strokeColor:"#131540",strokeOpacity:.6,strokeWeight:6,callback:function(){t=!0}})}),waitsFor(function(){return t},"The drawn route should create a line in the current map",3500),runs(function(){expect(map_with_routes.polylines.length).toEqual(1),expect(map_with_routes.polylines[0].get("strokeColor")).toEqual("#131540"),expect(map_with_routes.polylines[0].get("strokeOpacity")).toEqual(.6),expect(map_with_routes.polylines[0].getMap()).toEqual(map_with_routes.map)})})}),describe("Getting routes",function(){beforeEach(function(){map_with_routes=map_with_routes||new GMaps({el:"#map-with-routes",lat:-12.0433,lng:-77.0283,zoom:12})}),it("should return an array of routes",function(){var t,e;runs(function(){e=!1,map_with_routes.getRoutes({origin:"grand central station, new york, ny",destination:"350 5th Ave, New York, NY, 10118",callback:function(o){t=o,e=!0}})}),waitsFor(function(){return e},"#getRoutes should return the found routes as an argument",3500),runs(function(){expect(t).toBeDefined(),expect(map_with_routes.routes).toEqual(t),t.length>0&&(expect(t[0].legs[0].distance).toBeDefined(),expect(t[0].legs[0].duration).toBeDefined())})})});