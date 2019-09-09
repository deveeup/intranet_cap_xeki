module.exports = function(grunt) {
  // Folder to minified files
  var minifyFolder = "compressed";

  var subFolderLib = "";

  grunt.initConfig({
    // Minify CSS task
    clean: {
      folder: ["static_files/" + minifyFolder]
    },
    copy: {
      main: {
        files: [
          // images
          {
            expand: true,
            cwd: "static_files/" + subFolderLib + "/",
            src: ["**/*.png"],
            dest: "static_files/" + minifyFolder + "/" + subFolderLib + "/",
            filter: "isFile"
          },
          {
            expand: true,
            cwd: "static_files/" + subFolderLib + "/",
            src: ["**/*.gif"],
            dest: "static_files/" + minifyFolder + "/" + subFolderLib + "/",
            filter: "isFile"
          },
          {
            expand: true,
            cwd: "static_files/" + subFolderLib + "/",
            src: ["**/*.svg"],
            dest: "static_files/" + minifyFolder + "/" + subFolderLib + "/",
            filter: "isFile"
          },
          {
            expand: true,
            cwd: "static_files/" + subFolderLib + "/",
            src: ["**/*.jpg"],
            dest: "static_files/" + minifyFolder + "/" + subFolderLib + "/",
            filter: "isFile"
          },
          {
            expand: true,
            cwd: "static_files/" + subFolderLib + "/",
            src: ["**/*.jpge"],
            dest: "static_files/" + minifyFolder + "/" + subFolderLib + "/",
            filter: "isFile"
          },

          // fonts
          {
            expand: true,
            cwd: "static_files/" + subFolderLib + "/",
            src: ["**/*.eot"],
            dest: "static_files/" + minifyFolder + "/" + subFolderLib + "/",
            filter: "isFile"
          },
          {
            expand: true,
            cwd: "static_files/" + subFolderLib + "/",
            src: ["**/*.ttf"],
            dest: "static_files/" + minifyFolder + "/" + subFolderLib + "/",
            filter: "isFile"
          },
          {
            expand: true,
            cwd: "static_files/" + subFolderLib + "/",
            src: ["**/*.woff"],
            dest: "static_files/" + minifyFolder + "/" + subFolderLib + "/",
            filter: "isFile"
          },

          // less
          {
            expand: true,
            cwd: "static_files/" + subFolderLib + "/",
            src: ["**/*.less"],
            dest: "static_files/" + minifyFolder + "/" + subFolderLib + "/",
            filter: "isFile"
          }
        ]
      }
    },
    cssmin: {
      target: {
        files: [
          {
            expand: true,
            cwd: "static_files/",
            src: ["**/*.css"],
            dest: "static_files/" + minifyFolder + "/"
          }
        ]
      }
    },
    // Minify JS task
    uglify: {
      target_files: {
        files: [
          {
            expand: true,
            cwd: "static_files/",
            src: "**/*.js",
            dest: "static_files/" + minifyFolder + "/"
          }
        ]
      },
      options: {
        banner: "",
        footer: ""
      }
    }
  });

  // Loads needed modules
  grunt.loadNpmTasks("grunt-contrib-clean");
  grunt.loadNpmTasks("grunt-contrib-uglify");
  grunt.loadNpmTasks("grunt-contrib-cssmin");
  grunt.loadNpmTasks("grunt-newer");
  grunt.loadNpmTasks("grunt-contrib-copy");

  grunt.registerTask("default", [
    "newer:clean",
    "newer:copy",
    "newer:cssmin",
    "newer:uglify"
  ]);
};
