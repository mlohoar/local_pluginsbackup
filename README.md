# Plugins Backup local_pluginsbackup
> A Moodle plugin to help automate updates by backing up additional plugins

When updating Moodle, any additional plugins need to be restored after the main body of code has been updated.
This plugin provides a way to manually or automatically trigger the backup of the files associated with additional plugins
to be backed up to a defined location so that they can be restored.

- [Installation] (#installation)
- [Settings] (#settings)

## Installation

Install the plugin by the normal means either as a zip or via a Git pull to the folder

{moodle root dir}/local/pluginsbackup

navigation for the plugin setting is added in the standard admin page:

Site Administration ->  Plugins -> (Local Plugins) Red Tuna Plugins Backup 

and for triggering backups

Site Administration ->  Plugins -> (Plugins) Backup Additional Plugins 


## Settings

System settings allow the configuration of:

#### Active
Whether backups are active at all or not

#### Backup Path
The base location where the backups will be made e.g. c:\backup\plugins\

#### Password
The plugin can be triggered from an http call to {moodle root}/local/pluginsbackup/backup.php?password={password}
to enable automation.  Set the required password here.

## On Backup screen

#### Plugin Selection
All the additional plugins are listed and are defaulted to be backed up.
Uncheck those plugins which are not required.

#### Backup Folder Subdirectory
If a backup is required to an additional subdirectory of the base location fo backup and additional folder can be defined.


