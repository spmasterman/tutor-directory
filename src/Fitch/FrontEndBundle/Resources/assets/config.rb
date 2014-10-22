##
# NOT USED - USE filter definition in config.yml to define things
#
# available options in vendor/symfony/assetic-bundle/Symfony/Bundle/AsseticBundle/Resources/config/filters/compass.xml
# errors doing assetic:dump should be fixed in recent pull requests to assetic - if we need to dump before then
# might need to compile the scss from compass command line and manually link stuff in (i.e. work round assetic)
# in which case getting the paramteres fixed in here will be key... /Shaun
##

# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "stylesheets"
sass_dir = "scss"
images_dir = "images"
javascripts_dir = "javascripts"

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed

# To enable relative paths to assets via compass helper functions. Uncomment:
# relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
# line_comments = false


# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass
