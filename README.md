# wp-miogest-sync
The wordpress plugin to syncronize miogest with Worpdress every night

## Project purpose

This project consist in a **wordpress plugin** which is intended for **internal use only**. The purpose of the plugin is synchronizing with a **gestional software (management software)** for housing advertisements. The gestional software is called **miogest** and offered data as **xml served from a simple url**. The plugin parses this data and **fills the wordpress' database** with it. The data is then make available to users with the **resideo plugin and theme**, which was **slightly modified** to fulfill our needs.

## Project history

Most of the work has been done by a first programmer, but because of some bugs such as **duplicate post names** and **missing thumbnail functions**, the project was given to maintainance to a second programmer. The code was really messy, so it has been **totally rewritten** using modern php, classes syntax, moduled structure and with bug fixes.

## Parts of the repository

The repository is divided in two parts, one is the resideo-child-theme and the resideo plugin, while the other one is the real wordpress plugin.

### Resideo edits

Some modifications were done to the Resideo plugin and child theme. In the folder `themes_plugins_modified` there is a full copy of all those plugins. This is because every time there is an edit, it is just easier to copy-paste the whole directory on github, or to erase the old one and replace it when you want to put this code in production.

In particular, the files that has been modified are:

* On the resideo child theme:
   - `single-property.php`

* On the resideo theme:
   - `gallery.js`

* On the resideo plugin:
   - `page-templates/property-search.php`
   - `shortcodes/featured_properties.php`
   - `views/similar-properties.php`
   - `views/contact-agent-modal.php`

Almost all the modifications were about the fact that **resideo expected images to be attachments, local saved in the worpress site**. Instead, the photos are saved **by url provided by miogest**, so some little changes about that have been made by the first programmer. The other modification was done after the **thumbnail fix**, in practice, the first image is a thumbnail that should be removed in some places of the resideo plugin.

**Note:** The resideo theme itself should not be edited, but it was the only way found to edit the gallery and make it support videos. In that folder, there are just the changed files, so during the migration in the production environment, this folder should be copied onto the old one, without deleting the old one.

### Plugin

The rest of the code is just the **wordpress plugin**, that offers the synchronization with the miogest data.

## What does the plugin do

### On the plugin activation

Nothing special is done, apart from the activation itself. **If the plugin is not activated, the sync.php script won't do anything**.

### On the plugin disinstallation

It should remove every content related to miogest, including all posts. The **table** is not removed, even if it should.

### On the synchronization

First of all, the synchronization is done in the file `sync.php` and should be done with a `cronjob`.

What is done is:
1. Create the table `miogest_synced_annunci` if it does not exist.
2. Fetching all the remote content from miogest. Namely, all ads and other two resources: their categories and the various translations for the "stato immobile".
3. Get all posts ids by looking for posts with `post_type` equal to `property`. This method was better than just looking for the ids in the `miogest_synced_annunci` table.
4. Resetting all data related to the posts of miogest. By using posts ids, all the posts, entries in the `miogest_synced_annunci` table, posts meta info, relationships and translation bindings are deleted.
5. Resetting all the taxonomies and terms related to the properties (`property_status` and `propery_type`).
6. Also all the attachments are deleted before a new syncing, they are recognized by the prefix `miogest_sync_`.
7. Insert the updated taxonomies and terms in base of the `categorie` fetched by the XML.
8. The new announcements are added to wordpress, this means:
   - Adding a post for each ad
   - Adding meta data for each post
   - Adding a `miogest_synced_annunci` entry for each post
   - Adding translations to bind posts of same announcement, but with different language
   - Adding relationships of posts to taxonomies and terms
   - Adding a thumbnail which consists in the first image and has to be local in the wordpress server

## Issues solved

The second programmer also solved the first issues:
* Some post names of different announcements where the same and this entailed some problems. The issue is solved by **adding as postfix the ad id to the post name**.
* Some post names were illegal, because containing special characters. The issue has been solved by **replacing the special character with an underscore**.
* The date of the post was **not in UTC**, which was wrong.
* The thumbnail was missing. The issue was solved **by adding a real thumbnail attachment and by adding it also as first field of the meta gallery property**.
* Added `"Prezzo riservato"` instead of the price if the price is reserved. Before that, `0€` appeared. The issue was solved **by adding a meta prop `price_reserved` and by changing slightly the resideo theme/plugin**.
* There was a problem because all the properties posts **had "in vendita" (for sale) as status in the taxonomies**
* The taxonomies and terms for the properties **had been added manually to the db** and **the code used the specific ids** to connect xml annunci's categories with the taxonomies. It was not reproducible and very, very dumb. In addition all the languages' posts received the italian taxonomies, so filters worked only with the Italian language. All of this has been **rewritten from scratch**.
* Adding a way to **parse the videos** from the xml and **show the videos in the website**. The video is shown using the default Resideo field to show it but, for future usage, in case of multiple videos, the gallery has been modified and should show all the videos in that case.
* Adding `"Affitto"` to the *allowed property types*. A new record with that category was being excluded for that reason.

## Deployment

### Locally

You should have **PHP 7.4** at least and **Composer** installed. You should run `composer install` before programming. 
The componenet should be placed in the `/wp-content/plugins` dir of a wordpress website.
You can run `composer run rector` to add backward compatibility to the code, before going in production (note that this **will overwrite the current code**).

**Add pushes just to the dev branch and before making pull requests increase the composer version**.

### Github action

The developer should **push on the dev** branch and only **merge on the main branch**, after having **updated the composer.json version** and also the wp plugin ones. After that, with a **github action**, the code will be reprouced, with composer installation and everything. A **release** will be created with the same version of the **composer.json** file and with **two zip files**: one for the **resideo child template and resideo plugin** and one for the **wordpress plugin**. 

Note that **rector** is not run in the action because it should include some external wordpress code to properly work, which is not in the github repo.

There could be some problems with the **vendor** php version of composer, if this is the case, just build the plugin yourself.

### How to use it

In the **releases** section, open the **last** release, which will have two zip files.

The two zip files are:
* `wp-miogest-sync.zip`: this is the **plugin** and, after being extracted, should be placed with the other plugins in worpdress.
* `wp-miogest-sync-resideo-modified.tar.gz`: this will contain the **resideo child theme** and **resideo plugin**, they will replace the analogue folders that are already there.

After that, just activate the plugin if it is not already activated.