#! /bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )"/.. && pwd )"
PLUGINSLUG="$(basename $DIR)"
TEMPPATH="/tmp/$PLUGINSLUG"
ZIP_PATH="$DIR/../$PLUGINSLUG.zip"

rm -rf $TEMPPATH
rm $ZIP_PATH
rsync -r --delete --exclude-from $DIR/bin/exclude-list.txt $DIR/ $TEMPPATH/
cd $TEMPPATH
zip -rq $ZIP_PATH ./