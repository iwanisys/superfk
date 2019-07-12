/*!
 * Gulp asset automation script.
 * 
 * @since 1.0.0
 * 
 * @package Nav Menu Collapse
 */

const argv = require('yargs').argv;
const del = require('del');
const clone = require('gulp-clone');
const cssnano = require('gulp-cssnano');
const gulp = require('gulp');
const gulpif = require('gulp-if');
const imagemin = require('gulp-imagemin');
const jshint = require('gulp-jshint');
const merge = require('merge-stream');
const replace = require('gulp-replace');
const rev = require('gulp-rev');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const uglify = require('gulp-uglify');

var paths =
{
	"manifest": './manifest.json',
	"debug": './debug',
	"release": './release',
	"assets": './assets',
	"images": '/images',
	"scripts": '/scripts',
	"styles": '/styles'
};

var regex =
{
	"comments_replace": '\n$1\n',
	"comments_search": /(\/\*\![.\n\r\s\S\t]*?\*\/)/g,
	"empty_lines": /^\s*\r?\n/gm,
	"preserve_comments": /^!/
};

function clean()
{
	return del([paths.manifest, paths.debug, paths.release]);
}

function scripts()
{
	var source = gulp
		.src(paths.assets + paths.scripts + '/*.js')
		.pipe(jshint())
		.pipe(jshint.reporter('default'))
		.pipe(gulpif(!argv.production, sourcemaps.init()))
		.pipe(rev());
	
	var debug = source
		.pipe(clone())
		.pipe(gulpif(!argv.production, sourcemaps.write('.')))
		.pipe(gulp.dest(paths.debug + paths.scripts));
		
	var release = source
		.pipe(clone())
		.pipe(uglify(
		{
			"output":
			{
				"comments": regex.preserve_comments
			}
		}))
		.pipe(replace(regex.comments_search, regex.comments_replace))
		.pipe(replace(regex.empty_lines, ''))
		.pipe(gulpif(!argv.production, sourcemaps.write('.')))
		.pipe(gulp.dest(paths.release + paths.scripts))
		.pipe(rev.manifest(paths.manifest,
		{
			"merge": true
		}))
		.pipe(gulp.dest('.'));
		
	return merge(debug, release);
}

function styles()
{
	var source = gulp
		.src(paths.assets + paths.styles + '/*.scss')
		.pipe(gulpif(!argv.production, sourcemaps.init()))
		.pipe(sass(
		{
			"outputStyle": 'expanded'
		}))
		.pipe(rev());
		
	var debug = source
		.pipe(clone())
		.pipe(gulpif(!argv.production, sourcemaps.write('.')))
		.pipe(gulp.dest(paths.debug + paths.styles));
		
	var release = source
		.pipe(clone())
		.pipe(cssnano(
		{
			"safe": true
		}))
		.pipe(replace(regex.comments_search, regex.comments_replace))
		.pipe(replace(regex.empty_lines, ''))
		.pipe(gulpif(!argv.production, sourcemaps.write('.')))
		.pipe(gulp.dest(paths.release + paths.styles))
		.pipe(rev.manifest(paths.manifest,
		{
			"merge": true
		}))
		.pipe(gulp.dest('.'));
		
	return merge(debug, release);
}

function images()
{
	return gulp
		.src(paths.assets + paths.images + '/*')
		.pipe(imagemin(
		{
			"optimizationLevel": 3,
			"progressive": true,
			"interlaced": true
		}))
		.pipe(gulp.dest(paths.debug + paths.images))
		.pipe(gulp.dest(paths.release + paths.images));
}

var build = gulp.series(clean, scripts, styles, images);
gulp.task('build', build);
gulp.task('default', build);
