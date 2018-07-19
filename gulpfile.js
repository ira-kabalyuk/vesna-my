var gulp = require('gulp');
var browserSync = require('browser-sync').create();
var sass = require('gulp-sass');
var imagemin = require('gulp-imagemin');
var del = require('del');
var useref = require('gulp-useref');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
var gulpif = require('gulp-if');
var minifyCss = require('gulp-minify-css');
var spritesmith = require('gulp.spritesmith');
var buffer = require('vinyl-buffer');
var merge = require('merge-stream');
var nunjucks = require('gulp-nunjucks');

var src = {
    app: 'app/',
    html: 'app/**/*.html',
    js: 'app/scripts/*.js',
    sass: 'app/styles/*.scss',
    images: 'app/images/*',
    fonts: 'app/fonts/*',
    spriteImages: 'app/images/sprite/*.png',
    tmp: '.tmp/',
    dist: 'dist/',
    distHtml: 'dist/*.html'
}


gulp.task('styles', function () {
    return gulp.src(src.sass)
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'nested'}))
        .pipe(autoprefixer())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(src.tmp +'/styles'))
        .pipe(browserSync.stream());
});

gulp.task('sprite', function () {
    var spriteData = gulp.src(src.spriteImages).pipe(spritesmith({
        imgName: 'sprite.png',
        cssName: '_sprite.scss',
        cssFormat: 'css',
        imgPath: '../images/sprite.png'
    }));

    var imgStream = spriteData.img
    // DEV: We must buffer our stream into a Buffer for `imagemin`
        .pipe(buffer())
        .pipe(imagemin())
        .pipe(gulp.dest(src.tmp + '/images'))

    var cssStream = spriteData.css
        .pipe(gulp.dest('app/styles/'))

    // Return a merged stream to handle both `end` events
    return merge(imgStream, cssStream);
});


gulp.task('scripts', function() {
    return gulp.src(src.js)
        //.pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(src.tmp + '/scripts'))
        .pipe(browserSync.stream());
});

gulp.task('fonts', function() {
    return gulp.src(src.fonts)
        .pipe(gulp.dest(src.tmp + '/fonts'))
        .pipe(browserSync.stream());
});


gulp.task('images', function() {
    return gulp.src(src.images)
        .pipe(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true }))
        .pipe(gulp.dest(src.tmp + '/images'))
        .pipe(browserSync.stream());
});


gulp.task('nunjucks', function() {
    return gulp.src(src.html)
        .pipe(nunjucks.compile())
        .pipe(gulp.dest(src.tmp))
        .pipe(browserSync.stream());
});

gulp.task('serve', ['styles', 'fonts', 'scripts', 'nunjucks', 'images'], function() {
    browserSync.init({
        server: {
            baseDir: [src.tmp],
            routes: {
                "/bower_components": "bower_components"
            }
        }
    });

    gulp.watch(src.spriteImages, ['sprite', 'styles']);
    gulp.watch(src.sass, ['styles']);
    gulp.watch(src.js, ['scripts']);
    gulp.watch(src.images, ['images']);
    var htmlWatcher = gulp.watch(src.html, ['nunjucks']);
    // gulp.watch(src.distHtml, function(event) {
    //   browserSync.reload();
    // });
});


gulp.task('serve:dist', ['build'], function() {
    browserSync.init({
        server: src.dist
    });
});


gulp.task('clean', function() {
    return del.sync([src.tmp, src.dist]);
});


gulp.task('copy', ['sprite', 'styles', 'fonts', 'images'], function() {
    gulp.src(src.tmp + '/images/**/*')
        .pipe(gulp.dest(src.dist + '/images'));

    return gulp.src(src.app + '/fonts/**/*')
        .pipe(gulp.dest(src.dist + '/fonts'))
});


gulp.task('build', ['clean', 'copy'], function() {
    var assets = useref.assets({searchPath: ['.tmp', 'app', '.']});
    return gulp.src(src.html)
        .pipe(nunjucks.compile())
        .pipe(assets)
        //.pipe(gulpif(isFileExt('js'), uglify()))
        .pipe(gulpif(isFileExt('css'), minifyCss()))
        .pipe(assets.restore())
        .pipe(useref())
        .pipe(gulp.dest(src.dist));
});


function isFileExt(ext) {
    return function(file) {
        var parts = file.path.split('.');
        var last = parts[parts.length -1];
        return (ext === last);
    }
}