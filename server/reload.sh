echo "reloading..."
pid=`pidof live_master`
echo $pid
kill -USR1 $pid
echo "reload success"
