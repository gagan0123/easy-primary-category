
module.exports = function ( grunt ) {

	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),
		wp_readme_to_markdown: {
			dist: {
				options: {
					screenshot_url: '<%= pkg.repository.url %>/raw/master/assets/{screenshot}.png',
					post_convert: function ( file ) {
						return "<img src='" + grunt.config.get( 'pkg' ).repository.url + "/raw/master/assets/icon-128x128.png' align='right' />\n\n" + file;
					}
				},
				files: {
					'README.md': 'readme.txt'
				}
			}
		},
		sass: {
			dist: {
				options: {
					style: 'compressed'
				},
				files: [ {
						expand: true,
						cwd: 'admin/scss',
						src: [
							'*.scss'
						],
						dest: 'admin/css',
						ext: '.min.css'
					} ]
			}
		},
		uglify: {
			dist: {
				options: {
					mangle: {
						reserved: [ 'jQuery', '$' ]
					},
					sourceMap: true,

				},
				files: {
					'admin/js/epc-taxonomy-metabox.min.js': [ 'admin/js/epc-taxonomy-metabox.js' ]
				}
			}
		},
		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					mainFile: 'easy-primary-category.php',
					potFilename: 'easy-primary-category.pot',
					type: 'wp-plugin',
					potHeaders: {
						poedit: false
					}
				}
			}
		},
		watch: {
			grunt: {
				files: [ 'Gruntfile.js' ]
			},
			sass: {
				files: [ 'admin/scss/*.scss' ],
				tasks: [ 'sass' ]
			},
			uglify: {
				files: [ 'admin/js/*.js', '!admin/js/*.min.js' ],
				tasks: [ 'uglify' ]
			},
			wp_readme_to_markdown: {
				files: [ 'readme.txt' ],
				tasks: [ 'wp_readme_to_markdown' ]
			}
		}
	} );

	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
	grunt.loadNpmTasks( 'grunt-contrib-sass' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	grunt.registerTask( 'default', [
		'watch'
	] );

};