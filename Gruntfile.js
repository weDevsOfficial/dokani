'use strict';
module.exports = function(grunt) {
    var pkg = grunt.file.readJSON('package.json');
    grunt.initConfig({

        // setting folder templates
        dirs: {
            css: 'assets/css',
            less: 'assets/less',
            fonts: 'assets/fonts',
            images: 'assets/images',
            js: 'assets/js'
        },

        // Compile all .less files.
        less: {

            // one to one
            front: {
                files: {
                    '<%= dirs.css %>/master.css': '<%= dirs.less %>/master.less',
                },
                options: {
                    sourceMap: true,
                    sourceMapRootpath: '../../'
                }
            },
        },

        watch: {
            less: {
                files: ['<%= dirs.less %>/*.less'],
                tasks: ['less:front'],
                options: {
                    livereload: true
                }
            }
        },
    });

    // Load NPM tasks to be used here
    grunt.loadNpmTasks( 'grunt-contrib-less' );
    grunt.loadNpmTasks( 'grunt-contrib-watch' );

    grunt.registerTask( 'default', [
        'watch',
    ]);
};