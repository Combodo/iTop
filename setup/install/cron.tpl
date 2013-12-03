#
# Regular cron jobs for the _ITOP_NAME_ package
#

#MAILTO=root

#
# Main heartbeat scheduler for _ITOP_NAME_, launched every 5 minutes
#

*/5 * * * * root php _ITOP_DATADIR_/_ITOP_NAME_/webservices/cron.php --param_file=_ITOP_SYSCONFDIR_/_ITOP_NAME_/production/cron.params >> _ITOP_LOGDIR_/_ITOP_NAME_/cron.log 2>&1

# # # # #   #
# # # # #   #-- User name
# # # # #------ Day of week (0-7) 0 == 7 == Sunday
# # # #-------- Month (1 - 12)
# # #---------- Day of month (1 - 31)
# #------------ Hour (0 - 23)
#-------------- Min (0 - 59)
