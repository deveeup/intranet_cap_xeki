describe("Create a Street View Panorama",function(){var e,t,a,n;beforeEach(function(){e=e||new GMaps({el:"#map-with-streetview",lat:42.3455,lng:-71.0983,zoom:12})}),describe("Standalone",function(){beforeEach(function(){a=a||GMaps.createPanorama({el:"#streetview-standalone-panorama",lat:42.3455,lng:-71.0983,pov:{heading:60,pitch:-10,zoom:1}})}),it("should create a Street View panorama",function(){expect(a).toBeDefined()})}),describe("Attached to the current map",function(){beforeEach(function(){t=t||e.createPanorama({el:"#streetview-panorama",pov:{heading:60,pitch:-10,zoom:1}})}),it("should be equal to the current map Street View panorama",function(){expect(e.getStreetView()).toEqual(t)})}),describe("With events",function(){var e;beforeEach(function(){e={onpovchanged:function(){console.log(this)}},spyOn(e,"onpovchanged").andCallThrough(),n=n||GMaps.createPanorama({el:"#streetview-with-events",lat:42.3455,lng:-71.0983,pov:{heading:60,pitch:-10,zoom:1},pov_changed:e.onpovchanged})}),it("should respond to pov_changed event",function(){n.setPov({heading:80,pitch:-10,zoom:1}),expect(e.onpovchanged).toHaveBeenCalled()})})});