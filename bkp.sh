SHELL=/bin/bash
BASEDIR=$(dirname "$0")
MUSER="root" 
MPASS="" 
MDB="walmart"
NOW=$(date +"%m-%d-%Y-%H-%M")
BKPFILE="$BASEDIR/bkps/$NOW-$MDB.sql";
echo $BKPFILE >> "$BASEDIR/user.txt"
MYSQLDUMP=$(which mysqldump)
MYSQLDUMP="/opt/lampp/bin/mysqldump"
$MYSQLDUMP -u$MUSER -p$MPASS $MDB  > $BKPFILE