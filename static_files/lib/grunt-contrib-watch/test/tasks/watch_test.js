"use strict";function cleanUp(){useFixtures.forEach(function(e){helper.cleanUp(e+"/node_modules")})}var grunt=require("grunt"),path=require("path"),fs=require("fs"),helper=require("./helper"),fixtures=helper.fixtures,useFixtures=["multiTargets","oneTarget","atBegin","dateFormat"];exports.watch={setUp:function(e){cleanUp(),useFixtures.forEach(function(e){fs.symlinkSync(path.join(__dirname,"../../node_modules"),path.join(fixtures,e,"node_modules"))}),e()},tearDown:function(e){cleanUp(),e()},atBegin:function(e){e.expect(1);var t=path.resolve(fixtures,"atBegin");helper.assertTask(["watch","--debug"],{cwd:t})(function(){},function(t){helper.verboseLog(t);var n=t.indexOf("one has changed");e.ok(-1!==n,"Watch should have fired even though no file was changed."),e.done()})},dateFormat:function(e){e.expect(1);var t=path.resolve(fixtures,"dateFormat");helper.assertTask(["watch","--debug"],{cwd:t})(function(){grunt.file.write(path.join(t,"lib","one.js"),"var one = true;")},function(t){helper.verboseLog(t),e.ok(-1!==t.indexOf("dateFormat has worked!"),"Should have displayed a custom dateFormat."),e.done()})},oneTarget:function(e){e.expect(2);var t=path.resolve(fixtures,"oneTarget");helper.assertTask(["watch","--debug"],{cwd:t})(function(){grunt.file.write(path.join(t,"lib","one.js"),"var test = true;")},function(t){helper.verboseLog(t),e.ok(-1!==t.indexOf('File "lib'+path.sep+'one.js" changed'),"Watch should have fired when oneTarget/lib/one.js has changed."),e.ok(-1!==t.indexOf("I do absolutely nothing."),"echo task should have fired."),e.done()})},multiTargetsTriggerOneNotTwo:function(e){e.expect(2);var t=path.resolve(fixtures,"multiTargets");helper.assertTask("watch",{cwd:t})(function(){grunt.file.write(path.join(t,"lib","one.js"),"var test = true;")},function(t){helper.verboseLog(t),e.ok(-1!==t.indexOf("one has changed"),"Only task echo:one should of emit."),e.ok(-1===t.indexOf("two has changed"),"Task echo:two should NOT emit."),e.done()})},multiTargetsSequentialFilesChangeTriggerBoth:function(e){e.expect(2);var t=path.resolve(fixtures,"multiTargets");helper.assertTask("watch",{cwd:t})([function(){grunt.file.write(path.join(t,"lib","one.js"),"var test = true;")},function(){grunt.file.write(path.join(t,"lib","two.js"),"var test = true;")}],function(t){helper.verboseLog(t),e.ok(-1!==t.indexOf("one has changed"),"Task echo:one should of emit."),e.ok(-1!==t.indexOf("two has changed"),"Task echo:two should of emit."),e.done()})},multiTargetsSimultaneousFilesChangeTriggerBoth:function(e){e.expect(2);var t=path.resolve(fixtures,"multiTargets");helper.assertTask("watch",{cwd:t})([function(){grunt.file.write(path.join(t,"lib","one.js"),"var test = true;"),grunt.file.write(path.join(t,"lib","two.js"),"var test = true;")}],function(t){helper.verboseLog(t),e.ok(-1!==t.indexOf("one has changed"),"Task echo:one should of emit."),e.ok(-1!==t.indexOf("two has changed"),"Task echo:two should of emit."),e.done()})},spawnOneAtATime:function(e){e.expect(1);var t=path.resolve(fixtures,"multiTargets");helper.assertTask("watch",{cwd:t})(function(){grunt.file.write(path.join(t,"lib","wait.js"),"var wait = false;"),setTimeout(function(){grunt.file.write(path.join(t,"lib","wait.js"),"var wait = true;")},500)},function(t){helper.verboseLog(t),e.ok(-1!==t.indexOf("I waited 2s"),"Task should have waited 2s and only spawned once."),e.done()})},interrupt:function(e){e.expect(1);var t=path.resolve(fixtures,"multiTargets");helper.assertTask("watch",{cwd:t})(function(){grunt.file.write(path.join(t,"lib","interrupt.js"),"var interrupt = false;"),setTimeout(function(){grunt.file.write(path.join(t,"lib","interrupt.js"),"var interrupt = true;")},1e3)},function(t){helper.verboseLog(t),e.ok(-1!==t.indexOf("have been interrupted"),"Task should have been interrupted."),e.done()})},failingTask:function(e){e.expect(2);var t=path.resolve(fixtures,"multiTargets");helper.assertTask("watch",{cwd:t})(function(){grunt.file.write(path.join(t,"lib","fail.js"),"var fail = false;")},function(t){helper.verboseLog(t),e.ok(-1!==t.toLowerCase().indexOf("fatal"),"Task should have been fatal."),e.equal(grunt.util._(t).count("Waiting..."),2,'Should have displayed "Wating..." twice'),e.done()})}};